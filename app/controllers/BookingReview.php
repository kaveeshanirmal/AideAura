<?php

class BookingReview extends Controller
{
    private $bookingModel;
    private $reviewModel;

    public function __construct()
    {
        // Ensure user is logged in
        if (!isset($_SESSION['customerID'])) {
            // Redirect to login if not logged in
            header('Location: ' . ROOT . '/public/login');
            exit();
        }
        
        $this->bookingModel = new BookingModel();
        $this->reviewModel = new BookingReviewModel();
    }

    /**
     * Check for pending reviews
     * Used via AJAX to determine if there are completed bookings without reviews
     */
    public function checkPendingReviews()
    {
        // Turn off output buffering and clear any previous output
        ob_clean();
        
        // Set content type to JSON
        header('Content-Type: application/json');
        
        if (!isset($_SESSION['customerID'])) {
            echo json_encode(['error' => 'User not logged in']);
            exit;
        }

        try {
            // Get completed bookings without reviews
            $pendingReviews = $this->reviewModel->getCompletedBookingsWithoutReviews($_SESSION['customerID']);
            
            // Add worker names to each booking
            foreach ($pendingReviews as &$booking) {
                $workerName = $this->reviewModel->getWorkerName($booking->workerID);
                $booking->firstName = $workerName->firstName;
                $booking->lastName = $workerName->lastName;
            }
            
            echo json_encode(['success' => true, 'pendingReviews' => $pendingReviews]);
        } catch (Exception $e) {
            echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
        }
        exit;
    }

    /**
     * Submit a review
     * Handles AJAX submission of review data
     */
    public function submitReview()
    {
        // Turn on error logging to a file
        ini_set('log_errors', 1);
        ini_set('error_log', '/path/to/your/error_log.txt'); // Change this to a valid path
        
        // Set content type to JSON
        header('Content-Type: application/json');
        
        try {
            if (!isset($_SESSION['customerID'])) {
                throw new Exception('User not logged in');
            }

            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('Invalid request method');
            }

            // Log received data for debugging
            error_log('Review submission data: ' . print_r($_POST, true));

            // Get POST data
            $bookingID = isset($_POST['bookingID']) ? filter_var($_POST['bookingID'], FILTER_SANITIZE_NUMBER_INT) : null;
            $workerID = isset($_POST['workerID']) ? filter_var($_POST['workerID'], FILTER_SANITIZE_NUMBER_INT) : null;
            $rating = isset($_POST['rating']) ? filter_var($_POST['rating'], FILTER_SANITIZE_NUMBER_INT) : null;
            $comment = isset($_POST['comment']) ? filter_var($_POST['comment'], FILTER_SANITIZE_FULL_SPECIAL_CHARS) : null;

            // Validate input
            if (!$bookingID || !$workerID || !$rating || $rating < 1 || $rating > 5) {
                throw new Exception('Invalid input data: bookingID='.$bookingID.', workerID='.$workerID.', rating='.$rating);
            }

            // Verify booking belongs to the customer and is completed
            $booking = $this->bookingModel->getBasicBookingData($bookingID);
            
            if (!$booking) {
                throw new Exception('Booking not found: '.$bookingID);
            }
            
            if ($booking->customerID != $_SESSION['customerID']) {
                throw new Exception('Booking does not belong to this customer');
            }
            
            if ($booking->status != 'completed') {
                throw new Exception('Booking is not completed');
            }

            // Check if review already exists
            if ($this->reviewModel->checkReviewExists($bookingID)) {
                throw new Exception('Review already submitted for this booking');
            }

            // Create review data
            $reviewData = [
                'bookingID' => $bookingID,
                'workerID' => $workerID,
                'customerID' => $_SESSION['customerID'],
                'rating' => $rating,
                'comment' => $comment
            ];

            // Insert review
            $result = $this->reviewModel->createReview($reviewData);
            
            if (!$result) {
                throw new Exception('Failed to insert review into database');
            }
            
            echo json_encode(['success' => true, 'message' => 'Review submitted successfully']);
            
        } catch (Exception $e) {
            // Log the exception
            error_log('Review submission error: ' . $e->getMessage());
            error_log('Stack trace: ' . $e->getTraceAsString());
            
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
        exit;
    }

    /**
     * Check if a review has already been shown to the user
     * 
     * @param int $bookingID The booking ID
     * @return bool True if the review has been shown, false otherwise
     */
    private function isReviewShown($bookingID)
    {
        if (!isset($_SESSION['shownReviews'])) {
            $_SESSION['shownReviews'] = [];
        }
        
        return in_array($bookingID, $_SESSION['shownReviews']);
    }

    /**
     * Mark a review as shown to the user
     * 
     * @param int $bookingID The booking ID
     */
    private function markReviewAsShown($bookingID)
    {
        if (!isset($_SESSION['shownReviews'])) {
            $_SESSION['shownReviews'] = [];
        }
        
        if (!in_array($bookingID, $_SESSION['shownReviews'])) {
            $_SESSION['shownReviews'][] = $bookingID;
        }
        
        // Mark that we've shown a review this session
        $_SESSION['reviewShownThisSession'] = true;
    }

    /**
     * Get ONE pending review per user session
     * Will only return a review if none have been shown yet this session
     */
    public function getPendingReviews()
    {
        // Set content type to JSON
        header('Content-Type: application/json');
        
        if (!isset($_SESSION['customerID'])) {
            echo json_encode(['success' => false, 'error' => 'User not logged in']);
            exit;
        }

        try {
            // Check if we've already shown a review in this session
            if (isset($_SESSION['reviewShownThisSession']) && $_SESSION['reviewShownThisSession'] === true) {
                // Already shown a review this session, don't show more
                echo json_encode(['success' => true, 'pendingReviews' => []]);
                exit;
            }
            
            // Get completed bookings without reviews
            $pendingReviews = $this->reviewModel->getCompletedBookingsWithoutReviews($_SESSION['customerID']);
            
            // Initialize unshown reviews array
            $unshownReviews = [];
            
            // Find the first review that hasn't been marked as permanently shown
            foreach ($pendingReviews as $booking) {
                if (!$this->isReviewShown($booking->bookingID)) {
                    // Add worker names to the booking
                    $workerName = $this->reviewModel->getWorkerName($booking->workerID);
                    
                    if ($workerName) {
                        $booking->firstName = $workerName->firstName;
                        $booking->lastName = $workerName->lastName;
                    } else {
                        $booking->firstName = "Unknown";
                        $booking->lastName = "Worker";
                    }
                    
                    // We found one unshown review, add it to the array and break
                    $unshownReviews[] = $booking;
                    
                    // Mark that we've shown a review this session
                    $_SESSION['reviewShownThisSession'] = true;
                    
                    break; // Only get the first unshown review
                }
            }
            
            echo json_encode(['success' => true, 'pendingReviews' => $unshownReviews]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'error' => 'Database error: ' . $e->getMessage()]);
        }
        exit;
    }

    /**
     * Add a public method to handle AJAX calls to mark a review as shown
     */
    public function markAsShown()
    {
        // Set content type to JSON
        header('Content-Type: application/json');
        
        if (!isset($_SESSION['customerID'])) {
            echo json_encode(['success' => false, 'error' => 'User not logged in']);
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'error' => 'Invalid request method']);
            exit;
        }
        
        $bookingID = isset($_POST['bookingID']) ? filter_var($_POST['bookingID'], FILTER_SANITIZE_NUMBER_INT) : null;
        
        if (!$bookingID) {
            echo json_encode(['success' => false, 'error' => 'Invalid booking ID']);
            exit;
        }
        
        $this->markReviewAsShown($bookingID);
        echo json_encode(['success' => true]);
        exit;
    }

    /**
     * Reset the review shown flag when user logs out
     * This should be called in your logout method
     */
    public function resetReviewShownFlag()
    {
        if (isset($_SESSION['reviewShownThisSession'])) {
            unset($_SESSION['reviewShownThisSession']);
        }
    }
}
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

        // Get POST data
        $bookingID = isset($_POST['bookingID']) ? filter_var($_POST['bookingID'], FILTER_SANITIZE_NUMBER_INT) : null;
        $workerID = isset($_POST['workerID']) ? filter_var($_POST['workerID'], FILTER_SANITIZE_NUMBER_INT) : null;
        $rating = isset($_POST['rating']) ? filter_var($_POST['rating'], FILTER_SANITIZE_NUMBER_INT) : null;
        $comment = isset($_POST['comment']) ? filter_var($_POST['comment'], FILTER_SANITIZE_FULL_SPECIAL_CHARS) : null;

        // Validate input
        if (!$bookingID || !$workerID || !$rating || $rating < 1 || $rating > 5) {
            echo json_encode(['success' => false, 'error' => 'Invalid input data']);
            exit;
        }

        // Verify booking belongs to the customer and is completed
        $booking = $this->bookingModel->getBasicBookingData($bookingID);
        if (!$booking || $booking->customerID != $_SESSION['customerID'] || $booking->status != 'completed') {
            echo json_encode(['success' => false, 'error' => 'Invalid booking']);
            exit;
        }

        // Check if review already exists
        if ($this->reviewModel->checkReviewExists($bookingID)) {
            echo json_encode(['success' => false, 'error' => 'Review already submitted for this booking']);
            exit;
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
        
        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Review submitted successfully']);
        } else {
            echo json_encode(['success' => false, 'error' => 'Failed to submit review']);
        }
        exit;
    }
}
<?php

class Booking extends Controller
{
    private $bookingModel;
    private $notificationModel;
    private $userModel;

    private $workerModel;

    public function __construct()
    {
        $this->bookingModel = new BookingModel();
        $this->notificationModel = new NotificationModel();
        $this->userModel = new UserModel();
        $this->workerModel = new WorkerModel();
    }

    public function index()
    {
        echo 'Undefined method';
    }

    public function bookWorker()
    {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['status' => 'error', 'message' => 'Invalid request method. POST required.']);
            exit;
        }

        if (!isset($_SESSION['userID']) || !isset($_SESSION['booking_info'])) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Missing required session data']);
            exit;
        }

        $workerID = $_POST['workerID'] ?? null;
        if (!$workerID) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Worker ID is required']);
            exit;
        }

        $customerID = $_SESSION['customerID'] ?? null;
        $bookingInfo = $_SESSION['booking_info'];

        // Check required booking info fields
        $requiredFields = ['serviceType', 'preferred_date', 'service_location', 'arrival_time', 'total_cost'];
        foreach ($requiredFields as $field) {
            if (!isset($bookingInfo[$field])) {
                http_response_code(400);
                echo json_encode(['status' => 'error', 'message' => "Missing required booking information: {$field}"]);
                exit;
            }
        }

        // Extract main booking details
        $serviceType = $bookingInfo['serviceType'];
        $bookingDate = $bookingInfo['preferred_date'];
        $serviceLocation = $bookingInfo['service_location'];
        $arrivalTime = $bookingInfo['arrival_time'];
        $totalCost = $bookingInfo['total_cost'];

        // Wrap all other booking_info in a single array
        $details = [];
        foreach ($bookingInfo as $key => $value) {
            if (!in_array($key, $requiredFields)) {
                $details[] = (object) ['key' => $key, 'value' => $value];
            }
        }

        // Create booking
        $bookingID = $this->bookingModel->createBooking(
            $workerID,
            $customerID,
            $serviceType,
            $bookingDate,
            $serviceLocation,
            $arrivalTime,
            $totalCost,
            $details
        );
        // If bookingID is false, return error
        if (!$bookingID) {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Failed to create booking']);
            exit;
        }

        // Save booking data to session by fetching it from the database
        $bookingData = $this->bookingModel->getBasicBookingData($bookingID);
        if ($bookingData) {
            $_SESSION['booking'] = [
                'bookingID' => $bookingData->bookingID,
                'workerID' => $bookingData->workerID,
                'customerID' => $bookingData->customerID,
                'serviceType' => $bookingData->serviceType,
                'bookingDate' => $bookingData->bookingDate,
                'location' => $bookingData->location,
                'startTime' => $bookingData->startTime,
                'totalCost' => $bookingData->totalCost,
                'status' => $bookingData->status,
                'createdAt' => $bookingData->createdAt,
            ];
        } else {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Failed to fetch booking data']);
            exit;
        }

        // create notifications for both customer and worker
        $this->notificationModel->create(
            $this->userModel->getUserID($workerID, 'worker'),
            'Booking Request',
            'You have a new booking request.',
            'Navigate to your dashboard to accept or reject the request.'
        );

        $this->notificationModel->create(
            $this->userModel->getUserID($customerID, 'customer'),
            'Booking Confirmation',
            'Booking requested.',
            'We\'re waiting for the worker to accept your request.'
        );

        echo json_encode([
            'status' => 'success',
            'message' => 'Booking created successfully',
            'bookingID' => $bookingID
        ]);
        exit;
    }

    public function getBooking($bookingID)
    {
        header('Content-Type: application/json');

        if (!isset($_SESSION['userID'])) {
            http_response_code(401);
            echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
            exit;
        }

        $booking = $this->bookingModel->getBookingDetails($bookingID);

        if ($booking) {
            echo json_encode(['status' => 'success', 'booking' => $booking]);
        } else {
            http_response_code(404);
            echo json_encode(['status' => 'error', 'message' => 'Booking not found']);
        }
    }

    //accept booking function for worker
    public function accept()
    {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['status' => 'error', 'message' => 'Invalid request method. POST required.']);
            exit;
        }

        $json = file_get_contents('php://input');
        $data = json_decode($json, true);
        $bookingID = $data['bookingID'] ?? null;

        if (!$bookingID) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Booking ID is required']);
            exit;
        }

        $workerID = $_SESSION['workerID'];

        $updateSuccess = $this->bookingModel->updateBookingStatus($bookingID, 'accepted');
        if (!$updateSuccess) {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Failed to update booking status']);
            exit;
        }

        // create notifications for both customer and worker
        $this->notificationModel->create(
            $this->userModel->getUserID($workerID, 'worker'),
            'Booking Accepted',
            'You have accepted a booking.',
            'Navigate to your dashboard to view the details.'
        );

        $customerID = $this->bookingModel->getCustomerIdByBookingId($bookingID);
        $this->notificationModel->create(
            $this->userModel->getUserID($customerID, 'customer'),
            'Booking Accepted',
            'Your booking has been accepted.',
            'Proceed with payment to confirm the booking.'
        );

        echo json_encode(['status' => 'success', 'message' => 'Booking accepted successfully']);
    }

    //reject booking function for worker
    public function reject()
    {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['status' => 'error', 'message' => 'Invalid request method. POST required.']);
            exit;
        }

        $json = file_get_contents('php://input');
        $data = json_decode($json, true);
        $bookingID = $data['bookingID'] ?? null;

        if (!$bookingID) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Booking ID is required']);
            exit;
        }

        $workerID = $_SESSION['workerID'];

        $updateSuccess = $this->bookingModel->updateBookingStatus($bookingID, 'cancelled');
        if (!$updateSuccess) {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Failed to update booking status']);
            exit;
        }


        // create notifications for both customer and worker
        $this->notificationModel->create(
            $this->userModel->getUserID($workerID, 'worker'),
            'Booking Rejected',
            'You have rejected a booking.',
            'Navigate to your dashboard to view the details.'
        );

        $customerID = $this->bookingModel->getCustomerIdByBookingId($bookingID);
        $this->notificationModel->create(
            $this->userModel->getUserID($customerID, 'customer'),
            'Booking Rejected',
            'Your booking has been rejected.',
            'Please choose another worker or service.'
        );

        echo json_encode(['status' => 'success', 'message' => 'Booking rejected successfully']);
    }

    public function getBookingState()
    {
        header('Content-Type: application/json');

        if (!isset($_SESSION['userID'])) {
            http_response_code(401);
            echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
            exit;
        }

        $json = file_get_contents('php://input');
        $data = json_decode($json, true);
        $bookingID = $data['bookingID'] ?? null;

        if (!$bookingID) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Booking ID is required']);
            exit;
        }

        $bookingState = $this->bookingModel->getStatusByBookingId($bookingID);
        // Set the booking state in the session
        $_SESSION['booking']['status'] = $bookingState;

        if ($bookingState) {
            echo json_encode(['status' => 'success', 'state' => $bookingState]);
        } else {
            http_response_code(404);
            echo json_encode(['status' => 'error', 'message' => 'Booking not found']);
        }
    }

    //worker rejection function for customer
    public function noResponse()
    {
        if (isset($_SESSION['booking'])) {
            $bookingID = $_SESSION['booking']['bookingID'];
            // set booking status to cancelled
            $cancelSuccess = $this->bookingModel->updateBookingStatus($bookingID, 'cancelled');
            if (!$cancelSuccess) {
                http_response_code(500);
                echo json_encode(['status' => 'error', 'message' => 'Failed to update booking status']);
                exit;
            }
            // Create notification for customer
            if (isset($_SESSION['booking']) && $_SESSION['booking']['status'] === 'pending') {
                $this->notificationModel->create(
                    $_SESSION['userID'],
                    'Booking Cancelled',
                    'Booking has been cancelled.',
                    'The worker did not respond in time. Please choose another worker or service.'
                );
                $workerID = $this->bookingModel->getWorkerIdByBookingId($bookingID);
                $this->notificationModel->create(
                    $this->userModel->getUserID($workerID, 'worker'),
                    'Booking Cancelled',
                    'You\'ve missed a job opportunity.',
                    'You did not respond in time to the booking request.'
                );
            }
            // Unset booking session data
            unset($_SESSION['booking']);
        }
        $this->view('noResponseApology');
    }

    //customer redirection after timeout
    public function acceptanceTimeout()
    {
        //get latest booking status
        $bookingStatus = $this->bookingModel->getStatusByBookingId($_SESSION['booking']['bookingID']);
        //check whether booking is accepted or not
        if ($bookingStatus === 'accepted') {
            // check if the payment timeout of 5 minutes has passed
            $currentTime = time();
            $bookingTime = strtotime($_SESSION['booking']['createdAt']);
            $timeDifference = $currentTime - $bookingTime;
            if ($timeDifference < 540) { // 9 minutes in seconds
                // Redirect to order summary page
                header("Location: " . ROOT . "/public/booking/orderSummary");
                exit;
            } else {
                // Order timed out, redirect to order timeout page
                header("Location: " . ROOT . "/public/booking/orderTimeout");
            }
        } else if ($bookingStatus === 'cancelled') {
            header("Location: " . ROOT . "/public/booking/workerRejected");
        }
        else if ($bookingStatus === 'pending') {
            // Redirect to no response page
            header("Location: " . ROOT . "/public/booking/noResponse");
            exit;
        }
    }

    public function workerRejected()
    {
        if (isset($_SESSION['booking'])) {
            unset($_SESSION['booking']);
        }

        $this->view('workerRejectionApology');
    }

    public function orderTimeout()
    {
        if (isset($_SESSION['booking'])) {
            $bookingID = $_SESSION['booking']['bookingID'];
            // set booking status to cancelled
            $cancelSuccess = $this->bookingModel->updateBookingStatus($bookingID, 'cancelled');
            if (!$cancelSuccess) {
                http_response_code(500);
                echo json_encode(['status' => 'error', 'message' => 'Failed to update booking status']);
                exit;
            }
            // Create notification for customer
            $this->notificationModel->create(
                $_SESSION['userID'],
                'Booking Cancelled',
                'Booking has been cancelled.',
                'Time allocated for this booking has expired. Please try again.'
            );
            $workerID = $this->bookingModel->getWorkerIdByBookingId($bookingID);
            $this->notificationModel->create(
                $this->userModel->getUserID($workerID, 'worker'),
                'Booking Cancelled',
                'Your booking has been cancelled.',
                'The customer did not make the payment in time.'
            );
            // Unset booking session data
            unset($_SESSION['booking']);
        }

        $this->view('orderTimeout');
    }

    //customer cancel booking function
    public function cancelBooking()
    {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['status' => 'error', 'message' => 'Invalid request method. POST required.']);
            exit;
        }

        $json = file_get_contents('php://input');
        $data = json_decode($json, true);
        $bookingID = $data['bookingID'] ?? null;

        if (!$bookingID) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Booking ID is required']);
            exit;
        }

        $updateSuccess = $this->bookingModel->updateBookingStatus($bookingID, 'cancelled');
        if (!$updateSuccess) {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Failed to update booking status']);
            exit;
        }

        // create notifications for both customer and worker
        $this->notificationModel->create(
            $_SESSION['userID'],
            'Booking Cancelled',
            'Booking has been cancelled.',
            'You have cancelled the booking. Please choose another worker or service.'
        );
        $workerID = $this->bookingModel->getWorkerIdByBookingId($bookingID);
        $this->notificationModel->create(
            $this->userModel->getUserID($workerID, 'worker'),
            'Booking Cancelled',
            'Your booking has been cancelled.',
            'Customer has cancelled the booking.'
        );
        // Unset booking session data
        unset($_SESSION['booking']);

        echo json_encode(['status' => 'success', 'message' => 'Booking cancelled successfully']);
    }

    public function orderSummary()
    {
        if (!isset($_SESSION['booking'])) {
            header('Location: ' . ROOT . '/public/home');
            exit();
        }

        //get worker details
        $workerData = $this->workerModel->getWorkerProfileInfo($_SESSION['booking']['workerID']);


        $this->view('orderSummary', ['worker' => $workerData]);
    }

    public function completeBooking()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['status' => 'error', 'message' => 'Invalid request method. POST required.']);
            exit;
        }
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);
        $bookingID = $data['bookingID'] ?? null;
        $verificationCode = $data['verificationCode'] ?? null;
        if (!$bookingID || !$verificationCode) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Booking ID and verification code are required']);
            exit;
        }
        $updateSuccess = $this->bookingModel->verifyAndCompleteBooking($bookingID, $verificationCode);
        if (!$updateSuccess) {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Failed to complete booking']);
            exit;
        }
        // create notifications for both customer and worker
        $this->notificationModel->create(
            $_SESSION['userID'],
            'Booking Completed',
            'Booking has been completed.',
            'Your booking with Booking ID: ' . $bookingID . ' has been completed successfully.'
        );
        $customerID = $this->bookingModel->getCustomerIdByBookingId($bookingID);
        $this->notificationModel->create(
            $this->userModel->getUserID($customerID, 'customer'),
            'Booking Completed',
            'Booking Completed.',
            'Your booking with Booking ID: ' . $bookingID . ' has been completed successfully.'
        );
        // send email to the worker
//        $workerID = $_SESSION['workerID'];
//        $workerEmail = $this->userModel->findWorkerByID($workerID)->email;
        // send success to fetch
        echo json_encode(['status' => 'success', 'message' => 'Booking completed successfully']);
        exit;
    }
}

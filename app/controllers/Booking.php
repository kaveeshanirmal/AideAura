<?php

class Booking extends Controller
{
    private $bookingModel;
    private $notificationModel;
    private $userModel;

    public function __construct()
    {
        $this->bookingModel = new BookingModel();
        $this->notificationModel = new NotificationModel();
        $this->userModel = new UserModel();
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
}
<?php

class Dashboard extends Controller
{
    private $workerModel;

    public function __construct()
    {
        $this->workerModel = new WorkerModel();
    }
    public function index()
    {
        // Fetch worker data to display on the dashboard
        $workerID = $_SESSION['workerID'];
        $availability = $this->workerModel->getWorkerAvailability($workerID);
        $profileInfo = $this->workerModel->getWorkerProfileInfo($workerID);
        $workingLocation = $this->workerModel->getWorkerLocation($workerID);
        $workerDetails = [
            'full_name' => $profileInfo['full_name'],
            'profileImage' => $profileInfo['profileImage'],
            'roles' => $profileInfo['roles'],
            'rating' => $profileInfo['rating'],
            'reviews' => $profileInfo['reviews'],
            'availability' => $availability,
            'location' => $workingLocation,
        ];

        // Get all bookings for this worker
        $allBookings = $this->workerModel->getAllBookings($workerID);

        // Filter bookings by status
        $pendingBookings = [];
        $acceptedBookings = [];
        $completedBookings = [];
        $cancelledBookings = [];
        $confirmedBookings = [];

        foreach ($allBookings as $booking) {
            $status = $booking['value']['booking']->status;

            switch ($status) {
                case 'pending':
                    $pendingBookings[] = $booking;
                    break;
                case 'accepted':
                    $acceptedBookings[] = $booking;
                    break;
                case 'confirmed':
                    $confirmedBookings[] = $booking;
                    break;
                case 'completed':
                    $completedBookings[] = $booking;
                    break;
                case 'cancelled':
                    $cancelledBookings[] = $booking;
                    break;
            }
        }

        // Pass all data to the view
        $this->view('workerDashboard', [
            'workerDetails' => $workerDetails,
            'pendingBookings' => $pendingBookings,
            'acceptedBookings' => $acceptedBookings,
            'confirmedBookings' => $confirmedBookings,
            'completedBookings' => $completedBookings,
            'cancelledBookings' => $cancelledBookings,
        ]);
    }

    public function availability()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $availability = $_POST['availability'] ?? null;
            if ($availability !== null && isset($_SESSION['workerID'])) {
                $this->workerModel->updateAvailability($_SESSION['workerID'], $availability);
                echo json_encode(['status' => 'success']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Invalid input']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
        }
    }

    public function getJobRequests()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $workerID = $_SESSION['workerID'];
            $newRequests = $this->workerModel->getNewBookingRequests($workerID);
            echo json_encode(['status' => 'success', 'data' => $newRequests]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
        }
        exit;
    }
}
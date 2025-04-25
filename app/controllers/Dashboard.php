<?php

class Dashboard extends Controller
{
    private $workerModel;
    private $paymentModel;

    public function __construct()
    {
        $this->workerModel = new WorkerModel();
        $this->paymentModel = new PaymentModel();
    }
    public function index()
    {
        // Fetch worker data to display on the dashboard
        $workerID = $_SESSION['workerID'];
        $availability = $this->workerModel->getWorkerAvailability($workerID);
        $profileInfo = $this->workerModel->getWorkerProfileInfo($workerID);

        $workerDetails = [
            'full_name' => $profileInfo['full_name'],
            'profileImage' => $profileInfo['profileImage'],
            'roles' => $profileInfo['roles'],
            'rating' => $profileInfo['rating'],
            'reviews' => $profileInfo['reviews'],
            'availability' => $availability,
            'location' => $profileInfo['workLocation']
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
        $incomeData = $this->calculateLastMonthEarnings();

        // Pass all data to the view
        $this->view('workerDashboard', [
            'workerDetails' => $workerDetails,
            'pendingBookings' => $pendingBookings,
            'acceptedBookings' => $acceptedBookings,
            'confirmedBookings' => $confirmedBookings,
            'completedBookings' => $completedBookings,
            'cancelledBookings' => $cancelledBookings,
            'incomeData' => $incomeData,
        ]);
    }

    public function availability()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $json = file_get_contents('php://input');
            $data = json_decode($json, true);

            $availability = $data['availability'] ?? null;
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

    public function updateLocation()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = json_decode(file_get_contents('php://input'), true);
            $workerID = $_SESSION['workerID'];

            if (isset($data['newLocation'])) {
                $success = $this->workerModel->updateWorkLocation($workerID, $data['newLocation']);
                echo json_encode($success ? ['status' => 'success'] : ['status' => 'error']);
                exit;
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Invalid input']);
                exit;
            }
        }
    }

    // In your Dashboard controller:

    public function calculateLastMonthEarnings()
    {
        $workerID = $_SESSION['workerID'];

        // Get current month's earnings
        $currentMonth = date('Y-m');
        $lastMonth = date('Y-m', strtotime('-1 month'));

        $currentMonthEarnings = $this->paymentModel->getEarningsByMonth($workerID, $currentMonth);
        $lastMonthEarnings = $this->paymentModel->getEarningsByMonth($workerID, $lastMonth);

        // Calculate percentage change
        $percentChange = 0;
        if ($lastMonthEarnings > 0) {
            $percentChange = (($currentMonthEarnings - $lastMonthEarnings) / $lastMonthEarnings) * 100;
        }

        return [
            'currentMonth' => $currentMonthEarnings,
            'lastMonth' => $lastMonthEarnings,
            'percentChange' => round($percentChange, 2)
        ];
    }

    public function getLatestBookings()
    {
        // Accept fetch requests
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $workerID = $_SESSION['workerID'];
            $latestBookings = $this->workerModel->getLatestBookings($workerID);
            echo json_encode(['status' => 'success', 'data' => $latestBookings]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
        }
        exit;
    }
}
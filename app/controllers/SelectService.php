<?php

class SelectService extends Controller
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel(); // Instantiate UserModel
    }
    public function index($a = '', $b = '', $c = '')
    {
        $this->view('serviceForms/serviceForms');

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $service = $_POST['service'] ?? '';
            $validServices = ['Cook', 'Maid', 'Nanny', 'Cook 24-hour Live in', 'All rounder'];
            if (in_array($service, $validServices)) {
                // Save to session or database
                $_SESSION['booking_info']['serviceType'] = $service;

                // Return success response
                echo json_encode(['status' => 'success']);
                exit;
            }
            // Return error response
            echo json_encode(['status' => 'error', 'message' => 'Invalid service']);
            exit;
        }
    }

    public function cook()
    {
        $this->view('serviceForms/cook');
    }

    public function cookingService()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_SESSION['booking_info']['gender'] = $_POST['gender'] ?? '';
            $_SESSION['booking_info']['num_people'] = $_POST['people'] ?? '';
            $_SESSION['booking_info']['num_meals'] = $_POST['meals'] ?? [];
            $_SESSION['booking_info']['diet'] = $_POST['diet'] ?? '';
            $_SESSION['booking_info']['addons'] = $_POST['addons'] ?? [];

            $total = $this->calculateTotal("cook");
            $_SESSION['booking_info']['total_cost'] = $total;

            // Only output JSON for AJAX requests
            if (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) ||
                $_SERVER['HTTP_X_REQUESTED_WITH'] !== 'XMLHttpRequest') {
                return;
            }
        }
    }
    public function maid()
    {
        $this->view('serviceForms/maid');
    }

    public function nanny()
    {
        $this->view('serviceForms/nanny');
    }

    public function cook24()
    {
        $this->view('serviceForms/cook24');
    }

    public function allRounder()
    {
        $this->view('serviceForms/allRounder');
    }

    private function calculateTotal($service)
    {
        $total_price = 0;
        $addon_price = 0;

        // These should be stored in db
        if ($service == 'cook') {
            $people_cost = [
                "1-2" => 500,
                "3-5" => 700,
                "5-7" => 850,
                "8-10" => 1000,
            ];

            $addon_cost = [
                "dishwashing" => 500,
                "desserts" => 200,
                "shopping" => 500,
            ];

            // Correct session variable access
            if (isset($_SESSION['booking_info']['num_people'])) {
                $total_price += $people_cost[$_SESSION['booking_info']['num_people']] ?? 0;
            }

            if (isset($_SESSION['booking_info']['num_meals'])) {
                $total_price = count($_SESSION['booking_info']['num_meals']) * $total_price;
            }

            if (isset($_SESSION['booking_info']['addons'])) {
                foreach ($_SESSION['booking_info']['addons'] as $addon) {
                    $addon_price += $addon_cost[$addon] ?? 0;
                    $total_price += $addon_price;
                }
            }

            if (isset($_SESSION['booking_info']['diet'])) {
                if ($_SESSION['booking_info']['diet'] == 'veg') {
                    $total_price -= 150;
                }
            }

            // Store the calculated total_price in session
            $_SESSION['booking_info']['total_cost'] = $total_price;
            $_SESSION['booking_info']['base_price'] = $total_price - $addon_price;
            $_SESSION['booking_info']['addon_price'] = $addon_price;

            // For AJAX requests, return JSON
            if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
                $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
                header("Content-Type: application/json");
                echo json_encode(["total" => $total_price]);
                exit;
            }

            // For non-AJAX requests, return the total_price
            return $total_price;
        }
        return $total_price;
    }

    public function bookingInfo()
    {
        $data = $this->userModel->findUserByUsername($_SESSION['username']);
        $this->view('serviceForms/bookingInfo', ['user' => $data]);
    }

    public function submitBookingInfo()
    {
        header('Content-Type: application/json');

        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('Invalid request method');
            }

            // Store form data in session
            $_SESSION['booking_info']['customer_name'] = $_POST['customer_name'] ?? '';
            $_SESSION['booking_info']['contact_phone'] = $_POST['contact_phone'] ?? '';
            $_SESSION['booking_info']['contact_email'] = $_POST['contact_email'] ?? '';
            $_SESSION['booking_info']['service_location'] = $_POST['service_location'] ?? '';
            $_SESSION['booking_info']['preferred_date'] = $_POST['preferred_date'] ?? '';
            $_SESSION['booking_info']['arrival_time'] = $_POST['arrival_time'] ?? '';
            $_SESSION['booking_info']['data_acknowledgment'] = isset($_POST['data_acknowledgment']);

            $sessionDebug = json_encode($_SESSION);

            echo json_encode([
                'success' => true,
                'message' => 'Booking information saved successfully',
                'session_debug' => $sessionDebug,
            ]);

        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }

        exit();
    }

    public function bookingSummary()
    {
        // Debug session data
        $sessionDebug = json_encode($_SESSION);

        $data = null;
        if(isset($_SESSION['username'])) {
            $data = $this->userModel->findUserByUsername($_SESSION['username']);
        }

        $this->view('bookingSummary', [
            'user' => $data,
            'booking_info' => $_SESSION['booking_info'],
            'session_debug' => $sessionDebug
        ]);
    }
}


<?php

class SelectService extends Controller
{
    private $userModel;
    private $pricingModel;

    public function __construct()
    {
        $this->userModel = new UserModel(); // Instantiate UserModel
        $this->pricingModel = new PricingModel(); // Instantiate PricingModel
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
        if (isset($_SESSION['booking_info'])) {
            unset($_SESSION['booking_info']);
            $_SESSION['booking_info']['serviceType'] = 'Cook';
        }
        $this->view('serviceForms/cook');
    }

    public function cookPricing()
    {
        $this->calculatePricing('Cook');
    }

    public function maid()
    {
        if (isset($_SESSION['booking_info'])) {
            unset($_SESSION['booking_info']);
            $_SESSION['booking_info']['serviceType'] = 'Maid';
        }
        $this->view('serviceForms/maid');
    }

    public function maidPricing()
    {
        $this->calculatePricing('Maid');
    }

    public function nanny()
    {
        if (isset($_SESSION['booking_info'])) {
            unset($_SESSION['booking_info']);
            $_SESSION['booking_info']['serviceType'] = 'Nanny';
        }
        $this->view('serviceForms/nanny');
    }

    public function nannyPricing(){
        $this->calculatePricing('Nanny');
    }

    public function cook24()
    {
        if (isset($_SESSION['booking_info'])) {
            unset($_SESSION['booking_info']);
            $_SESSION['booking_info']['serviceType'] = 'Cook 24-hour Live in';
        }
        $this->view('serviceForms/cook24');
    }

//    public function cook24Pricing(){}

    public function allRounder()
    {
        if (isset($_SESSION['booking_info'])) {
            unset($_SESSION['booking_info']);
            $_SESSION['booking_info']['serviceType'] = 'All rounder';
        }
        $this->view('serviceForms/allRounder');
    }

//    public function allRounderPricing(){}

    public function calculatePricing($service)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Get the form fields specific to this service
            $formFields = $this->getServiceFormFields($service);

            // Update session with form data
            foreach ($formFields as $key => $value) {
                $_SESSION['booking_info'][$key] = $value;
            }

            // Calculate total cost
            $prices = $this->pricingModel->calculateServiceTotal($service, $formFields);

            // Store price details in session
            $_SESSION['booking_info']['total_cost'] = $prices['total_price'];
            $_SESSION['booking_info']['base_price'] = $prices['base_price'];
            $_SESSION['booking_info']['addon_price'] = $prices['addon_price'];

            // Return JSON response for AJAX requests
            if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
                $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
                header('Content-Type: application/json');
                echo json_encode([
                    'total' => $prices['total_price'],
                    'formFields' => $formFields,
                ]);
                exit;
            }
        }
    }

    private function getServiceFormFields($service): array
    {
        $fieldsMap = [
            'Cook' => [
                'gender' => $_POST['gender'] ?? '',
                'num_people' => $_POST['people'] ?? '',
                'num_meals' => $_POST['meals'] ?? [],
                'diet' => $_POST['diet'] ?? '',
                'addons' => $_POST['addons'] ?? [],
            ],
            'Maid' => [
                'gender' => $_POST['gender'] ?? '',
                'property-size' => $_POST['property-size'] ?? '',
                'services' => $_POST['services'] ?? [],
                'intensity' => $_POST['intensity'] ?? '',
                'addons' => $_POST['addons'] ?? [],
            ],
            'Nanny' => [
                'gender' => $_POST['gender'] ?? '',
                'children-count' => $_POST['children-count'] ?? '',
                'children-ages' => $_POST['children-ages'] ?? [],
                'service-duration' => $_POST['service-duration'] ?? '',
                'care-level' => $_POST['care-level'] ?? '',
                'addons' => $_POST['addons'] ?? [],
            ]
        ];

        return $fieldsMap[$service] ?? [];
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

        $userData = null;
        if(isset($_SESSION['username'])) {
            $userData = $this->userModel->findUserByUsername($_SESSION['username']);
        }

        if (!isset($_SESSION['booking_info']['serviceType'])) {
            header("Location: " . ROOT . "/public/SelectService");
            exit;
        }
        $bookingData = $this->pricingModel->getServicePricing($_SESSION['booking_info']['serviceType']);

        $this->view('bookingSummary', [
            'user' => $userData,
            'booking_info' => $bookingData,
            'session_debug' => $sessionDebug
        ]);
    }
}


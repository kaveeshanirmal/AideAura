<?php

class AdminEmployeeAdd extends Controller
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel(); // Instantiate UserModel
    }

    public function index($a = '', $b = '', $c = '')
    {
        $data = [
            'pageTitle' => 'Add User',
            'action' => 'add',
        ];
        $this->view('admin/adminEmployeeAdd', $data); // Load the appropriate view
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $_POST = filter_input_array(INPUT_POST);

            // Determine the role (e.g., 'worker' or 'customer') based on the form input
            $role = trim($_POST['role']);
            
            // Prepare data based on the role
            $data = [
                'firstName' => trim($_POST['firstName']),
                'lastName' => trim($_POST['lastName']),
                'username' => trim($_POST['username']),
                'email' => trim($_POST['email']),
                'phone' => trim($_POST['phone']),
                'password' => trim($_POST['password']),
                'address' => trim($_POST['address']),
            ];

            // For workers, include the services they offer
            if ($role === 'worker' && isset($_POST['servicesOffer'])) {
                $data['servicesOffer'] = $_POST['servicesOffer']; // Array of job roles
            }

            $result = $this->userModel->register($data, $role); // Register the user

            if ($result) {
                echo json_encode(['status' => 'success', 'message' => 'User added successfully!']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to add user. Please try again.']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
        }
        exit;
    }
}
?>

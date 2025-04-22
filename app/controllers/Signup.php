<?php

class Signup extends Controller
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel(); // Instantiate UserModel
    }
    public function index($a = '', $b = '', $c = '')
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sanitize and collect user inputs
            $role = $_POST['role']; // Role can be 'customer' or 'worker'

            $data = [
                'firstName' => trim($_POST['firstName']),
                'lastName' => trim($_POST['lastName']),
                'username' => trim($_POST['username']),
                'address' => trim($_POST['address']),
                'phone' => trim($_POST['phone']),
                'email' => trim($_POST['email']),
                'password' => $_POST['password'], // Password will be hashed in UserModel
            ];
            
            if ($role != 'customer') {
                $data['servicesOffer'] = isset($_POST['serviceType']) ? explode(',', $_POST['serviceType']) : [];
            }
            
            

            // Register the user
            if ($this->userModel->register($data, $role)) {
                header('Location: ' . ROOT . '/public/login'); // Redirect to login page after successful registration
                exit();
            } else {
                // implement error handling here
                echo "Registration failed!";
            }
        }

        // Load the registration view
        $this->view('signup');
    }
}

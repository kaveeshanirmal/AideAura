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
            $data = [
                'name' => trim($_POST['name']),
                'username' => trim($_POST['username']),
                'address' => trim($_POST['address']),
                'phoneNo' => trim($_POST['phone']),
                'email' => trim($_POST['email']),
                'passwordHash' => $_POST['password'], // Password will be hashed in UserModel
            ];
            $role = $_POST['role']; // Role can be 'customer' or 'worker'

            // Register the user
            if ($this->userModel->register($data, $role)) {
                header('Location: ' . ROOT . '/public/login'); // Redirect to login page after successful registration
                exit();
            } else {
                // You can implement error handling here
                echo "Registration failed!";
            }
        }

        // Load the registration view
        $this->view('signup');
    }

    public function edit($a = '', $b = '', $c = '')
    {
        $this->view('signup');
    }
}

<?php

class Login extends Controller
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel(); // Instantiate UserModel
    }
    public function index($a = '', $b = '', $c = '')
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Collect and sanitize user input
            $username = trim($_POST['username']);
            $passwordHash = $_POST['password'];
            $role = $_POST['role'];

            // Find the user by username
            $user = $this->userModel->findUserByUsername($username, $role);
            // Check if the user exists and the password is correct
            if ($user && password_verify($passwordHash, $user->passwordHash)) {
                // Set session variables
                $_SESSION['loggedIn'] = true;
                $_SESSION['user_id'] = $user->id;
                $_SESSION['username'] = $user->username;
                $_SESSION['role'] = $role;

                // Redirect based on user role
                if ($role === 'customer') {
                    header('Location: ' . ROOT . '/public/home');
                } elseif ($role === 'worker') {
                    header('Location: ' . ROOT . '/public/home');
                } else {
                    header('Location: ' . ROOT . '/public/home');
                }
            } else {
                // Handle invalid login attempt
                echo "Invalid username or password!";
            }
        }

        // Load the login view
        $this->view('login');
    }
    public function logout()
    {
        // Destroy session and redirect to home
        session_destroy();
        header('Location: ' . ROOT . '/public/home');
    }

    
}

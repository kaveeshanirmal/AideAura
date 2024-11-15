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
            $password = $_POST['password'];

            // Find the user by username
            $user = $this->userModel->findUserByUsername($username);
            $role = $user->role;
            // Check if the user exists and the password is correct
            if ($user && password_verify($password, $user->password)) {
                // Set session variables
                $_SESSION['loggedIn'] = true;
                $_SESSION['userID'] = $user->userID;
                $_SESSION['username'] = $user->username;
                $_SESSION['role'] = $role;

                // Redirect based on user role
                if ($role === 'customer') {
                    header('Location: ' . ROOT . '/public/home');
                } elseif ($role === 'worker') {
                    header('Location: ' . ROOT . '/public/home');
                } else {
                    // Admin and other dashboards
                    // header('Location: ' . ROOT . '/public/home');
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

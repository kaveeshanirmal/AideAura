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
        $errorMessage = '';

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Collect and sanitize user input
            $username = trim($_POST['username']);
            $password = $_POST['password'];

            // Find the user by username
            $user = $this->userModel->findUserByUsername($username);
            
            // Check if the user exists and the password is correct
            if ($user && password_verify($password, $user->password)) {
                $role = $user->role;
                // Set session variables
                $_SESSION['loggedIn'] = true;
                $_SESSION['userID'] = $user->userID;
                $_SESSION['role'] = $role;
                $_SESSION['workerID'] = isset($user->workerID) ? $user->workerID : null;
                $_SESSION['customerID'] = isset($user->customerID) ? $user->customerID : null;
                $_SESSION['username'] = $user->username;
                $_SESSION['isVerified'] = isset($user->isVerified) ? $user->isVerified : null;

                // Redirect based on user role
                // 'admin','hrManager','opManager','financeManager','customer','worker'

                if ($role === 'customer') {
                    header('Location: ' . ROOT . '/public/home');
                } elseif ($role === 'worker') {
                    header('Location: ' . ROOT . '/public/home');
                } elseif ($role === 'admin') {
                    // Admin and other dashboards
                    header('Location: ' . ROOT . '/public/Admin/adminReports');
                } elseif ($role === 'hrManager') {
                    header('Location: ' . ROOT . '/public/HRworkerProfileManagement');
                } elseif ($role === 'opManager') {
                    header('Location: ' . ROOT . '/public/OPMcomplaintManagement');
                } elseif ($role === 'financeManager') {
                    header('Location: ' . ROOT . '/public/AccountantReports');
                }
            } else {
                // Handle invalid login attempt
                $errorMessage = 'Invalid username or password';
            }
        }

        if ($errorMessage) {
            $data = ['error' => $errorMessage];
            $this->view('login', $data);
        } else {
            $this->view('login');
        }
    }
    public function logout()
    {
        // Destroy session and redirect to home
        session_destroy();
        header('Location: ' . ROOT . '/public/home');
    }

    
}

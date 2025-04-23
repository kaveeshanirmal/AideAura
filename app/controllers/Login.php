<?php

class Login extends Controller
{
    private $userModel;
    private $workerStatsModel;
    private $bookingModel;
    
    public function __construct()
    {
        $this->userModel = new UserModel(); // Instantiate UserModel
        $this->workerStatsModel = new WorkerStats(); // Instantiate WorkerStats
        $this->bookingModel = new BookingModel(); // Instantiate BookingModel
    }
    public function index($a = '', $b = '', $c = '')
    {
        $errorMessage = '';
        //echo "Inside login controller";
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
                session_start(); // Ensure the session is started
                
                $_SESSION['loggedIn'] = true;
                $_SESSION['userID'] = $user->userID;
                $_SESSION['role'] = $role;
                $_SESSION['workerID'] = isset($user->workerID) ? $user->workerID : null;
                $_SESSION['customerID'] = isset($user->customerID) ? $user->customerID : null;
                $_SESSION['username'] = $user->username;
                $_SESSION['isVerified'] = isset($user->isVerified) ? $user->isVerified : null;
                
                //echo "Role: " . $_SESSION['role']; //debugging
                //echo "User role from DB: " . $user->role;

                // Redirect based on user role
                // 'admin','hrManager','opManager','financeManager','customer','worker'

                if ($role === 'customer') {

                    // If customer had a unconfirmed booking and it is createdAt < 10 minutes ago populate session with booking info
                    if ($this->bookingModel->hasUnconfirmedBookings($_SESSION['customerID'])) {
                        $unconfirmedBookings = $this->bookingModel->getUnconfirmedBookings($_SESSION['customerID']);
                        if (!empty($unconfirmedBookings)) {
                            $unconfirmedBooking = is_array($unconfirmedBookings) ? $unconfirmedBookings[0] : $unconfirmedBookings;

                            $createdAt = new DateTime($unconfirmedBooking->createdAt);
                            $currentDateTime = new DateTime();
                            $interval = $currentDateTime->diff($createdAt);
                            $minutes = $interval->i + ($interval->h * 60) + ($interval->d * 24 * 60);
                            // Check if the booking is older than 10 minutes
                            if ($minutes < 10) {
                                $_SESSION['booking'] = [
                                    'bookingID' => $unconfirmedBooking->bookingID,
                                    'workerID' => $unconfirmedBooking->workerID,
                                    'customerID' => $unconfirmedBooking->customerID,
                                    'serviceType' => $unconfirmedBooking->serviceType,
                                    'bookingDate' => $unconfirmedBooking->bookingDate,
                                    'location' => $unconfirmedBooking->location,
                                    'startTime' => $unconfirmedBooking->startTime,
                                    'totalCost' => $unconfirmedBooking->totalCost,
                                    'status' => $unconfirmedBooking->status,
                                    'createdAt' => $unconfirmedBooking->createdAt,
                                ];
                            } else {
                                // cancel the unconfirmed booking if older than 10 minutes
                                $this->bookingModel->updateBookingStatus($unconfirmedBooking->bookingID, 'cancelled');
                            }
                        }
                    }
                    header('Location: ' . ROOT . '/public/home');
                } elseif ($role === 'worker') {
                    // Update last login time
                    $this->workerStatsModel->updateLastLogin($user->workerID);
                    // Update the availability status
                    $this->userModel->updateAvailability($user->workerID, 'online');
                    header('Location: ' . ROOT . '/public/home');
                } elseif ($role === 'admin') {
                    // Admin and other dashboards
                    header('Location: ' . ROOT . '/public/Admin');
                } elseif ($role === 'hrManager') {
                    header('Location: ' . ROOT . '/public/HrManager');
                } elseif ($role === 'opManager') {
                    header('Location: ' . ROOT . '/public/OpManager');
                } elseif ($role === 'financeManager') {
                    header('Location: ' . ROOT . '/public/FinanceManager');
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
        // For workers, update their availability status
        if (isset($_SESSION['workerID'])) {
            $this->userModel->updateAvailability($_SESSION['workerID'], 'offline');
        }
        session_destroy();
        header('Location: ' . ROOT . '/public/home');
    }

    
}

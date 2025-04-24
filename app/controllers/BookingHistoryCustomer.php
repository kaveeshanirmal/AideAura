<?php 

class BookingHistoryCustomer extends Controller
{
    private $bookingModel;

    public function __construct()
    {
        $this->bookingModel = $this->loadModel('BookingHistoryModel');
    }

    public function index()
    {    
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
    
        if (!isLoggedIn()) {
            die('Not logged in!');
        }
    
        echo '<pre>';
        print_r($_SESSION); // Shows all session data
        echo '</pre>';
    
        $customerID = $_SESSION['userID']; // <- Change this to 'user_id' if session shows that instead
        echo "Customer ID: $customerID<br>";
    
        $bookings = $this->bookingModel->getBookingsByCustomer($customerID);
    
        echo '<pre>';
        var_dump($bookings); // Check what data we got back
        echo '</pre>';
        die;

    
    }

    public function cancel()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isLoggedIn()) {
                redirect('login');
            }

            $bookingID = $_POST['bookingID'] ?? null;
            $customerID = $_SESSION['userID'];

            // Validate booking belongs to the logged-in customer
            $booking = $this->bookingModel->getBookingsByCustomer($customerID);

            if ($booking && $booking['customerID'] == $customerID && strtolower($booking['status']) !== 'cancelled') {
                // Perform cancellation
                $this->bookingModel->cancelBooking($bookingID);
                flash('booking_message', 'Booking cancelled successfully.');
            } else {
                flash('booking_message', 'Invalid cancellation request.');
            }
        }

        redirect('bookingHistoryCustomer');
    }


} 

?>

<?php 

class BookingHistoryCustomer extends Controller
{
    private $bookingModel;

    public function __construct()
    {
        $this->bookingModel = $this->model('BookingHistoryModel');
    }

    // Show all bookings for the currently logged-in customer
    public function index()
    {
        $customerId = $_SESSION['customer_id'] ?? null;
        if (!$customerId) redirect('login');

        $bookings = $this->bookingModel->getBookingsByCustomer($customerId);
        $this->view('bookingHistory', ['bookings' => $bookings]);
    }

    // Show detailed view of a specific booking
    public function show($id)
    {
        $booking = $this->bookingModel->getBookingById($id);
        $this->view('booking/show', ['booking' => $booking]);
    }
}

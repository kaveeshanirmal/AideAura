<?php

class BookingHistoryModel
{
    use Database;

    // Get all bookings for a customer
    public function getBookingsByCustomer($customerId)
    {
        $query = "SELECT * FROM bookings WHERE customerID = :customerID ORDER BY bookingDate DESC, startTime DESC";
        return $this->get_all($query, ['customerID' => $customerId]);
    }

    // Get a single booking by its ID
    public function getBookingById($bookingID)
    {
        $query = "SELECT * FROM bookings WHERE bookingID = :bookingID";
        return $this->get_row($query, ['bookingID' => $bookingID]);
    }
}

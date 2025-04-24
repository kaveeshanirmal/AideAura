<?php

class BookingHistoryModel
{
    use Database;

    // Get all bookings for a customer
    public function getBookingsByCustomer($customerID)
    {
        $query = "SELECT 
                    b.bookingID,
                    b.serviceType,
                    b.bookingDate,
                    b.startTime,
                    b.status,
                    b.totalCost,
                    b.createdAt,
                    w.full_name AS workerName
                FROM bookings b
                JOIN verified_workers w ON b.workerID = w.workerID
                WHERE b.customerID = :customerID
                ORDER BY b.bookingDate DESC, b.startTime DESC";

        return $this->get_all($query, ['customerID' => $customerID]);
    }


    // Get a single booking by its ID
    public function getBookingById($bookingID)
    {
        $query = "SELECT * FROM bookings WHERE bookingID = :bookingID";
        return $this->get_row($query, ['bookingID' => $bookingID]);
    } 

    public function cancelBooking($bookingID)
    {
        $query = "UPDATE bookings SET status = 'cancelled' WHERE bookingID = :bookingID";
        return $this->query($query, ['bookingID' => $bookingID]);
    }

}

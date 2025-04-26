<?php

class BookingModel
{
    use Model;

    public function createBooking($workerID, $customerID, $serviceType, $bookingDate, $serviceLocation, $arrivalTime, $totalCost, $details)
    {
        $data = [
            'customerID' => $customerID,
            'workerID' => $workerID,
            'serviceType' => $serviceType,
            'bookingDate' => $bookingDate,
            'startTime' => $arrivalTime,
            'location' => $serviceLocation,
            'totalCost' => $totalCost,
        ];

        // Insert booking into the database
        $this->setTable('bookings');
        $bookingID = $this->insertAndGetId($data);

        //Iteratively insert all details regarding the booking into bookingDetails
        foreach ($details as $detail) {
            $excludedKeys = ['gender', 'data_acknowledgment'];
            if (in_array($detail->key, $excludedKeys)) {
                continue; // Skip excluded keys
            }

            $detailData = [
                'bookingID' => $bookingID,
                'detailType' => $detail->key,
                'detailValue' => is_array($detail->value) ? json_encode($detail->value) : $detail->value,
            ];
            $this->setTable('booking_details');
            $this->insert($detailData);
        }

        // Update the worker's availability status
//        $this->setTable('worker');
//        $this->update(['availability_status' => 'busy'], ['workerID' => $workerID]);

        return $bookingID;
    }

    public function updateBookingStatus($bookingID, $status)
    {
        $this->setTable('bookings');
        return $this->update($bookingID, ['status' => $status], 'bookingID');
    }

    public function getBookingDetails($bookingID)
    {
        $this->setTable('bookings');
        $booking = $this->find($bookingID, 'bookingID');
        if ($booking) {
            $this->setTable('booking_details');
            $details = $this->get_all("SELECT * FROM booking_details WHERE bookingID = :bookingID", ['bookingID' => $bookingID]);
            return [
                'booking' => $booking,
                'details' => $details
            ];
        }
        return null;
    }
    //Maneth's

    public function getBookingDetailsByCustomer($customerID)
    {
        $this->setTable('bookings');
        $query = "SELECT b.*,vw.profileImage AS workerImage, vw.full_name AS workerName
            FROM bookings b
            JOIN verified_workers vw ON b.workerID = vw.workerID
            WHERE b.customerID = :customerID
            ORDER BY b.bookingDate DESC";

        return $this->get_all($query, ['customerID' => $customerID]);
    }

    public function cancelBooking($bookingID)
    {
        $this->setTable('bookings');
        $cancelledBooking = $this->find($bookingID, 'bookingID');

        if (!$cancelledBooking) return false;

        $this->update($bookingID, ['status' => 'cancelled'], 'bookingID');

        $this->setTable('cancelled_bookings');
        $data = (array) $cancelledBooking;
        $data['cancelledAt'] = date('Y-m-d H:i:s');
        $this->insertAndGetId($data);

        // Soft-delete from bookings (or you can just delete if no "is_deleted" flag is used)
        // $this->setTable('bookings');
        // return $this->delete($bookingID, 'bookingID');
    }

    
    public function getCustomerIdByBookingId($bookingID)
    {
        $this->setTable('bookings');
        $customer = $this->find($bookingID, 'bookingID');
        return $customer ? $customer->customerID : null;
    }

    public function getWorkerIdByBookingId($bookingID)
    {
        $this->setTable('bookings');
        $worker = $this->find($bookingID, 'bookingID');
        return $worker ? $worker->workerID : null;
    }

    public function getStatusByBookingId($bookingID)
    {
        $this->setTable('bookings');
        $status = $this->find($bookingID, 'bookingID');
        return $status ? $status->status : null;
    }

    public function hasUnconfirmedBookings($customerID) {
        $this->setTable('bookings');
        $query = "SELECT * FROM bookings 
              WHERE customerID = :customerID 
                AND (status = 'pending' OR status = 'accepted')";
        $result = $this->get_all($query, ['customerID' => $customerID]);
        return ($result !== false) && !empty($result);
    }

    public function getUnconfirmedBookings($customerID)
    {
        $this->setTable('bookings');
        $query = "SELECT * FROM bookings WHERE customerID = :customerID AND status = 'pending' OR status = 'accepted'";
        return $this->get_all($query, ['customerID' => $customerID]);
    }

    public function getBasicBookingData($bookingID)
    {
        $this->setTable('bookings');
        return $this->find($bookingID, 'bookingID');
    }

    public function deleteUnconfirmedBooking($bookingID)
    {
        $this->setTable('bookings');
        return $this->delete($bookingID, 'bookingID');
    }
}
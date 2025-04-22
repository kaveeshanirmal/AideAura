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
        $this->update($bookingID, ['status' => $status], 'bookingID');
    }
}
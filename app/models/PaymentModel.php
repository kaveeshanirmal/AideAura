<?php

class PaymentModel
{
    use Model;

    public function createPayment($paymentData)
    {
        // Insert payment data into the database
        $this->setTable('payments');
        return $this->insertAndGetId($paymentData);
    }

    public function updatePaymentStatus($paymentID, $status, $responseData = null)
    {
        // Update payment status and optionally response data
        $this->setTable('payments');
        $data = [
            'status' => $status,
            'responseData' => $responseData,
        ];

        return $this->update($paymentID, $data, 'paymentID');
    }

    public function findPaymentByTransactionID($transactionID)
    {
        // Find payment by transaction ID
        $this->setTable('payments');
        return $this->find($transactionID, 'transactionID');
    }

    public function findPaymentsByBookingID($bookingID)
    {
        // Find payments by booking ID
        $this->setTable('payments');
        return $this->get_all("SELECT * FROM payments WHERE bookingID = :bookingID", ['bookingID' => $bookingID]);
    }

    public function findPaymentByID($paymentID)
    {
        // Find payment by payment ID
        $this->setTable('payments');
        return $this->find($paymentID, 'paymentID');
    }

    public function logPaymentTransaction($logData)
    {
        // Log payment transaction
        $this->setTable('payment_logs');
        return $this->insert($logData);
    }

    public function getPaymentLogs($paymentID)
    {
        // Get payment logs for a specific payment
        $this->setTable('payment_logs');
        return $this->get_all("SELECT * FROM payment_logs WHERE paymentID = :paymentID", ['paymentID' => $paymentID]);
    }

    public function getPaymentWithBookingDetails($paymentID)
    {
        // Get payment details along with booking information
        $this->setTable('payments');
        $payment = $this->find($paymentID, 'paymentID');

        if ($payment) {
            $this->setTable('bookings');
            $booking = $this->find($payment->bookingID, 'bookingID');

            return [
                'payment' => $payment,
                'booking' => $booking,
            ];
        }
        return null;
    }

    public function getEarningsByMonth($workerID, $monthYear)
    {
        // Get completed bookingIDs for the worker
        $this->setTable('bookings');
        $completedBookings = $this->get_all("SELECT bookingID FROM bookings WHERE workerID = :workerID AND status = 'confirmed' AND DATE_FORMAT(createdAt, '%Y-%m') = :monthYear", [
            'workerID' => $workerID,
            'monthYear' => $monthYear,
        ]);
        error_log("Completed bookings for worker $workerID in month $monthYear: " . json_encode($completedBookings));
        $bookingIDs = array_column($completedBookings, 'bookingID');
        error_log("Completed booking IDs for worker $workerID in month $monthYear: " . json_encode($bookingIDs));
        if (empty($bookingIDs)) {
            return 0; // No completed bookings for this month
        }
        // Get payment details for the completed bookings
        $this->setTable('payments');
        $payments = $this->get_all(
            "SELECT SUM(amount) AS totalEarnings FROM payments WHERE bookingID IN (" . implode(',', array_fill(0, count($bookingIDs), '?')) . ")",
            $bookingIDs
        );
        error_log("Earnings for worker $workerID in month $monthYear: " . json_encode($payments));
        return $payments ? $payments[0]->totalEarnings : 0;
    }

}
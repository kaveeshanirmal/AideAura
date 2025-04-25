<?php

class PaymentModel {

    use Model;

    // fuction to get all data of jobroles table
    public function getAllPayments(){
        $this->setTable('payments');
        return $this->all();
    }

        // fuction to get all data of jobroles table
        public function getAllPaymentsWithBookingDetails(){
            $this->setTable('payments');
            $sql = "SELECT 
    p.paymentID,
    p.transactionID,
    p.amount,
    p.currency,
    p.paymentMethod,
    p.paymentStatus,
    p.merchantReference,
    p.paymentDate,
    p.lastUpdated,
    p.responseData,
    b.bookingID,
    c.customerID,
    CONCAT(cu.firstName, ' ', cu.lastName) AS customerName,
    w.workerID,
    CONCAT(wu.firstName, ' ', wu.lastName) AS workerName
FROM payments p
JOIN bookings b ON p.bookingID = b.bookingID
JOIN customer c ON b.customerID = c.customerID
JOIN users cu ON c.userID = cu.userID
JOIN worker w ON b.workerID = w.workerID
JOIN users wu ON w.userID = wu.userID;
";
            return $this->get_all($sql,[]);
        }


    public function softDeleteRole($paymentID){
       $this->setTable('payments');
       
       // before delete check if that payment exists
       $payment = $this->find($paymentID, 'paymentID');
          if(!$payment){
            return false;
          }

          // perform soft delete
          return $this->softDelete($paymentID, 'paymentID' , 'isDelete');
    }
}
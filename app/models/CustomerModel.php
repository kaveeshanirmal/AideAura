<?php
class CustomerModel 
{
    use Model;

    public function __construct()
    {
        $this->setTable('customer');
    }

    public function getAllCustomerDetails(){
        $this->setTable('customer');

        $sql = "SELECT 
        c.*, 
        u.*
    FROM  customer c
  JOIN users u ON c.userID =  u.userID;";
return $this->get_all($sql, []);
    }

    public function searchCustomer($customerID){
        $this->setTable('customer');
        $sql = "SELECT 
        c.*, 
        u.*
    FROM  customer c
  JOIN users u ON c.userID =  u.userID WHERE c.customerID = :customerID;";
return $this->get_all($sql, ['customerID' => $customerID]);

}

}
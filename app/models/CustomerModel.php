<?php
class CustomerModel 
{
    use Model;

    public function __construct()
    {
        $this->setTable('customer');
    }

    public function getAllCustomerDetails(){
        return $this->all();
    }
}
<?php

class CustomerComplaintModel
{
    use Model; // Use the Model trait

    public function __construct()
    {
        $this->setTable('customercomplaints');
    }

    // add a new complaint
    public function addComplaint($data)
    {
        return $this->insertAndGetId($data);
    }

}

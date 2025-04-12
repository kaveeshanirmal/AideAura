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

    // get all complaints
    public function getAllComplaints()
    {
        return $this->all();
    }

    public function getComplaintById($id)
    {
        return $this->find($id, 'complaintID');
    }

    public function submitComplaintUpdates($data)
    {
        $this->setTable('customercomplaints_updates');
        return $this->insertAndGetId($data);
    }

    public function updateComplaint($id, $data)
    {
        $this->setTable('customercomplaints');
        return $this->update($id, $data, 'complaintID');
    }

    public function deleteComplaint($id)
    {
        return $this->delete($id, 'complaintID');
    }

    public function getComplaintsByUser($id)
    {
        return $this->get($id, 'customerID');
    }

    public function getSolutionByComplaintId($id)
    {
        $this->setTable('customercomplaints_updates');
        return $this->find($id, 'complaintID');
    }

}

<?php

class VerificationRequestModel
{
    use Model; // Use the Model trait to leverage shared functionality

    // Constructor to set the table name
    public function __construct()
    {
        $this->setTable('verification_requests'); // Assume your table name is `verification_requests`
    }


    public function createRequest($data)
    {
        return $this->insertAndGetId($data);
    }

    public function getPendingRequests()
    {
        $query = "SELECT * FROM {$this->getTable()} WHERE status = :status";
        return $this->get_all($query, ['status' => 'pending']);
    }

    public function findRequestById($id)
    {
        return $this->find($id, 'requestID');
    }

    public function updateRequestStatus($id, $status)
    {
        return $this->update($id, ['status' => $status], 'requestID');
    }

    public function deleteRequest($id)
    {
        return $this->delete($id, 'requestID');
    }
}

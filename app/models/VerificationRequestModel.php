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

    public function findRequestByWorkerId($id)
    {
        return $this->find($id, 'workerID');
    }

    public function findRequestById($id)
    {
        return $this->find($id, 'requestID');
    }

    public function updateRequest($data, $id)
    {
        return $this->update($id, $data, 'requestID');
    }

    public function deleteRequest($id)
    {
        return $this->delete($id, 'requestID');
    }
}

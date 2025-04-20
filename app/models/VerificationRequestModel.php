<?php

class VerificationRequestModel
{
    use Model; // Use the Model trait to leverage shared functionality

    // Constructor to set the table name
    public function __construct()
    {
        $this->setTable('verification_requests'); 
    }


    protected $lastError = null;

    // Method to get the last error
    public function getLastError() {
        return $this->lastError;
    }
    
    public function createRequest($data)
    {
        return $this->insertAndGetId($data);
    }

    public function getPendingOrRejectedRequests()
    {
        $query = "SELECT * FROM {$this->getTable()} WHERE status IN (:pending, :rejected)";
        return $this->get_all($query, ['pending' => 'pending', 'rejected' => 'rejected']);
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
    try {
        // Log the update operation
        error_log("Updating request: ID=$id, Data=" . json_encode($data));
        
        // Add debugging for the exact data format
        error_log("Status value: " . $data['Status']);
        
        $result = $this->update($id, $data, 'requestID');
        
        // Log the result
        error_log("Update result: " . ($result ? "Success" : "Failed"));
        
        return $result;
    } catch (Exception $e) {
        $this->lastError = $e->getMessage();
        error_log("Exception in updateRequest: " . $e->getMessage());
        return false;
    } // This closing brace was missing
}

    public function deleteRequest($id)
    {
        return $this->delete($id, 'requestID');
    }
}

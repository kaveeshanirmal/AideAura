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
        $query = "SELECT * FROM {$this->getTable()} WHERE status IN (:pending, :rejected)" . " ORDER BY status";
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
            
            $dataforupdate = [
                'Status' => $data['Status']
            ];
    
            // Fix here: use userID directly
            $userID = $data['userID'];
    
            $result = $this->update($id, $dataforupdate, 'requestID');
            
            error_log("Update result: " . ($result ? "Success" : "Failed"));
            
            if ($result) {
                $notificationModel = new NotificationModel();
                $notificationModel->create($userID, 'worker', 'Verification Request Status Updated', 'Your verification request is ' . $data['Status'] . '.');
                return $result;
            }
    

        } catch (Exception $e) {
            $this->lastError = $e->getMessage();
            error_log("Exception in updateRequest: " . $e->getMessage());
            return false;
        }
    }
    

    public function deleteRequest($id)
    {
        return $this->delete($id, 'requestID');
    }
}

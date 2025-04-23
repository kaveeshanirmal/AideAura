<?php
class WorkerComplaintModel
{
    use Model; // Use the Model trait
    
    public function __construct()
    {
        $this->setTable('workercomplaints');
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
        $this->setTable('workercomplaints_updates');
        return $this->insertAndGetId($data);
    }
    
    public function updateComplaint($id, $data)
    {
        $this->setTable('workercomplaints');
        return $this->update($id, $data, 'complaintID');
    }
    
    public function deleteComplaint($id)
    {
        return $this->delete($id, 'complaintID');
    }
    
    public function getComplaintsByWorker($id)
    {
        return $this->get($id, 'workerID');
    }
    
    public function getSolutionByComplaintId($id)
    {
        $this->setTable('workercomplaints_updates');
        return $this->find($id, 'complaintID');
    }
    
    public function filter($filters)
    {
        $this->setTable('workercomplaints');
        $query = "SELECT * FROM workercomplaints WHERE 1";
        $params = [];
        foreach ($filters as $column => $value) {
            $query .= " AND {$column} = :{$column}";
            $params[$column] = $value;
        }
        return $this->get_all($query, $params);
    }
    
    /**
     * Get complaints by specific issue type
     *
     * @param string $issueType The issue type to filter by
     * @return array Complaints matching the issue type
     */
    public function getComplaintsByType($issueType)
    {
        // Ensure we're using the complaints table
        $this->setTable('workercomplaints');
        // Create filter array with issue_type
        $filters = ['issue_type' => $issueType];
        // Use the existing filter method
        return $this->filter($filters);
    }
    
    /**
     * Get complaint updates history
     * 
     * @param int $complaintID The complaint ID
     * @return array Updates related to the complaint
     */
    public function getComplaintUpdates($complaintID)
    {
        $this->setTable('workercomplaints_updates');
        return $this->get($complaintID, 'complaintID');
    }
    
    /**
     * Get complaints by status
     *
     * @param string $status The status to filter by
     * @return array Complaints matching the status
     */
    public function getComplaintsByStatus($status)
    {
        $this->setTable('workercomplaints');
        $filters = ['status' => $status];
        return $this->filter($filters);
    }
    
    /**
     * Get complaints by priority
     *
     * @param string $priority The priority to filter by
     * @return array Complaints matching the priority
     */
    public function getComplaintsByPriority($priority)
    {
        $this->setTable('workercomplaints');
        $filters = ['priority' => $priority];
        return $this->filter($filters);
    }
}
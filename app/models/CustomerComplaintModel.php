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

    public function filter($filters)
    {
        $this->setTable('customercomplaints');

        $query = "SELECT * FROM customercomplaints WHERE 1";
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
        $this->setTable('customercomplaints');
        
        // Create filter array with issue_type
        $filters = ['issue_type' => $issueType];
        
        // Use the existing filter method
        return $this->filter($filters);
    }

    /**
     * Get complaints by user ID and issue type
     * @param int $userId User ID
     * @param string $issueType The issue type to filter by
     * @return array Complaints for the user with the specified issue type
     */
    public function getComplaintsByIssueType($userId, $issueType)
    {
        $this->setTable('customercomplaints');
        
        $query = "SELECT * FROM customercomplaints WHERE customerID = :customerID AND issue_type = :issue_type";
        $params = [
            'customerID' => $userId,
            'issue_type' => $issueType
        ];
        
        return $this->get_all($query, $params);
    }


}

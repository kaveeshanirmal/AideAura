<?php
class WorkerComplaint extends Controller
{
    private $workerComplaintModel;
    
    public function __construct()
    {
        $this->workerComplaintModel = new WorkerComplaintModel();
    }
    
    /**
     * Display the worker complaints management dashboard for admin
     */
    public function adminIndex()
    {
        $complaints = $this->workerComplaintModel->getAllComplaints();
        $this->view('admin/adminWorkerComplaints', ['complaints' => $complaints]);
    }
    
    /**
     * Display the worker complaints dashboard for HR manager
     */
    public function hrIndex()
    {
        $complaints = $this->workerComplaintModel->getAllComplaints();
        $this->view('hr/hrWorkerComplaints', ['complaints' => $complaints]);
    }
    
    /**
     * Display the complaints dashboard for operational manager
     */
    public function opIndex()
    {
        $complaints = $this->workerComplaintModel->getAllComplaints();
        $this->view('opm/opmWorkerComplaints', ['complaints' => $complaints]);
    }

    /**
     * Display the payment issues dashboard for finance manager
     */
    public function financeIndex()
    {
        // Get only payment-related complaints
        $complaints = $this->workerComplaintModel->getComplaintsByType('Payment Issues');
        $this->view('fm/workerPaymentIssues', ['complaints' => $complaints]);
    }
    
    /**
     * Get complaint details including worker information
     */
    public function details($complaintId)
    {
        $complaint = $this->workerComplaintModel->getComplaintById($complaintId);
        
        if (!$complaint) {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'message' => 'Complaint not found'
            ]);
            return;
        }
        
        // Get worker information using WorkerModel and UserModel
        $workerModel = new WorkerModel();
        $userModel = new UserModel();
        
        $workerId = $complaint->workerID;
        $worker = $workerModel->find($workerId, 'workerID');
        
        if ($worker) {
            $user = $userModel->find($worker->userID, 'userID');
            $workerName = $user ? $user->firstName . ' ' . $user->lastName : 'Worker #' . $workerId;
        } else {
            $workerName = 'Worker #' . $workerId;
        }
        
        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'complaint' => $complaint,
            'workerName' => $workerName
        ]);
    }
    
    /**
     * Get chat history for a complaint
     */
    public function chat($complaintId)
    {
        // Switch to the updates table
        $this->workerComplaintModel->setTable('workercomplaints_updates');
        
        // Get updates related to this complaint
        $updates = $this->workerComplaintModel->get($complaintId, 'complaintID');
        
        // Switch back to the complaints table
        $this->workerComplaintModel->setTable('workercomplaints');
        
        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'updates' => $updates
        ]);
    }
    
    /**
     * Submit a response to a complaint
     */
    public function respond()
    {
        header('Content-Type: application/json');
        
        // Get JSON data
        $json = json_decode(file_get_contents('php://input'));
        
        if (!$json || !isset($json->complaintID) || !isset($json->comments)) {
            echo json_encode([
                'success' => false,
                'message' => 'Invalid request data'
            ]);
            return;
        }
        
        // First update the complaint status
        $status = $json->status ?? 'In Progress';
        $updateResult = $this->workerComplaintModel->updateComplaint($json->complaintID, [
            'status' => $status
        ]);
        
        if (!$updateResult) {
            echo json_encode([
                'success' => false,
                'message' => 'Failed to update complaint status'
            ]);
            return;
        }
        
        // Then add the update record
        $updateData = [
            'complaintID' => $json->complaintID,
            'comments' => $json->comments,
            'status' => $status,
            'updated_at' => date('Y-m-d H:i:s'),
            'userID' => $_SESSION['userID'],
            'role' => $_SESSION['role']
        ];
        
        $result = $this->workerComplaintModel->submitComplaintUpdates($updateData);
        
        if (!$result) {
            echo json_encode([
                'success' => false,
                'message' => 'Failed to submit response'
            ]);
            return;
        }
        
        echo json_encode([
            'success' => true,
            'message' => 'Response submitted successfully'
        ]);
    }
    
    /**
     * Resolve a complaint
     */
    public function resolve()
    {
        header('Content-Type: application/json');
        
        // Get JSON data
        $json = json_decode(file_get_contents('php://input'), true);
        
        if (!$json || !isset($json['complaintID']) || !isset($json['solution'])) {
            echo json_encode([
                'success' => false,
                'message' => 'Invalid request data'
            ]);
            return;
        }
        
        $complaintId = $json['complaintID'];
        
        // Add resolution comment
        $updateData = [
            'complaintID' => $complaintId,
            'comments' => $json['solution'],
            'status' => 'Resolved',
            'updated_at' => date('Y-m-d H:i:s'),
            'userID' => $_SESSION['userID'],
            'role' => $_SESSION['role']
        ];
        
        // Creating a record at workercomplaints_updates table
        $result1 = $this->workerComplaintModel->submitComplaintUpdates($updateData);
        
        // Update complaint status to resolved
        if ($result1) {
            $result2 = $this->workerComplaintModel->updateComplaint($complaintId, [
                'status' => 'Resolved'
            ]);
            
            if ($result2) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Complaint resolved successfully'
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Failed to update complaint status'
                ]);
            }
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Failed to submit resolution comment'
            ]);
        }
    }
    
    /**
     * Delete a complaint
     */
    public function delete()
    {
        header('Content-Type: application/json');
        
        // Check for DELETE method
        if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
            echo json_encode([
                'success' => false,
                'message' => 'Method Not Allowed'
            ]);
            return;
        }
        
        // Parse the incoming JSON data
        $inputData = json_decode(file_get_contents('php://input'), true);
        
        if (!isset($inputData['complaintId'])) {
            echo json_encode([
                'success' => false,
                'message' => 'Invalid complaint ID'
            ]);
            return;
        }
        
        $complaintId = $inputData['complaintId'];
        
        // Delete complaint
        $result = $this->workerComplaintModel->deleteComplaint($complaintId);
        
        if (!$result) {
            echo json_encode([
                'success' => false,
                'message' => 'Failed to delete complaint'
            ]);
            return;
        }
        
        echo json_encode([
            'success' => true,
            'message' => 'Complaint deleted successfully'
        ]);
    }
    
    /**
     * Filter and sort complaints
     */
    public function filter()
    {
        header('Content-Type: application/json');
        
        // Get JSON data
        $json = json_decode(file_get_contents('php://input'));
        
        $issueType = isset($json->issueType) ? $json->issueType : 'all';
        $priority = isset($json->priority) ? $json->priority : 'all';
        $status = isset($json->status) ? $json->status : 'all';
        
        // Build filter array
        $filters = [];
        if ($issueType !== 'all') {
            $filters['issue_type'] = $issueType;
        }
        
        if ($status !== 'all') {
            $filters['status'] = $status;
        }
        
        // Apply filters
        $complaints = empty($filters) 
            ? $this->workerComplaintModel->getAllComplaints() 
            : $this->workerComplaintModel->filter($filters);
        
        // Convert to array if it's an object (for sorting purposes)
        $complaints = is_array($complaints) ? $complaints : (array)$complaints;
        
        // Sort by priority if requested
        if ($priority !== 'all') {
            usort($complaints, function($a, $b) use ($priority) {
                // Ensure we're accessing the 'priority' field correctly
                $priorityOrder = ['Low' => 1, 'Medium' => 2, 'High' => 3, 'Critical' => 4];
                
                $priorityA = isset($priorityOrder[$a->priority]) ? $priorityOrder[$a->priority] : 0;
                $priorityB = isset($priorityOrder[$b->priority]) ? $priorityOrder[$b->priority] : 0;
                
                if ($priority === 'high') {
                    return $priorityB - $priorityA; // High to Low
                } else {
                    return $priorityA - $priorityB; // Low to High
                }
            });
        }
        
        echo json_encode([
            'success' => true,
            'complaints' => $complaints
        ]);
    }
}
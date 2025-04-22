<?php
class Complaint extends Controller
{
    private $customerComplaintModel;
    
    public function __construct()
    {
        $this->customerComplaintModel = new CustomerComplaintModel();
    }
    
    /**
     * Display the complaints management dashboard for admin
     */
    public function adminIndex()
    {
        $complaints = $this->customerComplaintModel->getAllComplaints();
        $this->view('admin/adminWorkerInquiries', ['complaints' => $complaints]);
    }
    
    /**
     * Display the complaints dashboard for HR manager
     */
    public function hrIndex()
    {
        $complaints = $this->customerComplaintModel->getAllComplaints();
        $this->view('hr/hrWorkerInquiries', ['complaints' => $complaints]);
    }
    
    /**
     * Display the complaints dashboard for operational manager
     */
    public function opIndex()
    {
        $complaints = $this->customerComplaintModel->getAllComplaints();
        $this->view('opm/opmWorkerInquiries', ['complaints' => $complaints]);
    }
    
    /**
     * Get complaint details including customer information
     */
    public function details($complaintId)
    {
        
        $complaint = $this->customerComplaintModel->getComplaintById($complaintId);
        
        if (!$complaint) {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'message' => 'Complaint not found'
            ]);
            return;
        }
        
        // Get customer information using UserModel
        $userModel = new UserModel();
        $customerId = $complaint->customerID;
        $customer = $userModel->find($customerId, 'userID');
        $customerName = $customer ? $customer->fullName : 'Customer #' . $customerId;
        
        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'complaint' => $complaint,
            'customerName' => $customerName
        ]);
    }
    
    /**
     * Get chat history for a complaint
     */
    public function chat($complaintId)
    {
        // Switch to the updates table
        $this->customerComplaintModel->setTable('customercomplaints_updates');
        
        // Get updates related to this complaint
        $updates = $this->customerComplaintModel->get($complaintId, 'complaintID');
        
        // Switch back to the complaints table
        $this->customerComplaintModel->setTable('customercomplaints');
        
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
        $updateResult = $this->customerComplaintModel->updateComplaint($json->complaintID, [
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
        
        $result = $this->customerComplaintModel->submitComplaintUpdates($updateData);
        
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
        
        // Creating a record at complaints_updates table
        $result1 = $this->customerComplaintModel->submitComplaintUpdates($updateData);
        
        // Update complaint status to resolved
        if ($result1) {
            $result2 = $this->customerComplaintModel->updateComplaint($complaintId, [
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
        $result = $this->customerComplaintModel->deleteComplaint($complaintId);
        
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
        ? $this->customerComplaintModel->getAllComplaints() 
        : $this->customerComplaintModel->filter($filters);
    
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
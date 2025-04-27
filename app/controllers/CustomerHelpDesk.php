<?php

class customerHelpDesk extends Controller
{
    private $customerComplaintModel;

    public function __construct()
    {
        // Ensure user is logged in for all help desk actions
        if (!isset($_SESSION['customerID'])) {
            // Redirect to login if not logged in
            header('Location: ' . ROOT . '/public/login');
            exit();
        }
        
        $this->customerComplaintModel = new CustomerComplaintModel();
    }

    public function index($a = '', $b = '', $c = '')
    {
        $this->view('faq');
    }

    public function operationalHelp()
    {
        // Sending the existing complaints to the view
        $complaints = $this->customerComplaintModel->getComplaintsByUser($_SESSION['customerID']);

        $this->view('customerOperationalHelp', ['complaints' => $complaints]);
    }

    public function paymentHelp()
    {
        // Get payment-related complaints for the logged-in customer
        $paymentComplaints = $this->customerComplaintModel->getComplaintsByIssueType($_SESSION['customerID'], 'Payment Issues');
        
        $this->view('customerPaymentHelp', ['complaints' => $paymentComplaints]);
    }

    public function submitComplaint()
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Sanitize input data - Updated to use FILTER_SANITIZE_FULL_SPECIAL_CHARS
        $_POST = filter_input_array(INPUT_POST, [
            'issue_type' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
            'issue' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
            'description' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
            'complaint_id' => FILTER_SANITIZE_NUMBER_INT,
            'comments' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
            'help_desk_type' => FILTER_SANITIZE_FULL_SPECIAL_CHARS  // Add this line to capture help desk type
        ]);
        
        // Extract values with fallbacks
        $issueType = isset($_POST['issue_type']) ? $_POST['issue_type'] : '';
        $issue = isset($_POST['issue']) ? $_POST['issue'] : '';
        $description = isset($_POST['description']) ? $_POST['description'] : '';
        $helpDeskType = isset($_POST['help_desk_type']) ? $_POST['help_desk_type'] : '';
        
        // Determine which help desk to redirect to
        $redirectUrl = ROOT . '/public/customerHelpDesk/operationalHelp'; // Default
        
        // If it's from payment help desk, set payment redirect URL
        if ($helpDeskType === 'payment') {
            $redirectUrl = ROOT . '/public/customerHelpDesk/paymentHelp';
        }
        
        // You can also check the HTTP referer as a fallback
        if (empty($helpDeskType) && isset($_SERVER['HTTP_REFERER'])) {
            if (strpos($_SERVER['HTTP_REFERER'], 'paymentHelp') !== false) {
                $redirectUrl = ROOT . '/public/customerHelpDesk/paymentHelp';
            }
        }
        
        // Validate required fields
        if (empty($issue) || empty($description)) {
            $_SESSION['complaint_message'] = 'Please fill in all required fields';
            header('Location: ' . $redirectUrl);
            exit();
        }
        
        $data = [
            'customerID' => $_SESSION['customerID'],
            'issue_type' => $issueType,
            'issue' => $issue,
            'description' => $description,
            'status' => 'Pending',
            'priority' => $this->getComplaintPriority($issue),
            'submitted_at' => date('Y-m-d H:i:s') // Ensure current timestamp
        ];
        
        $result = $this->customerComplaintModel->addComplaint($data);
        
        if ($result) {
            // Get the complaint ID from the result
            $complaintID = $result;
            $_SESSION['complaint_message'] = "Your complaint (#$complaintID) has been submitted successfully. We'll get back to you as soon as possible.";
        } else {
            $_SESSION['complaint_message'] = 'Failed to submit your complaint. Please try again or contact support directly.';
        }
        
        // Redirect to the appropriate help page
        header('Location: ' . $redirectUrl);
        exit();
    } else {
        // If not a POST request, redirect to the form
        header('Location: ' . ROOT . '/public/customerHelpDesk/operationalHelp');
        exit();
    }
}

    /**
     * Determine the priority of a complaint based on the issue type
     * 
     * @param string $issue The issue identifier
     * @return string The priority level (Critical, High, Medium, Low)
     */
    private function getComplaintPriority($issue)
    {
        // Define groups for each priority level
        $criticalIssues = [
            'unable-to-book',
            'failed-payment',
            'website-loading'
        ];

        $highIssues = [
            'worker-unavailability',
            'worker-misconduct',
            'bug-report',
            'login-issues',
            'deactivation',
            'unauthorized-access',
            'service-complaint',
            'worker-complaint',
            'refund-request',
            'overcharged'
        ];

        $mediumIssues = [
            'worker-scheduling',
            'incorrect-details',
            'cancellation',
            'payment-verification',
            'profile-update',
            'forgot-password',
            'role-permission',
            'operational-help'
        ];

        $lowIssues = [
            'general-inquiry',
            'feedback',
            'general-feedback',
            'service-guidance',
            'policy-clarification'
        ];

        // Determine the priority based on the issue
        if (in_array($issue, $criticalIssues)) {
            return 'Critical';
        } elseif (in_array($issue, $highIssues)) {
            return 'High';
        } elseif (in_array($issue, $mediumIssues)) {
            return 'Medium';
        } elseif (in_array($issue, $lowIssues)) {
            return 'Low';
        }

        // Default to Medium if not found
        return 'Medium';
    }

    /**
     * Fetch and return the solution for a specific complaint
     * 
     * @param string $complaintId The ID of the complaint
     * @return json The solution data as JSON
     */
    public function getSolution($complaintId = null)
    {
        // Set the content type to JSON
        header('Content-Type: application/json');
        
        // Validate complaint ID
        if (!$complaintId) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid complaint ID']);
            exit;
        }

        // Verify the complaint belongs to the logged-in user
        $complaint = $this->customerComplaintModel->getComplaintById($complaintId);
        
        if (!$complaint || $complaint->customerID != $_SESSION['customerID']) {
            http_response_code(403); // Forbidden
            echo json_encode(['error' => 'You do not have permission to view this solution']);
            exit;
        }

        // Fetch solution from model
        $solution = $this->customerComplaintModel->getSolutionByComplaintId($complaintId);

        if ($solution && isset($solution->comments)) {
            // Return solution as JSON
            echo json_encode(['solution' => $solution->comments]);
        } else {
            // Return a more user-friendly message
            echo json_encode([
                'solution' => 'Our team has resolved this issue, but detailed notes are not available. If you need more information, please contact our support team.'
            ]);
        }
        exit;
    }
    
    /**
     * Clear the session message after display
     */
    public function clearSessionMessage()
    {
        if (isset($_SESSION['complaint_message'])) {
            unset($_SESSION['complaint_message']);
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false]);
        }
        exit();
    }

    /**
     * Get conversation history for a complaint
     * @param string $complaintId - The ID of the complaint
     */
    public function getConversation($complaintId = null)
    {
        // Set content type to JSON
        header('Content-Type: application/json');
        
        // Validate complaint ID
        if (!$complaintId) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid complaint ID']);
            exit;
        }
        
        // Verify the complaint belongs to the logged-in user
        $complaint = $this->customerComplaintModel->getComplaintById($complaintId);
        
        if (!$complaint || $complaint->customerID != $_SESSION['customerID']) {
            http_response_code(403); // Forbidden
            echo json_encode(['error' => 'You do not have permission to view this conversation']);
            exit;
        }
        
        // Get all updates for this complaint using a custom query
        $this->customerComplaintModel->setTable('customercomplaints_updates');
        $query = "SELECT * FROM customercomplaints_updates 
                WHERE complaintID = :complaintID
                ORDER BY updated_at ASC";
                
        $params = [
            'complaintID' => $complaintId
        ];
        
        $updates = $this->customerComplaintModel->get_all($query, $params);
        
        // Format timestamps for display
        foreach ($updates as &$update) {
            $update->timestamp = date('F j, Y \a\t g:i a', strtotime($update->updated_at));
        }
        
        // Return as JSON
        echo json_encode([
            'success' => true,
            'updates' => $updates
        ]);
        exit;
    }

    /**
     * Submit a customer reply to a complaint
     */
    public function submitReply()
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Location: ' . ROOT . '/public/customerHelpDesk/operationalHelp');
        exit();
    }
    
    // Sanitize and get input
    $_POST = filter_input_array(INPUT_POST, [
        'complaint_id' => FILTER_SANITIZE_NUMBER_INT,
        'comments' => FILTER_SANITIZE_FULL_SPECIAL_CHARS
    ]);
    
    $complaintId = $_POST['complaint_id'] ?? '';
    $comments = $_POST['comments'] ?? '';
    
    // Validate required fields
    if (empty($complaintId) || empty($comments)) {
        $_SESSION['complaint_message'] = 'Please provide a message for your reply.';
        header('Location: ' . ROOT . '/public/customerHelpDesk/operationalHelp');
        exit();
    }
    
    // Verify the complaint belongs to the logged-in user
    $complaint = $this->customerComplaintModel->getComplaintById($complaintId);
    
    if (!$complaint || $complaint->customerID != $_SESSION['customerID']) {
        $_SESSION['complaint_message'] = 'Complaint not found or unauthorized access.';
        header('Location: ' . ROOT . '/public/customerHelpDesk/operationalHelp');
        exit();
    }
    
    // Get the corresponding userID for this customer
    $userID = $this->getUserIdForCustomer($_SESSION['customerID']);
    
    if (!$userID) {
        $_SESSION['complaint_message'] = 'User ID not found. Please contact support.';
        header('Location: ' . ROOT . '/public/customerHelpDesk/operationalHelp');
        exit();
    }
    
    // Create update data with the correct userID
    $updateData = [
        'complaintID' => $complaintId,
        'status' => 'In Progress', 
        'comments' => $comments,
        'userID' => $userID, // Use the correct userID from users table
        'role' => 'Customer', 
        'updated_at' => date('Y-m-d H:i:s')
    ];
    
    // Add the update using existing method
    $result = $this->customerComplaintModel->submitComplaintUpdates($updateData);
    
    if ($result) {
        // Update the main complaint status if needed
        if ($complaint->status === 'Pending') {
            $this->customerComplaintModel->updateComplaint($complaintId, ['status' => 'In Progress']);
        }
        
        $_SESSION['complaint_message'] = 'Your reply has been submitted successfully.';
    } else {
        $_SESSION['complaint_message'] = 'Failed to submit your reply. Please try again.';
    }
    
    header('Location: ' . ROOT . '/public/customerHelpDesk/operationalHelp');
    exit();
}

/**
 * Helper method to get the userID for a customerID
 * 
 * @param int $customerID The customer ID
 * @return int|null The corresponding user ID or null if not found
 */
private function getUserIdForCustomer($customerID)
{
    return $this->customerComplaintModel->getUserIdByCustomerId($customerID);
}
}
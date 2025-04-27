<?php

class workerHelpDesk extends Controller
{
    private $workerComplaintModel;

    public function __construct()
    {
        // Ensure user is logged in for all help desk actions
        if (!isset($_SESSION['workerID'])) {
            // Redirect to login if not logged in
            header('Location: ' . ROOT . '/public/login');
            exit();
        }

        $this->workerComplaintModel = new WorkerComplaintModel();
    }

    public function index($a = '', $b = '', $c = '')
    {
        $this->view('faq');
    }

    public function operationalHelp()
    {
        // Sending the existing complaints to the view
        $complaints = $this->workerComplaintModel->getComplaintsByWorker($_SESSION['workerID']);

        $this->view('workerOperationalHelp', ['complaints' => $complaints]);
    }

    public function paymentHelp()
    {
        // Get payment-related complaints for the logged-in worker
        $this->workerComplaintModel->setTable('workercomplaints');
        $query = "SELECT * FROM workercomplaints WHERE workerID = :workerID AND issue_type = :issueType";
        $params = [
            'workerID' => $_SESSION['workerID'],
            'issueType' => 'Payment Issues'
        ];
        $paymentComplaints = $this->workerComplaintModel->get_all($query, $params);

        $this->view('workerPaymentHelp', ['complaints' => $paymentComplaints]);
    }

    public function submitComplaint()
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Sanitize input data
        $_POST = filter_input_array(INPUT_POST, [
            'issue_type' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
            'issue' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
            'description' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
            'complaint_id' => FILTER_SANITIZE_NUMBER_INT,
            'comments' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
            'help_desk_type' => FILTER_SANITIZE_FULL_SPECIAL_CHARS  // Add this line
        ]);
        
        // Extract values with fallbacks
        $issueType = isset($_POST['issue_type']) ? $_POST['issue_type'] : '';
        $issue = isset($_POST['issue']) ? $_POST['issue'] : '';
        $description = isset($_POST['description']) ? $_POST['description'] : '';
        $helpDeskType = isset($_POST['help_desk_type']) ? $_POST['help_desk_type'] : '';
        
        // Determine which help desk to redirect to based on the form submission
        $redirectUrl = ROOT . '/public/workerHelpDesk/operationalHelp'; // Default to operational
        
        if ($helpDeskType === 'payment') {
            $redirectUrl = ROOT . '/public/workerHelpDesk/paymentHelp';
        } else if ($helpDeskType === 'operational') {
            $redirectUrl = ROOT . '/public/workerHelpDesk/operationalHelp';
        }
        
        // You can also check HTTP referer as a fallback
        if (empty($helpDeskType) && isset($_SERVER['HTTP_REFERER'])) {
            if (strpos($_SERVER['HTTP_REFERER'], 'paymentHelp') !== false) {
                $redirectUrl = ROOT . '/public/workerHelpDesk/paymentHelp';
            } else if (strpos($_SERVER['HTTP_REFERER'], 'operationalHelp') !== false) {
                $redirectUrl = ROOT . '/public/workerHelpDesk/operationalHelp';
            }
        }
        
        // Validate required fields
        if (empty($issue) || empty($description)) {
            $_SESSION['complaint_message'] = 'Please fill in all required fields';
            header('Location: ' . $redirectUrl);
            exit();
        }
        
        $data = [
            'workerID' => $_SESSION['workerID'],
            'issue_type' => $issueType,
            'issue' => $issue,
            'description' => $description,
            'status' => 'Pending',
            'priority' => $this->getComplaintPriority($issue),
            'submitted_at' => date('Y-m-d H:i:s') // Ensure current timestamp
        ];
        
        $result = $this->workerComplaintModel->addComplaint($data);
        
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
        // If not a POST request, redirect to the operational form by default
        header('Location: ' . ROOT . '/public/workerHelpDesk/operationalHelp');
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
            'unable-to-accept-jobs',
            'failed-payment',
            'website-loading'
        ];

        $highIssues = [
            'customer-misconduct',
            'job-assignment-issues',
            'bug-report',
            'login-issues',
            'deactivation',
            'unauthorized-access',
            'service-complaint',
            'customer-complaint',
            'payment-request',
            'underpaid'
        ];

        $mediumIssues = [
            'job-scheduling',
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
        $complaint = $this->workerComplaintModel->getComplaintById($complaintId);

        if (!$complaint || $complaint->workerID != $_SESSION['workerID']) {
            http_response_code(403); // Forbidden
            echo json_encode(['error' => 'You do not have permission to view this solution']);
            exit;
        }

        // Fetch solution from model
        $solution = $this->workerComplaintModel->getSolutionByComplaintId($complaintId);

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
        $complaint = $this->workerComplaintModel->getComplaintById($complaintId);

        if (!$complaint || $complaint->workerID != $_SESSION['workerID']) {
            http_response_code(403); // Forbidden
            echo json_encode(['error' => 'You do not have permission to view this conversation']);
            exit;
        }

        // Get all updates for this complaint using a custom query
        $this->workerComplaintModel->setTable('workercomplaints_updates');
        $query = "SELECT * FROM workercomplaints_updates 
                WHERE complaintID = :complaintID
                ORDER BY updated_at ASC";

        $params = [
            'complaintID' => $complaintId
        ];

        $updates = $this->workerComplaintModel->get_all($query, $params);

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
 * Submit a worker reply to a complaint
 */
public function submitReply()
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Location: ' . ROOT . '/public/workerHelpDesk/operationalHelp');
        exit();
    }

    // Sanitize and get input
    $_POST = filter_input_array(INPUT_POST, [
        'complaint_id' => FILTER_SANITIZE_NUMBER_INT,
        'comments' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
        'help_desk_type' => FILTER_SANITIZE_FULL_SPECIAL_CHARS  // Add this line to capture help desk type
    ]);

    $complaintId = $_POST['complaint_id'] ?? '';
    $comments = $_POST['comments'] ?? '';
    $helpDeskType = $_POST['help_desk_type'] ?? '';
    
    // Determine which help desk to redirect to
    $redirectUrl = ROOT . '/public/workerHelpDesk/operationalHelp'; // Default
    
    // If it's from payment help desk, set payment redirect URL
    if ($helpDeskType === 'payment') {
        $redirectUrl = ROOT . '/public/workerHelpDesk/paymentHelp';
    }
    
    // You can also check the HTTP referer as a fallback
    if (empty($helpDeskType) && isset($_SERVER['HTTP_REFERER'])) {
        if (strpos($_SERVER['HTTP_REFERER'], 'paymentHelp') !== false) {
            $redirectUrl = ROOT . '/public/workerHelpDesk/paymentHelp';
        }
    }

    // Validate required fields
    if (empty($complaintId) || empty($comments)) {
        $_SESSION['complaint_message'] = 'Please provide a message for your reply.';
        header('Location: ' . $redirectUrl);
        exit();
    }

    // Verify the complaint belongs to the logged-in user
    $complaint = $this->workerComplaintModel->getComplaintById($complaintId);

    if (!$complaint || $complaint->workerID != $_SESSION['workerID']) {
        $_SESSION['complaint_message'] = 'Complaint not found or unauthorized access.';
        header('Location: ' . $redirectUrl);
        exit();
    }

    // Create update data
    $updateData = [
        'complaintID' => $complaintId,
        'status' => 'In Progress', // Set to In Progress when worker replies
        'comments' => $comments,
        'userID' => $_SESSION['workerID'],
        'role' => 'Worker', // Set role as Worker
        'updated_at' => date('Y-m-d H:i:s')
    ];

    // Add the update using existing method
    $result = $this->workerComplaintModel->submitComplaintUpdates($updateData);

    if ($result) {
        // Update the main complaint status if needed
        if ($complaint->status === 'Pending') {
            $this->workerComplaintModel->updateComplaint($complaintId, ['status' => 'In Progress']);
        }

        $_SESSION['complaint_message'] = 'Your reply has been submitted successfully.';
    } else {
        $_SESSION['complaint_message'] = 'Failed to submit your reply. Please try again.';
    }

    header('Location: ' . $redirectUrl);
    exit();
}
}

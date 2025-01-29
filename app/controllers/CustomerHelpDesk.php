<?php

class customerHelpDesk extends Controller
{
    private $customerComplaintModel;

    public function __construct()
    {
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
        $this->view('customerPaymentHelp');
    }

    public function submitComplaint()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'customerID' => $_SESSION['customerID'],
                'issue_type' => $_POST['issue-type'],
                'issue' => $_POST['issue'],
                'description' => $_POST['description'],
                'priority' => $this->getComplaintPriority($_POST['issue']),
            ];

            // Ensure session is started
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }

            $result = $this->customerComplaintModel->addComplaint($data);

            if ($result) {
                $_SESSION['complaint_message'] = 'Complaint submitted successfully';
            } else {
                $_SESSION['complaint_message'] = 'Failed to submit complaint';
            }

            // Redirect to the operational help page
            header('Location: ' . ROOT . '/public/customerHelpDesk/operationalHelp');
            exit();
        }
    }

    function getComplaintPriority($issue)
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
            $priority = 'Critical';
        } elseif (in_array($issue, $highIssues)) {
            $priority = 'High';
        } elseif (in_array($issue, $mediumIssues)) {
            $priority = 'Medium';
        } elseif (in_array($issue, $lowIssues)) {
            $priority = 'Low';
        }

        return $priority;
    }

    public function getSolution($complaintId = null)
    {
        // Validate complaint ID
        if (!$complaintId) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid complaint ID']);
            exit;
        }

        // Fetch solution from model
        $solution = $this->customerComplaintModel->getSolutionByComplaintId($complaintId);

       if ($solution) {
            // Return solution as JSON
            header('Content-Type: application/json');
            echo json_encode(['solution' => $solution->comments]);
            exit;
       } else {
            http_response_code(400);
            echo json_encode(['error'=> '
            Solution not found']);
            exit;
        }
    }
    
    public function clearSessionMessage()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        unset($_SESSION['complaint_message']);
        http_response_code(200); // Indicate success
        exit();
    }

}

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

        $this->view('customerOperationalHelp');
    }

    public function paymentHelp()
    {
        $this->view('customerPaymentHelp');
    }

    public function submitComplaint()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Collect and sanitize user input
            $data = [
                'customerID' => $_SESSION['customerID'],
                'issue_type' => $_POST['issue-type'],
                'issue' => $_POST['issue'],
                'description' => $_POST['description'],
                'priority' => $this->getComplaintPriority($_POST['issue']),
            ];

            // Inser the complaint to the database
            $result = $this->customerComplaintModel->addComplaint($data);

            if ($result) {
                echo 'Complaint submitted successfully';
            } else {
                echo 'Failed to submit complaint';
            }
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
    
}

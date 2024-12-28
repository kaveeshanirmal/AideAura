<?php

class Admin extends Controller
{
    private $customerComplaintModel;

    public function __construct()
    {
        $this->customerComplaintModel = new CustomerComplaintModel();
    }
    public function index($a = '', $b = '', $c = '')
    {
        $this->view('admin/adminReports');
    }

    public function employees($a = '', $b = '', $c = '')
    {
        $this->view('admin/adminEmployees');
    }

    public function workers()
    {
        $this->view('admin/adminWorkerProfile');
    }

    public function worker1()
    {
        $this->view('admin/adminWorkerProfile1');
    }
    public function worker2()
    {
        $this->view('admin/adminWorkerProfile2');
    }
    public function workerSchedule()
    {
        $this->view('admin/adminWorkerProfileSchedule');
    }

    public function customers()
    {
        $this->view('admin/customerProfiles');
    }

    public function workerRoles()
    {
        $this->view('admin/adminRoles');
    }

    public function workerRoles1()
    {
        $this->view('admin/adminRoles1');
    }

    public function paymentRates()
    {
        $this->view('admin/adminPayrate');
    }

    public function paymentHistory()
    {
        $this->view('admin/adminPaymentHistory');
    }

    public function workerInquiries()
    {
        $complaints = $this->customerComplaintModel->getAllComplaints();
        $this->view('admin/adminWorkerInquiries', ['complaints' => $complaints]);
    }

    public function paymentIssues()
    {
        $this->view('admin/adminWorkerInquiries1');
    }

    public function replyComplaint()
{
    header('Content-Type: application/json');

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Process the form
        $inputData = json_decode(file_get_contents('php://input'), true);

        if($inputData)
        {
            $data = [
                'complaintID' => $inputData['complaintID'],
                'comments' => $inputData['solution'],
            ];

            // creating a record at complaints_updates table
            $result1 = $this->customerComplaintModel->submitComplaintUpdates($data);

            // updating the status of the complaint
            if($result1)
            {
                // get existing data
                $result2 = $this->customerComplaintModel->updateComplaint($inputData['complaintID'], ['status' => 'Resolved']);
                if($result2)
                {
                    http_response_code(200);
                    die(json_encode([
                        'success' => true,
                        'message' => 'Solution sent successfully',
                    ]));
                }
                else
                {
                    http_response_code(500);
                    die(json_encode([
                        'success' => false,
                        'message' => 'Error updating the complaint status.',
                    ]));
                }
            }
            else
            {
                http_response_code(500);
                die(json_encode([
                    'success' => false,
                    'message' => 'Error sending the solution.',
                ]));
            }
        }
        else
            {
                http_response_code(400);
                die(json_encode([
                    'success' => false,
                    'message' => 'Invalid input data.',
                ]));
            }
        }
    else
        {
            http_response_code(405);
            die(json_encode([
                'success' => false,
                'message' => 'Method Not Allowed',
            ]));
        }
    }

    public function deleteComplaint()
{
    header('Content-Type: application/json');

    if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
        // Parse the incoming JSON data
        $inputData = json_decode(file_get_contents('php://input'), true);

        if (isset($inputData['complaintId'])) {
            $complaintId = $inputData['complaintId'];

            // Attempt to delete the complaint
            $result = $this->customerComplaintModel->deleteComplaint($complaintId);

            if ($result) {
                http_response_code(200);
                die(json_encode([
                    'success' => true,
                    'message' => 'Complaint deleted successfully',
                ]));
            } else {
                http_response_code(500);
                die(json_encode([
                    'success' => false,
                    'message' => 'Failed to delete the complaint',
                ]));
            }
        } else {
            http_response_code(400);
            die(json_encode([
                'success' => false,
                'message' => 'Invalid complaint ID',
            ]));
        }
    } else {
            http_response_code(405);
            die(json_encode([
                'success' => false,
                'message' => 'Method Not Allowed',
            ]));
        }
    }
}
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
    $userModel = new UserModel();
    $allEmployees = $userModel->getAllEmployees(); // Fetch all Workers from the database
    error_log("Workers in controller: " . json_encode($allEmployees));

    // Define the allowed roles for filtering
    $allowedRoles = ['worker'];

    // Filter Workers based on allowed roles
    $filteredWorkers = array_filter($allEmployees, function ($employee) use ($allowedRoles) {
        return in_array($employee->role, $allowedRoles) && ($employee->isDelete == 0); // Access object property using '->'
    });

    if (!$filteredWorkers) {
        error_log("No Workers with specified roles retrieved or query failed");
        $filteredWorkers = []; // Ensuring the variable is always an array
    }

    // Dynamically update roles for filtered workers 
    $updatedWorkers = $this->assignDynamicRoles($filteredWorkers);

    $this->view('admin/adminWorkerProfile', ['workers' => $updatedWorkers]);
}


//Assign dynamic roles to filtered workers in worker array role element from jobroles table
private function assignDynamicRoles($filteredWorkers)
{
    $userModel = new UserModel();

    // Map through each worker and update the role dynamically
    return array_map(function ($worker) use ($userModel) {
        $dynamicRole = $userModel->getWorkerRole($worker->userID);
        $worker->role = $dynamicRole;
        return $worker;
    }, $filteredWorkers);
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
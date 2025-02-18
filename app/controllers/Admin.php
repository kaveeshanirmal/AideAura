<?php

class Admin extends Controller
{
    private $customerComplaintModel;
    private $workerClicked = [];

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

    //$workerClicked = $filteredWorkers;

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

public function workerDetails()
{
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        // Retrieve userID from the query parameters
        $userID = $_GET['userID'] ?? null;

        if ($userID) {
            // Search for the worker in the workerClicked array
            $worker = array_filter($this->workerClicked, function ($w) use ($userID) {
                return $w->userID == $userID;
            });

            // If a worker is found, pass it to the view
            if (!empty($worker)) {
                $worker = reset($worker); // Get the first matching worker
                $this->view('admin/adminWorkerProfile1', ['worker' => $worker]);
            } else {
                // Handle case where no worker is found
                http_response_code(404);
                echo "Worker not found.";
            }
        } else {
            // Handle case where userID is not provided
            http_response_code(400);
            echo "User ID is missing.";
        }
    } else {
        // Handle invalid request method
        http_response_code(405);
        echo "Method Not Allowed.";
    }
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

        $workerRoleModel = new WorkerRoleModel();
        $allRoles = $workerRoleModel->getAllRoles(); // Fetch all Workers from the database
        error_log("Workers in controller: " . json_encode($allRoles));    
        $this->view('admin/adminRoles',['roles'=> $allRoles]);
    }

    public function workerRoles1()
    {
        $this->view('admin/adminRoles1');
    }

    public function addRole() {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('Invalid request method');
            }
    
            // Set header first
            header('Content-Type: application/json');
    
            // Validate form inputs
            if (empty($_POST['roleName']) || empty($_POST['roleDescription'])) {
                throw new Exception('Role name and description are required');
            }
    
            // Sanitize inputs
            $roleName = trim(filter_var($_POST['roleName'], FILTER_SANITIZE_STRING));
            $roleDescription = trim(filter_var($_POST['roleDescription'], FILTER_SANITIZE_STRING));
    
            // Validate file upload
            if (!isset($_FILES['roleImage']) || $_FILES['roleImage']['error'] !== UPLOAD_ERR_OK) {
                throw new Exception('Please select a valid image file');
            }
    
            // Handle file upload
            $uploadDir = ROOT_PATH . '/public/assets/images/roles/';
            $fileTmpPath = $_FILES['roleImage']['tmp_name'];
            $fileName = uniqid() . '_' . basename($_FILES['roleImage']['name']);
            $uploadFilePath = $uploadDir . $fileName;
    
            // Create directory if it doesn't exist
            if (!is_dir($uploadDir)) {
                if (!mkdir($uploadDir, 0777, true)) {
                    throw new Exception('Failed to create upload directory');
                }
            }
    
            // Validate image file
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
            $fileInfo = finfo_open(FILEINFO_MIME_TYPE);
            $mimeType = finfo_file($fileInfo, $fileTmpPath);
            finfo_close($fileInfo);
    
            if (!in_array($mimeType, $allowedTypes)) {
                throw new Exception('Invalid file type. Please upload an image (JPEG, PNG, or GIF)');
            }
    
            // Move uploaded file
            if (!move_uploaded_file($fileTmpPath, $uploadFilePath)) {
                throw new Exception('Failed to upload the image');
            }
    
            // Prepare role data
            $roleData = [
                'name' => $roleName,
                'description' => $roleDescription,
                'image' => 'public/assets/images/roles/' . $fileName,
            ];
    
            // Insert role
            $workerRoleModel = new WorkerRoleModel();
            $insertStatus = $workerRoleModel->insertRole($roleData);
    
            if (!$insertStatus) {
                throw new Exception('Failed to add role to database');
            }
    
            echo json_encode([
                'status' => 'success',
                'message' => 'Role added successfully!'
            ]);
    
        } catch (Exception $e) {
            error_log('Role addition error: ' . $e->getMessage());
            echo json_encode([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
        exit;
    }    
    
    public function paymentRates()
    {
        $paymentRateModel = new PaymentRateModel();
        $allRates = $paymentRateModel->getAllPaymentRates(); // Fetch all payment rateas from the database
        $this->view('admin/adminPayrate',['rates'=>$allRates]);
    }

    public function updatePaymentRates() {
        try {
            // Ensure proper content type header is set first
            header('Content-Type: application/json');
            
            // Read raw POST data
            $rawData = file_get_contents('php://input');
            if (!$rawData) {
                throw new Exception('No data received');
            }
            
            // Decode JSON data
            $data = json_decode($rawData, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new Exception('Invalid JSON data: ' . json_last_error_msg());
            }
            
            // Validate required fields
            if (!$data || !isset($data['ServiceID'])) {
                throw new Exception('Invalid data. ServiceID is required.');
            }
            
            // Extract and validate data
            $ServiceID = $data['ServiceID'];
            $updateData = [
                'BasePrice' => isset($data['BasePrice']) ? (float) $data['BasePrice'] : null,
                'BaseHours' => isset($data['BaseHours']) ? (float) $data['BaseHours'] : null
            ];
            
            // Validate numeric values
            if ($updateData['BasePrice'] === null || $updateData['BaseHours'] === null) {
                throw new Exception('BasePrice and BaseHours are required and must be numeric.');
            }
            
            // Update payment rate
            $paymentRateModel = new PaymentRateModel();
            $success = $paymentRateModel->updatePayrate($ServiceID, $updateData);
            
            if (!$success) {
                throw new Exception('Failed to update payment rate.');
            }
            
            echo json_encode([
                'success' => true,
                'message' => 'Payment rates updated successfully'
            ]);
            
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
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
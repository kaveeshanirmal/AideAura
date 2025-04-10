<?php

class Admin extends Controller
{
    private $customerComplaintModel;
    private $workerClicked = [];
    private $selectedWorkerRole = ['role' => 'N/A'];

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

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $userID = $_POST['workerData'] ?? null;

        if (!empty($userID)) {
            $workerModel = new WorkerModel();
    
            // Fetch details using userID
            $workerDetails = $workerModel->getWorkerDetails($userID);
    
                // Use default details + worker data in worker table if user not verified yet
                if (!$workerDetails || !is_array($workerDetails)) {

                $worker = $workerModel->findWorker($userID);

                    $workerDetails = [
                        'userID' => $userID,
                        'Nationality' => 'N/A',
                        'fullName' => $worker->fullName,
                        'email' => $worker->email,
                        'role' => $worker->role,
                        'username' => $worker->username,
                        'Contact' => $worker->phone,
                        'Gender' => 'N/A',
                        'NIC'=> 'N/A',
                        'Age'=> 'N/A',
                        'ServiceType' => 'N/A',
                        'SpokenLanguages'=> 'N/A',
                        'WorkLocations' => 'N/A',
                        'ExperienceLevel' => 'N/A',
                        'AllergiesOrPhysicalLimitations' => 'N/A',
                        'Description' => 'N/A',
                        'HomeTown' => 'N/A',
                        'BankNameAndBranchCode' => 'N/A',
                        'BankAccountNumber' => 'N/A',
                        'WorkingWeekDays'=> 'N/A',
                        'WorkingWeekEnds'=> 'N/A', // Fixed key spacing
                        'Notes' => 'N/A',
                        'Status' => 'Not verified',
                    ];
                }

                $this->view('admin/adminWorkerProfile1', ['worker' => $workerDetails]);
            } else {
                http_response_code(404);
                echo "Missing userID.";
            }
        } else {
            http_response_code(400);
            echo "Method not allowed .";
        }
}

    public function workerCertificates(){
            $workerModel = new WorkerModel();
            $workerDetails = $workerModel->getWorkerCertificates();
            $finalData = array_merge($workerDetails, $this->selectedWorkerRole);
        $this->view('admin/adminWorkerProfile2', ['data'=> $finalData]);
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
        


        // Filter roles that doesn't delete 
        $filteredRoles = array_filter($allRoles, function($role){
            return $role->isDelete == 0;
        });

        if(empty($filteredRoles)){
            error_log("No roles with specified roles retrieved or query failed");
        }

        $this->view('admin/adminRoles',['roles'=> $filteredRoles]);
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
    
    public function deleteRoles() {
        try{
            // log raw input
            $row_input = file_get_contents('php://input');
            error_log("Raw input received: " . $row_input);

            // decode JSON with error checking
            $data = json_decode($row_input, true);
            if(json_last_error() !== JSON_ERROR_NONE) {
                throw new Exception('Invalid JSON: ' . json_last_error_msg());
            }

            // log decoded data
            error_log("Decoded data: " . print_r($data, true));

            // validation of userID
            if(!isset($data['roleID'])) {
                throw new Exception('This role roleID is required');
            }

            if (!is_numeric($data['roleID'])) {
                throw new Exception('Invalid roleID format');
            }
            $workerRoleModel = new WorkerRoleModel();
            $success = $workerRoleModel->softDeleteRole($data['roleID']);
    
            if($success == false) {
                throw new Exception('Database deletion failed');
            }
                // set headers
                header('Content-Type: application/json');

                // Return success response
                echo json_encode ([
                    'success' => true, 
                    'message' => 'Role deleted successfully'
                ]);
            }  catch(Exception $e){
                // log the error
                error_log("Delete Role erro: " . $e->getMessage());

                // set headers
                header('Content-Type: application/json');
                http_response_code(500);

                // return erro response 
                echo json_encode([
                    'success' => false,
                    'message' => $e->getMessage(),
                    'error' => true
                ]);
                exit;
            }

    
    
        }

    public function paymentRates()
    {
        $paymentRateModel = new PaymentRateModel();
        $allRates = $paymentRateModel->getAllPaymentRates(); // Fetch all payment rateas from the database
        
         // Filter roles that doesn't delete 
         $filteredRates = array_filter($allRates, function($rate){
            return $rate->isDelete == 0;
        });

        if(empty($filteredRates)){
            error_log("No roles with specified roles retrieved or query failed");
        }
        
        
        $this->view('admin/adminPayrate',['rates'=>$filteredRates]);
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
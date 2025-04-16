<?php

class HrManager extends Controller
{
    // public function index()
    // {
    //     $this->view('hr/workerProfiles');
    // }

    public function index()
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
    
        $this->view('hr/workerProfiles', ['workers' => $updatedWorkers]);
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
        $source = $_POST['source'] ?? 'workerProfiles'; // Default source is workerProfiles

        if (!empty($userID)) {
            $workerModel = new WorkerModel();
    
            // Fetch details using userID
            $workerDetails = $workerModel->getWorkerDetails($userID);
    
            // Use default details + worker data in worker table if user not verified yet
            if (!$workerDetails || !is_array($workerDetails)) {
                $worker = $workerModel->findWorker($userID);

                $workerDetails = [
                    'requestID'=>'N/A',
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
                    'WorkingWeekEnds'=> 'N/A',
                    'Notes' => 'N/A',
                    'Status' => 'Not verified',
                ];
            }

            // Pass both worker details and the source to the view
            $this->view('hr/workerInfo', [
                'worker' => $workerDetails,
                'source' => $source
            ]);
        } else {
            http_response_code(404);
            echo "Missing userID.";
        }
    } else {
        http_response_code(400);
        echo "Method not allowed.";
    }
}

    public function updateVerificationStatus() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            header('Content-Type: application/json');
            
            // Get and validate input
            $requestID = isset($_POST['requestID']) ? $_POST['requestID'] : null;

            $status = isset($_POST['status']) ? strtolower($_POST['status']) : null; // Force lowercase            
            // Validate input

                    // Validate input
        if (!$requestID || !$status) {
            echo json_encode([
                'success' => false,
                'message' => 'Missing required parameters.'
            ]);
            return;
        }

            if (!in_array($status, ['approved', 'rejected'])) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Invalid status value.'. $status
                ]);
                return;
            }
            
            // Log the received data for debugging
            error_log("Updating verification status: requestID=$requestID, status=$status");
            
            $requestModel = new VerificationRequestModel();
            $data = ['Status' => $status];
            $updated = $requestModel->updateRequest($data, $requestID);
            
            // Log the value of $updated for debugging
            error_log("Value of \$updated: " . json_encode($updated));
            
            if ($updated) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Verification status updated successfully.'
                ]);
            } else {// Get the error message if available
            $errorMsg = $requestModel->getLastError() ?: 'Unknown error';
            echo json_encode([
                'success' => false,
                'message' => 'Failed to update verification status. Error: ' . $errorMsg
            ]);
        
            }
        } else {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false, 
                'message' => 'Invalid request method.'
            ]);
        }
    }
    

    public function workerCertificates()
    {
        $this->view('hr/workerCertificates');
    }

    public function availabilitySchedule()
    {
        $this->view('hr/availabilitySchedule');
    }

    public function workerSchedules()
    {
        $this->view('hr/workerSchedules');
    }

    public function verificationRequests()
    {
         $verificationRequestsModel = new VerificationRequestModel();
         $pendingRequests = $verificationRequestsModel->getPendingRequests();
         if ($pendingRequests){
         $this->view('hr/verificationRequests', ['verificationRequests' => $pendingRequests]);
         } else {
            $this->view('hr/verificationRequests', ['verificationRequests' => []]);
         }

    }


    public function findWorkerUserID() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $workerID = $_POST['workerDataID'] ?? null;
    
            if ($workerID) {
                $workerModel = new WorkerModel();
                // Fetch userID of worker using their workerID
                $userID = $workerModel->findUserIDbyWorkerID($workerID);
    
                if ($userID) {
                    // Add source parameter to indicate we're coming from verification requests
                    $_POST['workerData'] = $userID;
                    $_POST['source'] = 'verificationRequests';
                    $this->workerDetails();
                } else {
                    http_response_code(404);
                    echo "UserID not found for the given WorkerID.";
                }
            } else {
                http_response_code(400);
                echo "WorkerID is missing.";
            }
        } else {
            http_response_code(405);
            echo "Method not allowed.";
        }
    }

    public function workerInquiries()
    {
        $this->view('hr/workerInquiries');
    }
}
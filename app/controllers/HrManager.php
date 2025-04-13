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
    
                    $this->view('hr/workerInfo', ['worker' => $workerDetails]);
                } else {
                    http_response_code(404);
                    echo "Missing userID.";
                }
            } else {
                http_response_code(400);
                echo "Method not allowed .";
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
        $this->view('hr/verificationRequests');
    }

    public function workerInquiries()
    {
        $this->view('hr/workerInquiries');
    }
}
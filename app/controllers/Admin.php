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
        $this->view('admin/adminPaymentHistory');
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

public function getScheduleView()
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $view = $_POST['view'] ?? 'week';
        $offset = (int)($_POST['offset'] ?? 0);
        $userID = $_POST['userID'] ?? null;
        
        $workingScheduleModel = new WorkingScheduleModel();
        
        // If userID is provided, get schedules only for that user
        if ($userID) {
            $workerModel = new WorkerModel();
            $workerID = $workerModel->findWorkerIDbyUserID($userID);
            
            if (!$workerID) {
                echo '<div class="error-message">Worker not found.</div>';
                return;
            }
            
            $allSchedules = $workingScheduleModel->getScheduleByWorkerID($workerID);
            $worker = $workerModel->findWorker($userID);
            
            // Add user information to each schedule
            if ($allSchedules) {
                foreach ($allSchedules as $schedule) {
                    $schedule->userID = $worker->userID;
                    $schedule->fullName = $worker->fullName;
                    $schedule->role = $worker->role;
                }
            } else {
                // Return empty schedule message
                echo '<div class="no-schedule">No schedules found for this worker.</div>';
                return;
            }
        } else {
            // Get all schedules
            $allSchedules = $workingScheduleModel->getAllSchedules();
            
            // Add user information to each schedule
            $workerModel = new WorkerModel();
            foreach ($allSchedules as $schedule) {
                $workerID = $schedule->workerID;
                $userID = $workerModel->findUserIDbyWorkerID($workerID);
                $worker = $workerModel->findWorker($userID);
                
                $schedule->userID = $worker->userID;
                $schedule->fullName = $worker->fullName;
                $schedule->role = $worker->role;
            }
        }
        
        // Calculate the date based on offset and view
        $currentDate = new DateTime();
        
        if ($view === 'day') {
            $currentDate->modify("$offset days");
        } elseif ($view === 'week') {
            $currentDate->modify("$offset weeks");
        } elseif ($view === 'month') {
            $currentDate->modify("$offset months");
        }
        
        // Generate view based on current view type
        $html = $this->generateScheduleView($allSchedules, $view, $currentDate);
        
        echo $html;
        return;
    }
    
    // If not a POST request, redirect to schedule page
    header('Location: /hr/workerSchedules');
    exit;
}

public function getAvailabilitySchedule()
{
    // Get the userID from either GET or POST request
    $userID = null;
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $userID = $_POST['userID'] ?? null;
    } elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $userID = $_GET['userID'] ?? null;
    }
    
    if (!$userID) {
        http_response_code(400);
        echo "UserID is missing.";
        return;
    }
    
    $workerModel = new WorkerModel();
    $workerID = $workerModel->findWorkerIDbyUserID($userID);
    $worker = $workerModel->findWorker($userID);
    
    if (!$workerID) {
        http_response_code(404);
        echo "WorkerID not found for the given UserID.";
        return;
    }
    
    $workingScheduleModel = new WorkingScheduleModel();
    $allSchedules = $workingScheduleModel->getScheduleByWorkerID($workerID);
    
    if ($allSchedules) {
        foreach ($allSchedules as $schedule) {
            // Add role and userID to the schedule object
            $schedule->userID = $worker->userID;
            $schedule->fullName = $worker->fullName;
            $schedule->role = $worker->role;
        }
        
        // Generate default week view
        $currentDate = new DateTime();
        $initialWeekView = $this->generateScheduleView($allSchedules, 'week', $currentDate);
        
        // Pass data to the view
        $this->view('admin/adminWorkerProfileSchedule', [
            'schedule' => $allSchedules,
            'initialView' => $initialWeekView,
            'userID' => $userID,
            'worker' => $worker
        ]);
    } else {
        // Show empty schedule instead of error
        $this->view('admin/adminWorkerProfileSchedule', [
            'schedule' => [],
            'initialView' => '<div class="no-schedule">No schedules found for this worker.</div>',
            'userID' => $userID,
            'worker' => $worker
        ]);
    }
}

private function generateScheduleView($schedules, $view, $currentDate)
{
    $output = '';
    
    if ($view == 'day') {
        // Day view - show single day with hourly slots
        $dayOfWeek = $currentDate->format('l');
        $formattedDate = $currentDate->format('l, d M Y');
        
        $output .= '<table>';
        $output .= '<tr><th>' . $formattedDate . '</th></tr>';
        
        // Filter schedules for current day
        $daySchedules = array_filter($schedules, function($schedule) use ($dayOfWeek) {
            return $schedule->day_of_week == $dayOfWeek;
        });
        
        // Sort schedules by start time
        usort($daySchedules, function($a, $b) {
            return strtotime($a->start_time) - strtotime($b->start_time);
        });
        
        foreach ($daySchedules as $schedule) {
            $output .= '<tr><td>';
            $output .= '<div class="schedule-item">';
            $output .= '<div class="worker-name">' . htmlspecialchars($schedule->fullName) . '</div>';
            $output .= '<div class="worker-role">' . htmlspecialchars($schedule->role) . '</div>';
            $output .= '<div class="work-time">' . $schedule->start_time . ' - ' . $schedule->end_time . '</div>';
            $output .= '</div>';
            $output .= '</td></tr>';
        }
        
        // Ensure minimum number of rows for consistent UI
        $rowCount = count($daySchedules);
        for ($i = $rowCount; $i < 7; $i++) {
            $output .= '<tr><td></td></tr>';
        }
        
        $output .= '</table>';
        
    } elseif ($view == 'week') {
        // Week view - show current week with days
        $weekStart = clone $currentDate;
        $weekStart->modify('monday this week');
        
        // Array to store days of the week with dates
        $days = [];
        for ($i = 0; $i < 7; $i++) {
            $currentDay = clone $weekStart;
            $currentDay->modify("+$i days");
            $days[] = [
                'name' => $currentDay->format('l'),
                'date' => $currentDay->format('d M Y')
            ];
        }
        
        $output .= '<table>';
        $output .= '<tr>';
        foreach ($days as $day) {
            $output .= '<th>' . $day['name'] . ', ' . $day['date'] . '</th>';
        }
        $output .= '</tr>';
        
        // Create an associative array to organize schedules by day
        $schedulesByDay = [
            'Monday' => [],
            'Tuesday' => [],
            'Wednesday' => [],
            'Thursday' => [],
            'Friday' => [],
            'Saturday' => [],
            'Sunday' => []
        ];
        
        // Organize schedules by day of the week
        foreach ($schedules as $schedule) {
            $dayOfWeek = $schedule->day_of_week;
            if (isset($schedulesByDay[$dayOfWeek])) {
                $schedulesByDay[$dayOfWeek][] = $schedule;
            }
        }
        
        // Sort schedules by start time within each day
        foreach ($schedulesByDay as &$daySchedules) {
            usort($daySchedules, function($a, $b) {
                return strtotime($a->start_time) - strtotime($b->start_time);
            });
        }
        
        // Get the maximum number of schedules in any day
        $maxSchedules = 0;
        foreach ($schedulesByDay as $daySchedules) {
            $count = count($daySchedules);
            if ($count > $maxSchedules) {
                $maxSchedules = $count;
            }
        }
        
        // Ensure at least 7 rows (as in the original design)
        $maxSchedules = max($maxSchedules, 7);
        
        // Output schedule cells
        for ($i = 0; $i < $maxSchedules; $i++) {
            $output .= '<tr>';
            foreach ($schedulesByDay as $daySchedules) {
                $output .= '<td>';
                if (isset($daySchedules[$i])) {
                    $schedule = $daySchedules[$i];
                    $output .= '<div class="schedule-item">';
                    $output .= '<div class="worker-name">' . htmlspecialchars($schedule->fullName) . '</div>';
                    $output .= '<div class="worker-role">' . htmlspecialchars($schedule->role) . '</div>';
                    $output .= '<div class="work-time">' . $schedule->start_time . ' - ' . $schedule->end_time . '</div>';
                    $output .= '</div>';
                }
                $output .= '</td>';
            }
            $output .= '</tr>';
        }
        
        $output .= '</table>';
        
    } else {
        // Month view - show calendar view
        $currentMonth = $currentDate->format('F Y');
        $firstDay = new DateTime('first day of ' . $currentMonth);
        $lastDay = new DateTime('last day of ' . $currentMonth);
        
        $firstDayOfWeek = $firstDay->format('N') - 1; // 0 = Monday, 6 = Sunday
        $daysInMonth = $lastDay->format('d');
        
        // Calculate total cells needed (previous month days + current month days)
        $totalCells = $firstDayOfWeek + $daysInMonth;
        $totalRows = ceil($totalCells / 7);
        
        $output .= '<table>';
        $output .= '<tr><th colspan="7">' . $currentMonth . '</th></tr>';
        $output .= '<tr><th>Mon</th><th>Tue</th><th>Wed</th><th>Thu</th><th>Fri</th><th>Sat</th><th>Sun</th></tr>';
        
        // Create day cells
        $day = 1;
        for ($i = 0; $i < $totalRows; $i++) {
            $output .= '<tr>';
            
            for ($j = 0; $j < 7; $j++) {
                $output .= '<td>';
                
                if (($i == 0 && $j < $firstDayOfWeek) || ($day > $daysInMonth)) {
                    // Empty cell (previous or next month)
                } else {
                    // Current month day
                    $output .= '<div class="day-number">' . $day . '</div>';
                    
                    // Get day of week name for this date
                    $dayDate = clone $firstDay;
                    $dayDate->modify('+' . ($day - 1) . ' days');
                    $dayOfWeek = $dayDate->format('l');
                    
                    // Filter schedules for this day
                    $daySchedules = array_filter($schedules, function($schedule) use ($dayOfWeek) {
                        return $schedule->day_of_week == $dayOfWeek;
                    });
                    
                    // Show first schedule for this day (if any)
                    if (!empty($daySchedules)) {
                        $schedule = reset($daySchedules);
                        $output .= '<div class="schedule-item">';
                        $output .= '<div class="worker-name">' . htmlspecialchars($schedule->fullName) . '</div>';
                        $output .= '<div class="worker-role">' . htmlspecialchars($schedule->role) . '</div>';
                        $output .= '<div class="work-time">' . $schedule->start_time . ' - ' . $schedule->end_time . '</div>';
                        $output .= '</div>';
                        
                        // If there are more schedules, add indicator
                        if (count($daySchedules) > 1) {
                            $output .= '<div class="more-schedules">+' . (count($daySchedules) - 1) . ' more</div>';
                        }
                    }
                    
                    $day++;
                }
                
                $output .= '</td>';
            }
            
            $output .= '</tr>';
        }
        
        $output .= '</table>';
    }
    
    return $output;
}

public function updateVerificationStatus() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        header('Content-Type: application/json');
        
        // Get and validate input
        $requestID = isset($_POST['requestID']) ? $_POST['requestID'] : null;
        $status = isset($_POST['status']) ? $_POST['status'] : null;
        
        // Validate input
        if (!$requestID || !$status) {
            echo json_encode([
                'success' => false,
                'message' => 'Missing required parameters.'
            ]);
            return;
        }
        
        // Log the received data for debugging
        error_log("Updating verification status: requestID=$requestID, status=$status");
        
        $requestModel = new VerificationRequestModel();
        $data = ['Status' => $status];
        
        $updated = $requestModel->updateRequest($data, $requestID);
        
        if ($updated) {
            echo json_encode([
                'success' => true,
                'message' => 'Verification status updated successfully.'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Failed to update verification status.'
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


    

    public function workerCertificates(){

        //     $workerModel = new WorkerModel();
        //     $workerDetails = $workerModel->getWorkerCertificates();
        //     $finalData = array_merge($workerDetails, $this->selectedWorkerRole);
        // $this->view('admin/adminWorkerProfile2', ['data'=> $finalData]);
    
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $_POST['worker'] ?? null;
            $worker = json_decode($data, true);

            $workerData = [
                'userID' => $worker['userID'],
                'fullName' => $worker['fullName'],
                'certificates' => $worker['certificates'],
                 'medical' => $worker['medical'],
            ];
            $this->view('admin/adminWorkerProfile2', ['worker'=> $workerData]);
    }
    else {
        http_response_code(400);
        echo "Method not allowed .";
    }
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
    
    public function updateRole(){
        try {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                header('Content-Type: application/json');

                $json = file_get_contents('php://input');
                $data = json_decode($json,true);

                if(!$data || !isset($data['roleID']) || !isset($data['name']) || !isset($data['description'])) {
                    echo json_encode([
                        'success'=> false, 'message' => 'Invalid data provided']);
                        return; 
                }

                $roleID = $data['roleID'];
                $roleData = [
                    'name' => $data['name'],
                    'description' => $data['description'],
                ];

                $workerRoleModel = new WorkerRoleModel();
        $result = $workerRoleModel->updateRole($roleID,$roleData);
        
        if($result){
            echo json_encode([
                'success'=> true ]);
        } else {
            echo json_encode ([
                'success' => false, 'message' => 'Failed to update role']);
        }
            } else {
                echo json_encode([
                    'success' => false, 'message' => 'Invalid request method']);
        }
    } catch (Exception $e) {
        echo json_encode([
            'success' => false, 'message' => $e-> getMessage()
        ]);
        }
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
    require_once "../app/controllers/Complaint.php";
    $complaintController = new Complaint();
    $complaintController->adminIndex();
}

    public function paymentIssues()
    {
        $this->view('admin/adminWorkerInquiries1');
    }

/**
 * Display worker complaints for Admin
 */
public function workerComplaints()
{
    require_once "../app/controllers/WorkerComplaint.php";
    $workerComplaintController = new WorkerComplaint();
    $workerComplaintController->adminIndex();
}

public function bookingReports()
    {
        require_once "../app/controllers/BookingReports.php";
        $bookingReportsController = new BookingReports();
        $bookingReportsController->roleIndex();
    }

    // API endpoints for booking reports
    public function worker_stats()
    {
        require_once "../app/controllers/BookingReports.php";
        $bookingReportsController = new BookingReports();
        $bookingReportsController->worker_stats();
    }

    public function service_stats()
    {
        require_once "../app/controllers/BookingReports.php";
        $bookingReportsController = new BookingReports();
        $bookingReportsController->service_stats();
    }

    public function revenue_trend()
    {
        require_once "../app/controllers/BookingReports.php";
        $bookingReportsController = new BookingReports();
        $bookingReportsController->revenue_trend();
    }

   
}
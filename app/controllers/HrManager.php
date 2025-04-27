<?php

class HrManager extends Controller
{   // public function index()
    // {
    //     $this->view('hr/workerProfiles');
    // }
    private $physicalModel;

    public function __construct()
    {
        $this->physicalModel = new PhysicallyVisitedUnverifiedCustomersModel();
    }

    public function index()
    {
        // Determine greeting based on current time
        $hour = date('H');
        if ($hour >= 5 && $hour < 12) {
            $greeting = "Good Morning";
        } elseif ($hour >= 12 && $hour < 17) {
            $greeting = "Good Afternoon";
        } elseif ($hour >= 17 && $hour < 21) {
            $greeting = "Good Evening";
        } else {
            $greeting = "Good Evening";
        }

        $this->view('hr/hrWelcomeScreen', [
            'greeting' => $greeting
        ]);
    }
    
    public function workerProfiles()
    {
        {
            $workerModel = new WorkerModel();
            $workers = $workerModel->getAllWorkerDetails(); // Fetch all Workers from the database
        }
    
        $this->view('hr/workerProfiles', ['workers' => $workers]);
    }

    // fetch details of worker from verification request table if not the users table
    public function workerDetails()
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $userID = $_POST['workerData'] ?? null;
        // to go back to the verification request page if needed.
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
                    'locationVerificationCode' => 'N/A',
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
            
            
            $userID = isset($_POST['userID']) ? $_POST['userID'] : null; // Get userID from POST data
            // Validate input

                    // Validate input
        if (!$requestID) {
            echo json_encode([
                'success' => false,
                'message' => 'Missing requestID parameters.'
            ]);
            return;
        }
        if (!$status) {
            echo json_encode([
                'success' => false,
                'message' => 'Missing status parameters.'
            ]);
            return;
        }
                    // Validate input
        if (!$userID) {
            echo json_encode([
                'success' => false,
                'message' => 'Missing userID parameters.'
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
            $data = [
                'Status' => $status,
                'userID' => $userID, 
            ]; // Add userID to the data array
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

    // public function availabilitySchedule()
    // {
    //     $this->view('hr/availabilitySchedule');
    // }

    // public function workerSchedules()
    // {
    //     $workingScheduleModel = new WorkingScheduleModel();
    //     $allSchedules = $workingScheduleModel->getAllSchedules();

    //     // to get the userID using workerID
    //     $workerModel = new WorkerModel();

    //     foreach ($allSchedules as $schedule) {
    //         $workerID = $schedule->workerID;
    //         $userID = $workerModel->findUserIDbyWorkerID($workerID);

    //         $worker = $workerModel->findWorker($userID);

    //         // add role and userID to the schedule object
    //         $schedule->userID = $worker->userID;
    //         $schedule->fullName = $worker->fullName;
    //         $schedule->role = $worker->role;
    //     }

    //     error_log("All schedules in controller: " . json_encode($allSchedules));
    //     $this->view('hr/workerSchedules',['schedules'=> $allSchedules]);
    // }

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
        $this->view('hr/availabilitySchedule', [
            'schedule' => $allSchedules,
            'initialView' => $initialWeekView,
            'userID' => $userID,
            'worker' => $worker
        ]);
    } else {
        // Show empty schedule instead of error
        $this->view('hr/availabilitySchedule', [
            'schedule' => [],
            'initialView' => '<div class="no-schedule">No schedules found for this worker.</div>',
            'userID' => $userID,
            'worker' => $worker
        ]);
    }
}

// function to get all the schedules in the schedule talbe
    public function workerSchedules()
{
    // Get all worker schedules
    $workingScheduleModel = new WorkingScheduleModel();
    $allSchedules = $workingScheduleModel->getAllSchedules();
    
    // Get worker details for each schedule
    $workerModel = new WorkerModel();
    
    foreach ($allSchedules as $schedule) {
        $workerID = $schedule->workerID;
        $userID = $workerModel->findUserIDbyWorkerID($workerID);
        
        $worker = $workerModel->findWorker($userID);
        
        // Add role and userID to the schedule object
        $schedule->userID = $worker->userID;
        $schedule->fullName = $worker->fullName;
        $schedule->role = $worker->role;
    }
    
    error_log("All schedules in controller: " . json_encode($allSchedules));
    
      // Generate default week view
      $currentDate = new DateTime();
      $initialWeekView = $this->generateScheduleView($allSchedules, 'week', $currentDate);
      
      // Pass both the schedules and the initial view to the view
      $this->view('hr/workerSchedules', [
          'schedules' => $allSchedules,
          'initialView' => $initialWeekView
      ]);
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

// public function getScheduleView()
// {
//     if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//         $view = $_POST['view'] ?? 'week';
//         $offset = (int)($_POST['offset'] ?? 0);
        
//         $workingScheduleModel = new WorkingScheduleModel();
//         $allSchedules = $workingScheduleModel->getAllSchedules();
        
//         // to get the userID using workerID
//         $workerModel = new WorkerModel();
        
//         foreach ($allSchedules as $schedule) {
//             $workerID = $schedule->workerID;
//             $userID = $workerModel->findUserIDbyWorkerID($workerID);
            
//             $worker = $workerModel->findWorker($userID);
            
//             // add role and userID to the schedule object
//             $schedule->userID = $worker->userID;
//             $schedule->fullName = $worker->fullName;
//             $schedule->role = $worker->role;
//         }
        
//         // Calculate the date based on offset and view
//         $currentDate = new DateTime();
        
//         if ($view === 'day') {
//             $currentDate->modify("$offset days");
//         } elseif ($view === 'week') {
//             $currentDate->modify("$offset weeks");
//         } elseif ($view === 'month') {
//             $currentDate->modify("$offset months");
//         }
        
//         // Generate view based on current view type
//         $html = $this->generateScheduleView($allSchedules, $view, $currentDate);
        
//         echo $html;
//         return;
//     }
    
//     // If not a POST request, redirect to schedule page
//     header('Location: /hr/workerSchedules');
//     exit;
// }

    public function verificationRequests()
    {
         $verificationRequestsModel = new VerificationRequestModel();
         $pendingRequests = $verificationRequestsModel->getPendingOrRejectedRequests();
         if ($pendingRequests){
         $this->view('hr/verificationRequests', ['verificationRequests' => $pendingRequests]);
         } else {
            $this->view('hr/verificationRequests', ['verificationRequests' => []]);
         }

    }

    //Maneth's deleopings

    public function managePhysicalVerifications()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['add_record'])) {
                $nic = $_POST['nic'];
                $email = $_POST['email'];
                $verificationCode = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);

                MailHelper::sendInLocationCode($email,$verificationCode);

                $this->physicalModel->insertRecord($nic, $email, $verificationCode);

                $this->view('hr/managePhysicalVerifications', [
                    'records' => $this->physicalModel->getAllRecords(),
                    'message' => "In-Location reference code generated successfully & mailed to $email! Reference Code: $verificationCode",
                ]);
                return;
            }

            if (isset($_POST['check_code'])) {
                $code = $_POST['verification_code'];
                $foundRecord = $this->physicalModel->findByVerificationCode($code);

                $message = $foundRecord
                    ? "Worker found: NIC {$foundRecord->nic}, Email {$foundRecord->email}"
                    : "No worker found with the provided code :(";

                $this->view('hr/managePhysicalVerifications', [
                    'records' => $this->physicalModel->getAllRecords(),
                    'check_message' => $message,
                ]);
                return;
            }

            if (isset($_POST['delete_record'])) {
                $nic = $_POST['nic_to_delete'];
    
                $deleteSuccess = $this->physicalModel->deleteByNIC($nic);
    
                $message = $deleteSuccess
                    ? "Record with NIC {$nic} deleted successfully!"
                    : "No record found with NIC {$nic} :(";
    
                $this->view('hr/managePhysicalVerifications', [
                    'records' => $this->physicalModel->getAllRecords(),
                    'delete_message' => $message,
                ]);
                return;
            }
        }

        // Default page load
        $this->view('hr/managePhysicalVerifications', [
            'records' => $this->physicalModel->getAllRecords()
        ]);
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


    public function workerMatching()
    {
        error_log("workerMatching function called");
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            error_log("POST request received");
            
            $workerID = $_POST['workerID'] ?? null;
            $customerID = $_POST['customerID'] ?? null;
            
            error_log("Worker ID: " . ($workerID ?? 'null'));
            error_log("Customer ID: " . ($customerID ?? 'null'));
    
            // Set content type to text/plain for simplicity
            header('Content-Type: text/plain');
            
            if ($workerID && $customerID) {
                // Here you would typically add code to save the match in your database
                // For example:
                // $matchingModel = new WorkerCustomerMatchModel();
                // $result = $matchingModel->createMatch($workerID, $customerID);
                
                error_log("Match successful for Worker $workerID and Customer $customerID");
                echo "Match successful";
                return;
            } else {
                error_log("Missing workerID or customerID");
                http_response_code(400);
                echo "WorkerID or CustomerID is missing.";
                return;
            }
        } else {
            error_log("Method not allowed: " . $_SERVER['REQUEST_METHOD']);
            http_response_code(405);
            echo "Method not allowed.";
            return;
        }
    }
    //public function workerInquiries()
    //{
    //    $this->view('hr/workerInquiries');
    //}

    /**
     * Display worker complaints for HR manager
     */
    public function workerComplaints()
    {
        require_once "../app/controllers/WorkerComplaint.php";
        $workerComplaintController = new WorkerComplaint();
        $workerComplaintController->hrIndex();
    }
}
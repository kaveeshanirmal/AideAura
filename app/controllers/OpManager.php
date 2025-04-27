<?php

class OpManager extends Controller
{
    public function index($a = '', $b = '', $c = '')
    {
        $this->view('opm/opmWorkerInquiries');
    }


    public function customers(){
        $customerModel = new CustomerModel();
        $customers = $customerModel->getAllCustomerDetails(); // Fetch all Workers from the database
        error_log("Workers in controller: " . json_encode($customers));
        $this->view('opm/opmCustomerDetails', ['customers' => $customers]);
       
    }
    
    public function searchCustomers(){
            header('Content-Type: application/json');
        
            try {
                // Decode the JSON input from the request body
                $data = json_decode(file_get_contents('php://input'), true);
        
                // Validate and extract filters from the input
                $filters = [
                    'customerID' => !empty($data['customerID']) ? trim($data['customerID']) : null
                ];
        
                // Ensure at least one filter is provided
                if (empty($filters['customerID'])) {
                    throw new Exception('CustomerID must be provided.');
                }
        
    
                $customerModel = new CustomerModel();
                $customers = $customerModel->searchCustomer($filters['customerID']);
        
                echo json_encode([
                    'success' => true,
                    'customers' => $customers
                ]);
            } catch (Exception $e) {
                http_response_code(400); // Bad request
                echo json_encode([
                    'success' => false,
                    'message' => $e->getMessage()
                ]);
            }
    // I was add some comments to the code
            exit; // Ensure no further output
        }
    

    public function bookingDetails()
    {
        $bookingModel = new BookingModel();
        $bookingDetails = $bookingModel->getBookingAllDetails();
        $this->view('opm/opmBookingDetails', ['bookingDetails'=>  $bookingDetails]);
    }

    // public function workerSchedules()
    // {
    //     $this->view('opm/workerSchedules');
    // }

    public function searchBookingDetails() 
    {
        header('Content-Type: application/json');
    
        try {
            // Decode the JSON input from the request body
            $data = json_decode(file_get_contents('php://input'), true);
    
            // Validate and extract filters from the input
            $filters = [
                'bookingID' => !empty($data['bookingID']) ? trim($data['bookingID']) : null,
                'customerID' => !empty($data['customerID']) ? trim($data['customerID']) : null,
                'workerID' => !empty($data['workerID']) ? trim($data['workerID']) : null,
            ];
              // Ensure at least one filter is provided
            if (empty($filters['bookingID']) && empty($filters['customerID'])  && empty($filters['workerID'])) {
                throw new Exception('At least one of BookinID or CustomerID or WorkerID must be provided.');
            }
    
            $bookingModel = new BookingModel();
            $bookingDetails = $bookingModel->searchBookingDetails($filters);
    
            echo json_encode([
                'success' => true,
                'bookingDetails' => $bookingDetails
            ]);
        } catch (Exception $e) {
            http_response_code(400); // Bad request
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
// I was add some comments to the code
        exit; // Ensure no further output
    }


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
          $this->view('opm/workerSchedules', [
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
        header('Location: /opm/workerSchedules');
        exit;
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

    public function workerInquiries()
    {   
        require_once "../app/controllers/Complaint.php";
        // Load the complaints controller
        $complaintsController = new Complaint();
        // Call the operational manager's complaint index
        $complaintsController->opIndex();
    }
    
}

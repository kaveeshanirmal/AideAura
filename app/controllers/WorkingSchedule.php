<?php

class WorkingSchedule extends Controller
{
    private $scheduleModel;

    public function __construct()
    {
        if (!isset($_SESSION['workerID'])) {
            header('Location: ' . ROOT . '/login');
            exit;
        }
        
        $this->scheduleModel = new WorkingScheduleModel();
    }

    public function index()
    {
        $workerId = $_SESSION['workerID'];
        error_log("Loading schedules for worker ID: " . $workerId);
        
        $schedules = $this->scheduleModel->getScheduleByWorkerId($workerId);
        
        $data = [
            'schedules' => $schedules
        ];

        $this->view('workingSchedule', $data);
    }

    public function getSchedule()
    {
        error_log("getSchedule method called");
        error_log("Request URI: " . $_SERVER['REQUEST_URI']);
        error_log("Request Method: " . $_SERVER['REQUEST_METHOD']);
        
        header('Content-Type: application/json');

        if (!isset($_SESSION['workerID'])) {
            error_log("No workerID in session");
            http_response_code(401);
            echo json_encode(['error' => 'Unauthorized']);
            return;
        }

        try {
            $workerId = $_SESSION['workerID'];
            error_log("Fetching schedules for worker ID: " . $workerId);
            
            $schedules = $this->scheduleModel->getScheduleByWorkerId($workerId);
            error_log("Retrieved schedules: " . print_r($schedules, true));
            
            echo json_encode($schedules ?: []);
        } catch (Exception $e) {
            error_log("Error in getSchedule: " . $e->getMessage());
            http_response_code(500);
            echo json_encode(['error' => 'Server error']);
        }
    }

    public function saveSchedule()
    {
        header('Content-Type: application/json');

        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('Invalid request method');
            }

            if (!isset($_SESSION['workerID'])) {
                throw new Exception('Not authenticated');
            }

            $rawData = file_get_contents('php://input');
            $data = json_decode($rawData, true);
            error_log('Received data: ' . print_r($data, true));

            if (!isset($data['schedules']) || !is_array($data['schedules'])) {
                throw new Exception('Invalid data format');
            }

            $workerId = $_SESSION['workerID'];
            $success = true;
            $messages = [];

            // Instead of deleting all schedules, update existing ones and add new ones
            foreach ($data['schedules'] as $schedule) {
                $scheduleData = [
                    'workerId' => $workerId,
                    'days_of_week' => $schedule['days_of_week'],
                    'startTime' => $schedule['startTime'],
                    'endTime' => $schedule['endTime']
                ];

                // If schedule has an ID, update it; otherwise, add new
                if (isset($schedule['scheduleID'])) {
                    error_log("Updating existing schedule ID: " . $schedule['scheduleID']);
                    if (!$this->scheduleModel->updateSchedule($schedule['scheduleID'], $scheduleData)) {
                        $success = false;
                        $messages[] = "Failed to update schedule for {$schedule['days_of_week']}";
                    }
                } else {
                    error_log("Adding new schedule for day: " . $schedule['days_of_week']);
                    if (!$this->scheduleModel->addSchedule($scheduleData)) {
                        $success = false;
                        $messages[] = "Failed to add schedule for {$schedule['days_of_week']}";
                    }
                }
            }

            echo json_encode([
                'success' => $success,
                'messages' => $messages
            ]);
        } catch (Exception $e) {
            error_log("Error in saveSchedule: " . $e->getMessage());
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function deleteSchedule()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['error' => 'Invalid request method']);
            return;
        }

        $data = json_decode(file_get_contents('php://input'), true);
        $workerId = $_SESSION['workerID'];
        
        if ($this->scheduleModel->deleteSchedule($data['scheduleId'], $workerId)) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['error' => 'Failed to delete schedule']);
        }
    }

    public function testDatabase()
    {
        try {
            $this->scheduleModel->setTable('workingschedule');
            $query = "SHOW TABLES";
            $result = $this->scheduleModel->get_all($query);
            
            echo "<pre>";
            print_r($result);
            echo "</pre>";
            
            echo "Database connection successful!";
            error_log("Database test result: " . print_r($result, true));
        } catch (Exception $e) {
            echo "Database error: " . $e->getMessage();
            error_log("Database test error: " . $e->getMessage());
        }
    }
}
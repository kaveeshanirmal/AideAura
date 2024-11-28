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
        error_reporting(E_ALL);
        ini_set('display_errors', 1);

        try {
            // Get and validate input
            $rawData = file_get_contents('php://input');
            error_log("Raw input: " . $rawData); // Debug log

            if (empty($rawData)) {
                throw new Exception('No data received');
            }

            $data = json_decode($rawData, true);
            error_log("Decoded data: " . print_r($data, true)); // Debug log

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new Exception('Invalid JSON: ' . json_last_error_msg());
            }

            if (!isset($data['schedules']) || !is_array($data['schedules'])) {
                throw new Exception('Invalid data format: schedules array missing');
            }

            if (!isset($_SESSION['workerID'])) {
                throw new Exception('Worker ID not found in session');
            }

            $workerId = $_SESSION['workerID'];
            $success = true;
            $messages = [];

            foreach ($data['schedules'] as $schedule) {
                // Validate schedule data
                if (!isset($schedule['days_of_week'], $schedule['startTime'], $schedule['endTime'])) {
                    throw new Exception('Missing required fields in schedule');
                }

                $scheduleData = [
                    'workerId' => $workerId,
                    'days_of_week' => $schedule['days_of_week'],
                    'startTime' => $schedule['startTime'],
                    'endTime' => $schedule['endTime']
                ];

                error_log("Processing schedule: " . print_r($scheduleData, true)); // Debug log

                // Add or update schedule
                if (!empty($schedule['scheduleID'])) {
                    if (!$this->scheduleModel->updateSchedule($schedule['scheduleID'], $scheduleData)) {
                        $success = false;
                        $messages[] = "Failed to update schedule for {$schedule['days_of_week']}";
                    }
                } else {
                    if (!$this->scheduleModel->addSchedule($scheduleData)) {
                        $success = false;
                        $messages[] = "Failed to add schedule for {$schedule['days_of_week']}";
                    }
                }
            }

            $response = [
                'success' => $success,
                'message' => $success ? 'Schedules saved successfully' : 'Failed to save some schedules',
                'messages' => $messages
            ];

            error_log("Sending response: " . json_encode($response)); // Debug log
            echo json_encode($response);

        } catch (Exception $e) {
            error_log("Error in saveSchedule: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            
            $response = [
                'success' => false,
                'message' => $e->getMessage()
            ];
            
            http_response_code(500);
            echo json_encode($response);
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
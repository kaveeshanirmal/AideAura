<?php
class WorkingScheduleModel
{
    use Model;
    
    private $validDays = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
    
    public function __construct()
    {
        $this->setTable('workingschedule');
    }
    
    public function addSchedule($data)
    {
        try {
            // Validate data
            if (!isset($data['workerId'], $data['days_of_week'], $data['startTime'], $data['endTime'])) {
                error_log("Missing required fields for schedule");
                return false;
            }
            
            // Validate day of week - capitalize first letter to match enum format
            $day = ucfirst(strtolower($data['days_of_week']));
            if (!in_array($day, $this->validDays)) {
                error_log("Invalid day of week: " . $day);
                return false;
            }
            
            $query = "INSERT INTO workingschedule 
                     (workerID, day_of_week, start_time, end_time) 
                     VALUES 
                     (:workerId, :day_of_week, :start_time, :end_time)";
                     
            $params = [
                ':workerId' => $data['workerId'],
                ':day_of_week' => $day,
                ':start_time' => $data['startTime'],
                ':end_time' => $data['endTime']
            ];
            
            error_log("Adding new schedule for worker: " . $data['workerId']);
            error_log("Schedule data: " . print_r($params, true));
            
            return $this->query($query, $params);
        } catch (Exception $e) {
            error_log("Error adding schedule: " . $e->getMessage());
            return false;
        }
    }
    
    public function getAllSchedules()
    {
        return $this->all();
    }

    
    public function getScheduleByWorkerId($workerID)
    {
        return $this->get($workerID, 'workerID');
    }
    
    // public function getScheduleByWorkerId($workerID)
    // {
    //     try {
    //         $query = "SELECT scheduleID, workerID, day_of_week as days_of_week, 
    //                   start_time as startTime, end_time as endTime 
    //                   FROM workingschedule 
    //                   WHERE workerID = :workerID 
    //                   ORDER BY FIELD(day_of_week, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday')";
                     
    //         return $this->get_all($query, [':workerID' => $workerID]);
    //     } catch (Exception $e) {
    //         error_log("Error getting worker schedules: " . $e->getMessage());
    //         return [];
    //     }
    // }
    
    public function deleteSchedule($scheduleId, $workerId)
    {
        try {
            $query = "DELETE FROM workingschedule WHERE scheduleID = :scheduleId AND workerID = :workerId";
            
            return $this->query($query, [
                ':scheduleId' => $scheduleId,
                ':workerId' => $workerId
            ]);
        } catch (Exception $e) {
            error_log("Error deleting schedule: " . $e->getMessage());
            return false;
        }
    }
    
    public function updateSchedule($scheduleId, $data)
    {
        try {
            // Validate data
            if (!isset($data['workerId'], $data['days_of_week'], $data['startTime'], $data['endTime'])) {
                error_log("Missing required fields for schedule update");
                return false;
            }
            
            // Validate day of week - capitalize first letter to match enum format
            $day = ucfirst(strtolower($data['days_of_week']));
            if (!in_array($day, $this->validDays)) {
                error_log("Invalid day of week for update: " . $day);
                return false;
            }
            
            $query = "UPDATE workingschedule SET 
                     day_of_week = :day_of_week, 
                     start_time = :start_time, 
                     end_time = :end_time 
                     WHERE scheduleID = :scheduleID 
                     AND workerID = :workerId";
                     
            $params = [
                ':day_of_week' => $day,
                ':start_time' => $data['startTime'],
                ':end_time' => $data['endTime'],
                ':scheduleID' => $scheduleId,
                ':workerId' => $data['workerId']
            ];
            
            error_log("Updating schedule with ID: " . $scheduleId);
            error_log("Update data: " . print_r($params, true));
            
            return $this->query($query, $params);
        } catch (Exception $e) {
            error_log("Error updating schedule: " . $e->getMessage());
            return false;
        }
    }
    
    // A helper function to check if a day already exists for a worker
    public function dayExists($workerId, $day, $excludeScheduleId = null)
    {
        try {
            // Capitalize first letter to match enum format
            $day = ucfirst(strtolower($day));
            
            $query = "SELECT COUNT(*) as count FROM workingschedule 
                     WHERE workerID = :workerId AND day_of_week = :day";
            $params = [':workerId' => $workerId, ':day' => $day];
            
            // If we're updating an existing schedule, exclude it from the check
            if ($excludeScheduleId) {
                $query .= " AND scheduleID != :scheduleId";
                $params[':scheduleId'] = $excludeScheduleId;
            }
            
            $result = $this->get_row($query, $params);
            return $result && $result->count > 0;
        } catch (Exception $e) {
            error_log("Error checking day existence: " . $e->getMessage());
            return false;
        }
    }
}
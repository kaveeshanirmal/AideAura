<?php

class WorkingScheduleModel
{
    use Model;

    private $validDays = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];

    public function __construct()
    {
        $this->setTable('workingschedule');
    }

    public function addSchedule($data)
    {
        try {
            $query = "INSERT INTO workingschedule 
                      (workerId, days_of_week, startTime, endTime) 
                      VALUES 
                      (:workerId, :days_of_week, :startTime, :endTime)";

            $params = [
                ':workerId' => $data['workerId'],
                ':days_of_week' => $data['days_of_week'],
                ':startTime' => $data['startTime'],
                ':endTime' => $data['endTime']
            ];

            error_log("Adding new schedule for worker: " . $data['workerId']);
            error_log("Schedule data: " . print_r($data, true));

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

     


    public function getScheduleByWorkerId($workerId)
    {
        $query = "SELECT * FROM workingschedule WHERE workerID = :workerId ORDER BY FIELD(days_of_week, 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday')";
        return $this->get_all($query, ['workerId' => $workerId]);
    }

    public function deleteSchedule($scheduleId, $workerId)
    {
        $query = "DELETE FROM workingschedule WHERE scheduleID = :scheduleId AND workerID = :workerId";
        return $this->query($query, [
            'scheduleId' => $scheduleId,
            'workerId' => $workerId
        ]);
    }

    public function updateSchedule($scheduleId, $data)
    {
        try {
            $query = "UPDATE workingschedule SET 
                      days_of_week = :days_of_week,
                      startTime = :startTime,
                      endTime = :endTime
                      WHERE scheduleID = :scheduleID 
                      AND workerId = :workerId";

            $params = [
                ':days_of_week' => $data['days_of_week'],
                ':startTime' => $data['startTime'],
                ':endTime' => $data['endTime'],
                ':scheduleID' => $scheduleId,
                ':workerId' => $data['workerId']
            ];

            error_log("Updating schedule with ID: " . $scheduleId);
            error_log("Update data: " . print_r($data, true));

            return $this->query($query, $params);
        } catch (Exception $e) {
            error_log("Error updating schedule: " . $e->getMessage());
            return false;
        }
    }
}
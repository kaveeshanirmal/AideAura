<?php

class SearchForWorker extends Controller
{
    use Database;
    public function index($a = '', $b = '', $c = '')
    {
        $this->view('searchAlgorithm');
    }

    public function find()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Get current system day
            $day = date('l'); // Returns full weekday name (Monday, Tuesday, etc.)

            // Get input values
            $startTime = $_POST['start-time'];
            $endTime = $_POST['end-time'];
            $location = $_POST['location'];
            $jobType = $_POST['job-type'];

            // SQL query to find workers who match the criteria
            $query = "SELECT 
                    w.workerID, u.username, u.firstName, u.lastName, u.phone, u.email, 
                    w.profileImage, w.address, j.name AS jobRole
                  FROM worker w
                  JOIN users u ON w.userID = u.userID
                  JOIN worker_roles wr ON w.workerID = wr.workerID
                  JOIN jobroles j ON wr.roleID = j.roleID
                  JOIN workingschedule ws ON w.workerID = ws.workerID
                  WHERE w.isVerified = 1
                  AND j.name = :jobType
                  AND w.address LIKE :location
                  AND ws.day_of_week = :day
                  AND ws.start_time <= :startTime
                  AND ws.end_time >= :endTime";

            // Fetch all matching workers using get_all()
            $data = $this->get_all($query, [
                'jobType'   => $jobType,
                'location'  => '%' . $location . '%', // Use LIKE for partial match
                'day'       => $day,
                'startTime' => $startTime,
                'endTime'   => $endTime
            ]);

            // Store results in session
            $_SESSION['workers'] = $data;

            // Redirect to workerFound view
            header("Location: " . ROOT . "/public/SearchForWorker/workerFound");
            exit;
        }
    }


    public function workerFound()
    {
        // Retrieve workers from session or default to an empty array
        $workers = $_SESSION['workers'] ?? [];

        // Clear session data after use
        unset($_SESSION['workers']);

        // Pass workers data to the view
        $this->view('workerFound', ['workers' => $workers]);
    }



}


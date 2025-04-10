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
        $date = $_SESSION['booking_info']['preferred_date'] ?? '';
// Extract weekday from the date
        $weekday = '';
        if ($date) {
            $dateObj = new DateTime($date);
            $weekday = $dateObj->format('l');
        }

        // Get input values
        $startTime = $_SESSION['booking_info']['arrival_time'] ?? '';
        // Ensure time is in HH:MM:SS format
        if ($startTime && substr_count($startTime, ':') === 1) {
            $startTime .= ':00';
        }

        $location = $_SESSION['booking_info']['service_location'] ?? '';
        $locationParts = explode(',', $location);
        $locationParts = array_map('trim', $locationParts);

        // Extract district name
        $district = '';
        foreach ($locationParts as $part) {
            if (strpos($part, 'District') !== false) {
                $district = str_replace(' District', '', $part);
                break;
            }
        }
        $locationPattern = '%' . $district . '%';

        $jobType = $_SESSION['booking_info']['serviceType'] ?? '';
        $gender = $_SESSION['booking_info']['gender'] ?? '';

        // SQL query to find verified workers who match the criteria

//      Weighted score calculation to sort the workers
//      Weighted Score =
//      (avg_rating / 5) * 60 +          -- 60% weight for rating quality
//      (LOG(total_reviews + 1) * 20) +  -- 20% weight for review count (log-scaled for fairness)
//      (IF last_activity < 7 days ago: 20 ELSE 0) -- 20% for recent activity


        $query = "SELECT
            vw.workerID, u.username, u.firstName, u.lastName, vw.gender, u.phone AS phone, u.email,
            vw.profileImage, vw.address, j.name AS jobRole, w.availability_status,
            ((wst.avg_rating / 5) * 60) + 
            (LOG(wst.total_reviews + 1) * 20) + 
            (IF(wst.last_activity >= NOW() - INTERVAL 7 DAY, 20, 0)) AS score
          FROM verified_workers vw
          JOIN worker w ON vw.workerID = w.workerID
          JOIN users u ON w.userID = u.userID
          JOIN worker_roles wr ON vw.workerID = wr.workerID
          JOIN jobroles j ON wr.roleID = j.roleID
          JOIN workingschedule ws ON vw.workerID = ws.workerID
          JOIN worker_stats wst ON w.workerID = wst.workerID
          WHERE w.availability_status = 'online'
          AND vw.gender = :gender
          AND j.name = :jobType
          AND vw.address LIKE :location
          AND ws.day_of_week = :weekday
          AND ws.start_time <= TIME(:startTime)
          AND ws.end_time >= TIME(:startTime)
          ORDER BY score DESC";

        // Fetch all matching workers using get_all()
        $data = $this->get_all($query, [
            'gender'    => $gender,
            'jobType'   => $jobType,
            'location'  => $locationPattern,
            'weekday'   => $weekday,
            'startTime' => $startTime,
            'endTime'   => $startTime
        ]);

        // Store results in session
        $_SESSION['workers'] = $data;

        // Redirect to workerFound view
        header("Location: " . ROOT . "/public/SearchForWorker/workerFound");
        exit;

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

    public function processing()
    {
        $this->view('EmployeeFindingScreen');
    }
}


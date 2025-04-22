<?php

class SearchForWorker extends Controller
{
    use Model;
    private $workerModel;

    public function __construct()
    {
        $this->workerModel = new WorkerModel();
    }
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
            (IF(wst.last_activity >= NOW() - INTERVAL 7 DAY, 20, 0)) AS score,
            wst.avg_rating, wst.total_reviews
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
          AND vw.workLocations LIKE :location
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

        // For all found workers check whether they are already booked
        foreach ($data as $key => $worker) {
            $workerID = $worker->workerID;
            $isBooked = $this->workerModel->isBooked($workerID, $date, $startTime);
            if ($isBooked) {
                unset($data[$key]); // Remove booked workers from the result
            }
        }
        // reindex the array
        $data = array_values($data);
        // Store results in session
        $_SESSION['workers'] = $data;

        // Redirect to workerFound view
        header("Location: " . ROOT . "/public/SearchForWorker/searchResults");
        exit;

    }

    public function searchResults()
    {
        // Retrieve workers from session or default to an empty array
        $workers = $_SESSION['workers'] ?? [];

        // Pass the first worker to the view if set
        $firstWorker = !empty($workers) ? $workers[0] : null;
        $this->view('workerFound', ['worker' => $firstWorker]);
    }

    public function processing()
    {
        $this->view('EmployeeFindingScreen');
    }

    public function browseWorkers()
    {
        $this->view('BrowseWorker');
    }
}


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
        // check if the session is set
        if (!isset($_SESSION['booking_info'])) {
            // Redirect to the booking page if session is not set
            header("Location: " . ROOT . "/public/selectService");
            exit;
        }

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
        // Set job type detached from booking info
        $_SESSION['serviceType'] = $jobType;

        $gender = $_SESSION['booking_info']['gender'] ?? '';

        $data =  [
            'gender'    => $gender,
            'jobType'   => $jobType,
            'location'  => $locationPattern,
            'weekday'   => $weekday,
            'startTime' => $startTime,
            'endTime'   => $startTime
        ];

        $results = $this->findExactMatch($data);

        // Check if any workers were found
        if (empty($results)) {
            // No workers found, redirect to noMatchApology view
            header("Location: " . ROOT . "/public/SearchForWorker/noWorkersFound");
            exit;
        }

        // For all found workers check whether they are already booked
        foreach ($results as $key => $worker) {
            error_log("Worker ID: " . $worker->workerID);
            $workerID = $worker->workerID;
            $isBooked = $this->workerModel->isBooked($workerID, $date, $startTime);
            if ($isBooked) {
                unset($results[$key]); // Remove booked workers from the result
            }
        }
        // reindex the array
        $results = array_values($results);
        // Store results in session
        $_SESSION['workers'] = $results;

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
        // Check if the session is set
        if (!isset($_SESSION['serviceType'])) {
            error_log("Session not set for browseWorkers");
            // Redirect to the booking page if session is not set
            header("Location: " . ROOT . "/public/selectService");
            exit;
        }
        $data = ['jobType' => $_SESSION['booking_info']['serviceType'] ?? ''];
        $results = $this->findAlternatives($data);

        $this->view('browseWorkers', ['workers' => $results]);
    }

    public function waitingForResponse()
    {
        $this->view('countdownScreen');
    }

    public function noWorkersFound()
    {
        $this->view('noMatchApology', ['redirectUrl' => ROOT . '/public/SearchForWorker/browseWorkers']);
    }

    public function noAlternativesFound()
    {
        $this->view('noMatchApology', ['redirectUrl' => ROOT . '/public/']);
    }

    public function findExactMatch($data)
    {
        // SQL query to find verified workers who match the criteria

//      Weighted score calculation to sort the workers
//      Weighted Score =
//      (avg_rating / 5) * 60 +          -- 45% weight for rating quality
//      (completion_rate / 100) * 25 +   -- 25% weight for completion rate
//      LEAST(LOG(wst.total_reviews + 1) / LOG(100), 1) * 20 +  -- 20% weight for review count (log-scaled for fairness)
//      (IF last_activity < 7 days ago: 20 ELSE 0) -- 10% for recent activity


        $query = "SELECT
            vw.workerID, u.username, u.firstName, u.lastName, vw.gender, u.phone AS phone, u.email,
            w.profileImage, vw.address, j.name AS jobRole, w.availability_status,
            ((wst.avg_rating / 5) * 45) + 
            ((wst.completion_rate / 100) * 25) +
            LEAST(LOG(wst.total_reviews + 1) / LOG(100), 1) * 20 + 
            (IF(wst.last_activity >= NOW() - INTERVAL 7 DAY, 10, 0)) AS score,
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
          GROUP BY vw.workerID
          ORDER BY score DESC";

        return $this->get_all($query, $data);
    }

    public function findAlternatives($data)
    {
        $query = "SELECT
            vw.workerID, u.username, u.firstName, u.lastName, vw.gender, u.phone AS phone, u.email,
            w.profileImage, vw.address, j.name AS jobRole, w.availability_status, vw.workLocations,
            ((wst.avg_rating / 5) * 45) + 
            ((wst.completion_rate / 100) * 25) +
            LEAST(LOG(wst.total_reviews + 1) / LOG(100), 1) * 20 + 
            (IF(wst.last_activity >= NOW() - INTERVAL 7 DAY, 10, 0)) AS score,
            wst.avg_rating, wst.total_reviews
          FROM verified_workers vw
          JOIN worker w ON vw.workerID = w.workerID
          JOIN users u ON w.userID = u.userID
          JOIN worker_roles wr ON vw.workerID = wr.workerID
          JOIN jobroles j ON wr.roleID = j.roleID
          JOIN workingschedule ws ON vw.workerID = ws.workerID
          JOIN worker_stats wst ON w.workerID = wst.workerID
          WHERE w.availability_status = 'online'
          AND j.name = :jobType
          GROUP BY vw.workerID
          ORDER BY score DESC";

        return $this->get_all($query, $data);
    }
}


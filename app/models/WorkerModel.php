<?php
class WorkerModel 
{
    use Model;
    private $certificateData = [];
    private $bookingModel;

    public function __construct()
    {
        $this->setTable('worker');
        $this->bookingModel = new BookingModel();
    }

    public function getAllWorkers(){
        $this->setTable('worker');
        return $this->all();
    }

    public function getWorkerDetails($id){
        // to find the workerID of worker using his userID
        $this->setTable('worker');
        $worker = $this->find($id, 'userID');
        if($worker){
            $workerID = $worker->workerID;
            $this->setTable('verification_requests');
            $workerDetails = $this->find($workerID,'workerID');
            if($workerDetails){
                // to get the role
                $userModel = new UserModel();

                $data = [
                'workerID' => $workerDetails->workerID,
                'fullName' => $workerDetails->full_name,
                'requestID'=> $workerDetails->requestID,

                'userID' => $id,
                'email' => $workerDetails->email,
                'role'=> $userModel->getWorkerRole($id),
                'username' => $workerDetails->username,
                'certificates' => $workerDetails-> certificates_path,
                'medical' => $workerDetails-> medical_path,

                'Nationality' => $workerDetails->nationality,
                'Gender' => $workerDetails->gender,
                'Contact' => $workerDetails->phone_number,
                'NIC'=> $workerDetails->nic,
                'Age'=> $workerDetails->age_range,
                'ServiceType' => $workerDetails->service_type,
                'SpokenLanguages'=> $workerDetails->spokenLanguages,
                'WorkLocations' => $workerDetails->workLocations,
                'ExperienceLevel' => $workerDetails->experience_level,
                'AllergiesOrPhysicalLimitations' => $workerDetails->allergies,
                'Description' => $workerDetails->description,
                'HomeTown' => $workerDetails->hometown,
                'BankNameAndBranchCode' => $workerDetails->bankNameCode,
                'BankAccountNumber' => $workerDetails->accountNumber,
                'WorkingWeekDays'=> $workerDetails->working_weekdays,
                'WorkingWeekEnds'=> $workerDetails->working_weekends,
                'Notes' => $workerDetails->special_notes,
                'Status' => $workerDetails->status,
                 ];
            return $data;
            } else {
                return false;
            }
        }

    }

    public function findWorkerIDbyUserID($id){
        $this->setTable('worker');
        $worker = $this->find($id, 'userID');
        if($worker){
            return $worker->workerID;
        }
        return null;
    }

    public function findUserIDbyWorkerID($id){
        $this->setTable('worker');
        $worker = $this->find($id, 'workerID');
        if ($worker) {
            return $worker->userID; // Return the userID associated with the workerID
        }
        return null; // Return null if not found
    }

    // function to get worker details from users table using given userID
    public function findWorker($id){
        $this->setTable('users');
        $worker = $this->find($id, 'userID');
    
        if ($worker) {
            $worker->fullName = $worker->firstName . ' ' . $worker->lastName;
    
            // Assign worker role
            $userModel = new UserModel();
            $worker->role = $userModel->getWorkerRole($id);
            
            return (object)$worker;
        }
    
        return false;
    }
    
    public function getWorkerCertificates(){
        // $this->setTable('verification_requests');
        // $workerDetails = $this->find($id,'workerID');
        // if($workerDetails){
        //     $data = [
        //     'fullName' => $workerDetails->full_name,
        //     'certificates' => $workerDetails-> certificates_path,
        //     'medical' => $workerDetails-> medical_path,
        // //     ];
            return $this->certificateData;
    }

    public function isBooked($workerID, $date, $startTime)
    {
        $this->setTable('bookings');
        $query = "SELECT * FROM bookings WHERE workerID = :workerID AND bookingDate = :bookingDate AND (startTime <= :startTime) AND (status = 'confirmed' OR status = 'accepted')";
        return $this->get_all($query, [
            'workerID' => $workerID,
            'bookingDate' => $date,
            'startTime' => $startTime
        ]);
    }

    public function updateAvailability($workerID, $status): bool
    {
        $this->setTable('worker');
        return $this->update($workerID, ['availability_status' => $status], 'workerID');
    }

    public function getWorkerAvailability($workerID)
    {
        $this->setTable('worker');
        $worker = $this->find($workerID, 'workerID');
        return $worker ? $worker->availability_status : null;
    }

    public function getWorkerProfileInfo($workerID): array
    {
        // Get base worker info
        $this->setTable('worker');
        $worker = $this->find($workerID, 'workerID');

        // Get verification details
        $this->setTable('verified_workers');
        $verification = $this->find($workerID, 'workerID');

        // Get worker stats
        $this->setTable('worker_stats');
        $stats = $this->find($workerID, 'workerID');

        return [
            'full_name' => $verification->full_name ?? '',
            'profileImage' => $worker->profileImage ?? '',
            'rating' => $stats->avg_rating ?? 0,
            'reviews' => $stats->total_reviews ?? 0,
            'roles' => $this->getWorkerRoles($workerID),
            'workLocation' => $verification->workLocations ?? '',
        ];
    }

    public function getWorkerRoles($workerID)
    {
        $this->setTable('worker_roles');
        $query = "SELECT r.name FROM worker_roles wr JOIN jobroles r ON wr.roleID = r.roleID WHERE wr.workerID = :workerID";
        $roleObjects = $this->get_all($query, ['workerID' => $workerID]);

        // Extract just the name values from each role object
        $roleNames = [];
        foreach ($roleObjects as $role) {
            $roleNames[] = $role->name;
        }

        return $roleNames;
    }

    public function getNewBookingRequests($workerID)
    {
        $this->setTable('bookings');

        $query = "SELECT * FROM bookings WHERE workerID = :workerID AND status = 'pending'";
        $bookings = $this->get_all($query, ['workerID' => $workerID]);

        $result = [];
        foreach ($bookings as $booking) {
            $result[] = [
                'key' => $booking->bookingID,
                'value' => $this->bookingModel->getBookingDetails($booking->bookingID)
            ];
        }
        return $result;
    }

    public function getAllBookings($workerID)
    {
        $this->setTable('bookings');
        $query = "SELECT * FROM bookings WHERE workerID = :workerID order by bookingDate ASC";
        $bookings = $this->get_all($query, ['workerID' => $workerID]);

        $result = [];
        foreach ($bookings as $booking) {
            $result[] = [
                'key' => $booking->bookingID,
                'value' => $this->bookingModel->getBookingDetails($booking->bookingID)
            ];
        }
        return $result;
    }

    public function updateWorkLocation($workerID, $newLocation)
    {
        $this->setTable('verified_workers');
        return $this->update($workerID, ['workLocations' => $newLocation], 'workerID');
    }

    public function getLatestBookings($workerID)
    {
        $this->setTable('bookings');
        $query = "SELECT * FROM bookings WHERE workerID = :workerID ORDER BY bookingDate DESC LIMIT 20";
        $bookings = $this->get_all($query, ['workerID' => $workerID]);

        $result = [];
        foreach ($bookings as $booking) {
            $result[] = [
                'key' => $booking->bookingID,
                'value' => $this->bookingModel->getBookingDetails($booking->bookingID)
            ];
        }
        return $result;
    }
}
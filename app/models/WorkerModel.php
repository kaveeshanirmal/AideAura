<?php
class WorkerModel 
{
    use Model;

    private $certificateData = [];
    
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
        $query = "SELECT * FROM bookings WHERE workerID = :workerID AND bookingDate = :bookingDate AND (startTime <= :startTime)";
        return $this->get_all($query, [
            'workerID' => $workerID,
            'bookingDate' => $date,
            'startTime' => $startTime
        ]);
    }
}
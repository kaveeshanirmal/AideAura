<?php
class WorkerModel 
{
    use Model;
    
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
                $data = [
                'workerID' => $workerDetails->workerID,
                'Nationality' => $workerDetails->nationality,
                'Gender' => $workerDetails->gender,
                'Contact' => $workerDetails->phone_number,
                'NIC'=> $workerDetails->nic,
                'Age'=> $workerDetails->age_range,
                'EmploymentExperience' => $workerDetails->experience_level,
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
    
    public function getWorkerCertificates($id){

    }
}
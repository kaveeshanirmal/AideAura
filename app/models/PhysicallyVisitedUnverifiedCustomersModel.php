<?php

class PhysicallyVisitedUnverifiedCustomersModel
{
    use Model;

    public function __construct()
    {
        $this->setTable('physically_visited_unverified_customers');
    }

    public function insertRecord($nic, $email, $verificationCode)
    {
        return $this->insert([
            'nic' => $nic,
            'email' => $email,
            'verification_code' => $verificationCode
        ]);
    }

    public function findByVerificationCode($code)
    {
        $query = "SELECT * FROM {$this->getTable()} WHERE verification_code = :code LIMIT 1";
        return $this->get_row($query, ['code' => $code]);
    }

    public function getAllRecords()
    {
        return $this->all();
    }

    // public function deleteByNIC($nic)
    // {
    //     return $this->delete($nic, 'nic');
    // } 

    public function deleteByNIC($nic)
    {
        $query = "DELETE FROM {$this->table} WHERE nic = :nic";
        $stmt = $this->connect()->prepare($query);
        $stmt->execute(['nic' => $nic]);

        // Check if any row was actually deleted
        return $stmt->rowCount() > 0;
    }
}

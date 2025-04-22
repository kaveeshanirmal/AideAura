<?php

class WorkerStats
{
    use Model;

    public function updateLastLogin($workerID)
    {
        $this->setTable('worker_stats');
        $data = [
            'last_activity' => date('Y-m-d H:i:s')
        ];
        $this->update($workerID, $data, 'workerID');
    }
}
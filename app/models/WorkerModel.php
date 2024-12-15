<?php
class WorkerModel 
{
    use Model;
    
    public function getAllWorkers(){
        $this->setTable('worker');
        return $this->all();
    }
    
}
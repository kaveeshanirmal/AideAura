<?php

class WorkerRoleModel {

    use Model;

    // fuction to get all data of jobroles table
    public function getAllRoles(){
        $this->setTable('jobroles');
        return $this->all();
    }

}
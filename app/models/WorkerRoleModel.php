<?php

class WorkerRoleModel {

    use Model;

    // fuction to get all data of jobroles table
    public function getAllRoles(){
        $this->setTable('jobroles');
        return $this->all();
    }

    public function insertRole($data){
        $this->setTable('jobroles');
        // $roleData = [
        //      'name' => $data['name'],
        //      'description' => $data['description'],
        //      'image' => $data['image'],
        // ];

       $userID = $this->insert($data);
       
       if(!$userID){
        return false;
       }

       return true;
    }

}
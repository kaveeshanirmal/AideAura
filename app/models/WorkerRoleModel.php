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

    public function updateRole($id,$data)
    {
        $this->setTable('jobroles');
        $roleData = [
            'name' => $data['name'],
            'description' => $data['description'],
        ];
       $result =  $this->update($id,$roleData, 'roleID');
    
       if($result){
        return true;
       } else {
        return false;
       }

    }

    public function softDeleteRole($roleID){
       $this->setTable('jobroles');
       
       // before delete check if that role exists
       $role = $this->find($roleID, 'roleID');
          if(!$role){
            return false;
          }

          // perform soft delete
          return $this->softDelete($roleID, 'roleID' , 'isDelete');
    }
}
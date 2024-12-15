<?php

class UserModel
{
    use Model; // Use the Model trait

    // Register a new user
    public function register($data, $role)
{
    // Set the table to 'users' for inserting the base user data
    $this->setTable('users');

    // User data to be entered into the 'users' table
    $userData = [
        'firstName' => $data['firstName'],
        'lastName' => $data['lastName'],
        'username' => $data['username'],
        'phone' => $data['phone'],
        'email' => $data['email'],
        'password' => password_hash($data['password'], PASSWORD_BCRYPT),
        'role' => $role,
    ];
    
    // Insert the base user data and get the newly created userID
    $userID = $this->insertAndGetId($userData);

    // Check if user was created successfully
    if (!$userID) {
        return false; // User creation failed
    }

    // If role is 'worker', add data to 'worker' and 'worker_roles' tables
    if ($role === 'worker') {
        $this->setTable('worker');

        // Insert worker-specific data, including foreign key reference to users
        $workerData = [
            'userID' => $userID,
            'address' => $data['address'],
        ];
        $workerID = $this->insertAndGetId($workerData);

        // Retrieve role IDs from jobRoles table based on provided job role names
        if ($workerID && !empty($data['servicesOffer'])) {
            $roleIDs = [];
            foreach ($data['servicesOffer'] as $roleName) {
                $query = "SELECT roleID FROM jobRoles WHERE name = :name LIMIT 1";
                $role = $this->get_row($query, ['name' => $roleName]);
                
                if ($role) {
                    $roleIDs[] = $role->roleID; // Collect the roleID if found
                }
            }

            // Insert job roles for the worker if role IDs were found
            if (!empty($roleIDs)) {
                $this->setTable('worker_roles');
                foreach ($roleIDs as $roleID) {
                    $this->insert([
                        'workerID' => $workerID,
                        'roleID' => $roleID
                    ]);
                }
            }
        }
    }
    // If role is 'customer', add data to 'customer' table
    else if ($role === 'customer') {
        $this->setTable('customer');

        // Insert customer-specific data
        $customerData = [
            'userID' => $userID,
            'address' => $data['address']
        ];
        $this->insert($customerData);
    }
    // If role is 'customer', add data to 'customer' table
    else if ($role === 'hrManager') {
        $this->setTable('hrManager');

        // Insert customer-specific data
        $hrManagerData = [
            'userID' => $userID,
            'address' => $data['address']
        ];
        $this->insert($hrManagerData);
    }
    return $userID; // Return the userID of the newly created user
}
public function registerEmployee($data)
{
     // Set the table to 'users' for inserting the base user data
    $this->setTable('users');

    // User data to be entered into the 'users' table
    $userData = [
        'firstName' => $data['firstName'],
        'lastName' => $data['lastName'],
        'username' => $data['username'],
        'phone' => $data['phone'],
        'email' => $data['email'],
        'password' => password_hash($data['password'], PASSWORD_BCRYPT),
        'role' => $data['role'],
    ];

    // Insert the base user data and get the newly created userID
    $userID = $this->insert($userData);

    // Check if user was created successfully
    if (!$userID) {
        return false; // User creation failed
    }

    return true; // Return true on success
}


    // Find a user by username (for login)
    public function findUserByUsername($username)
    {
        $this->setTable('users'); // Set the table to 'users'

        $query = "SELECT * FROM " . $this->getTable() . " WHERE username = :username LIMIT 1";
        $user = $this->get_row($query, ['username' => $username]);

        // Check if a user is found
        if ($user) {

            // Get role-specific data if the user was found
            if ($user->role === 'customer') {
                $this->setTable('customer');
                $query = "SELECT * FROM customer WHERE userID = :userID";
                $roleData = $this->get_row($query, ['userID' => $user->userID]);
            } elseif ($user->role === 'worker') {
                $this->setTable('worker');
                $query = "SELECT * FROM worker WHERE userID = :userID";
                $roleData = $this->get_row($query, ['userID' => $user->userID]);
            }
            // Merge the role-specific data with the user data
            if ($roleData) {
                $user = (object) array_merge((array) $user, (array) $roleData);
            }

            return $user;
        }

        return false; // No user found
    }


    public function getAllEmployees() {
        $this->setTable('users');
        return $this->all(); // Use the get_all method from the Database trait
    }


    // Update user information
    public function updateUserInfo($id, $data)
    {
        $this->setTable('users'); // Update user information in the 'users' table

        // Update the 'users' table
        $userData = [
            'firstName' => $data->firstName,
            'lastName' => $data->lastName,
            'username' => $data->username,
            'password' => $data->password,
            'email' => $data->email,
            'phone' => $data->phone,
        ];
        $this->update($id, $userData, 'userID');

        // Update 'worker' or 'customer' table based on the role
        $roleData = [
            'address' => $data->address,
            'profileImage' => $data->profileImage,
        ];

        if ($_SESSION['role'] === 'worker') {
            $this->setTable('worker'); 
            return $this->update($id, $roleData, 'userID');
        } elseif ($_SESSION['role'] === 'customer') {
            $this->setTable('customer');
            return $this->update($id, $roleData, 'userID');
        }

        return false; // Update failed
    }


    // Update user information
    public function updateEmployee($id, $data)
    {
        $this->setTable('users'); // Update user information in the 'users' table

        // Update the 'users' table
        $userData = [
            'firstName' => $data['firstName'],
            'lastName' => $data['lastName'],
            'username' => $data['username'],
            'phone' => $data['phone'],
            'email' => $data['email'],
            'role' => $data['role'],
        ];
        
        $result = $this->update($id, $userData, 'userID');

        if (!$result) {
            return false;
        }
        return true; // Update failed
    }


    public function searchEmployees($filters = []) {
        $conditions = [];
        $params = [];
    
        // Add filter for role if provided
        if (!empty($filters['role'])) {
            $conditions[] = "role = :role"; // Use '=' for exact match
            $params['role'] = trim($filters['role']);
        }
    
        // Add filter for userID if provided
        if (!empty($filters['userID'])) {
            $conditions[] = "userID = :userID"; // Use '=' for exact match
            $params['userID'] = trim($filters['userID']);
        }
    
        // Base query
        $sql = "SELECT * FROM users";
    
        // Add WHERE clause only if conditions exist
        if (!empty($conditions)) {
            $sql .= " WHERE " . implode(' AND ', $conditions);
        } else {
            // If no filters, return an empty result set (optional)
            return [];
        }
    
        // Add ordering
        $sql .= " ORDER BY userID DESC";
    
        try {
            // Execute query
            return $this->get_all($sql, $params);
        } catch (Exception $e) {
            error_log("Error searching employees: " . $e->getMessage());
            return [];
        }
    }
    
    

    // Updated delete method with validatio+n
    public function deleteEmployee($userID) {
    $this->setTable('users');
    
    // Check if employee exists before deletion
    $employee = $this->find($userID,'userID');
    if (!$employee) {
        return false;
    }
    
    return $this->delete($userID, 'userID'); // Ensure 'userID' is the correct column name in your table
    }


    // Updated delete method with validation for soft delete
public function softDeleteEmployee($userID) {
    $this->setTable('users');
    
    // Check if employee exists before deletion
    $employee = $this->find($userID, 'userID');
    if (!$employee) {
        return false;
    }
    
    // Perform soft delete instead of permanent deletion
    return $this->softDelete($userID, 'userID', 'isDelete'); 
}

}
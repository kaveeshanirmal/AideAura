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
                    $roleIDs[] = $role['roleID']; // Collect the roleID if found
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

    return $userID; // Return the userID of the newly created user
}


    // Find a user by username (for login)
    public function findUserByUsername($username)
    {
        $this->setTable('users'); // Set the table to 'users'

        $query = "SELECT * FROM " . $this->getTable() . " WHERE username = :username LIMIT 1";
        $user = $this->get_row($query, ['username' => $username]);

        // Check if a user is found
        if ($user) {
            return $user[0]; // Return the first result if found
        }

        return false; // No user found
    }

    // Update user information
    public function updateUserInfo($id, $data, $role)
    {
        $this->setTable('users'); // Update user information in the 'users' table

        // If password is being updated, hash it
        if (isset($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
        }

        // Update the 'users' table
        $this->update($id, $data, 'userID');

        // Update 'worker' or 'customer' table based on the role
        if ($role === 'worker') {
            $this->setTable('worker');
            return $this->update($id, $data, 'userID');
        } elseif ($role === 'customer') {
            $this->setTable('customer');
            return $this->update($id, $data, 'userID');
        }

        return false; // Update failed
    }
}

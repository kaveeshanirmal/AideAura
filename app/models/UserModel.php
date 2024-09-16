<?php

class UserModel
{
    use Model; // Use the Model trait

    // Method to register a new user
    public function register($data, $role)
    {
        if ($role === 'worker') {
            $this->setTable('worker'); // Set the table to 'workers'
        } else {
            $this->setTable('customer'); // Set the table to 'customers'
        }

        // Hash the password before storing it
        $data['passwordHash'] = password_hash($data['passwordHash'], PASSWORD_BCRYPT);
        return $this->insert($data); // Use the insert method from the Model trait
    }

    // Method to find a user by username (for login)
    public function findUserByUsername($username, $role)
    {
        if ($role === 'worker') {
            $this->setTable('worker'); // Set the table to 'workers'
        } else {
            $this->setTable('customer'); // Set the table to 'customers'
        }

        $query = "SELECT * FROM " . $this->getTable() . " WHERE username = :username LIMIT 1";

        $user = $this->get_row($query, ['username' => $username]);

        // Check if a user is found
        if ($user) {
            return $user[0]; // Return the first result if found
        }

        return false; // No user found
    }

    // Method to update user information
    public function updateUserInfo($id, $data, $role)
    {
        if ($role === 'worker') {
            $this->setTable('worker'); // Set the table to 'workers'
        } else {
            $this->setTable('customer'); // Set the table to 'customers'
        }

        // If password is being updated, hash it
        if (isset($data['passwordHash'])) {
            $data['passwordHash'] = password_hash($data['passwordHash'], PASSWORD_BCRYPT);
        }

        // Update the user information using the ID
        return $this->update($id, $data);
    }
}

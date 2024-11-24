<?php

class EmployeeModel
{
    use Model; // Use the Model trait

    // Register a new admin/employee (already working well, no changes)
    public function AddAdmins($data, $role)
    {
        $this->setTable('employees'); // Set the table to 'employees'

        $userData = [
            'name' => $data['name'],
            'email' => $data['email'],
            'contact' => $data['contact'],
            'role' => $role,
            'password' => $data['password'], // Password is already hashed 
            'dateOfHire' => $data['date'],
        ];

        return $this->insert($userData);
    }

    // Search employees by name, role, email, ID, or status
    public function searchEmployees($filters)
    {
        $this->setTable('employees'); // Ensure the table is set
        $conditions = [];
        $params = [];

        foreach ($filters as $key => $value) {
            if (!empty($value)) {
                $conditions[] = "$key LIKE :$key";
                $params[$key] = "%$value%"; // Use wildcard for partial match
            }
        }

        $sql = "SELECT * FROM employees";
        if (!empty($conditions)) {
            $sql .= " WHERE " . implode(' AND ', $conditions);
        }

        return $this->query($sql, $params);
    }

    // Get all employees
    public function getAllEmployees()
    {
        $this->setTable('employees'); // Ensure the table is set
        return $this->all();
    }

    // Update employee details
    public function updateEmployee($id, $data)
    {
        $this->setTable('employees'); // Ensure the table is set
        return $this->update($id, $data, 'id');
    }

    // Delete employee by ID
    public function deleteEmployee($id)
    {
        $this->setTable('employees'); // Ensure the table is set
        return $this->delete($id, 'id');
    }
}

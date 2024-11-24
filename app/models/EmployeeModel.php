<?php

class EmployeeModel {
    use Model;

    // Add admin/employee (existing method, unchanged)
    public function AddAdmins($data) {
        $this->setTable('employees');
        
        $userData = [
            'name' => $data['name'],
            'role' => $data['role'],
            'email' => $data['email'],
            'contact' => $data['contact'],
            'password' => $data['password'],
            'dateOfHire' => $data['date'],
        ];
        
        return $this->insert($userData);
    }

    // Get all employees with ordering
    public function getAllEmployees() {
        $this->setTable('employees');
        return $this->all(); // No SQL is passed here.
    }


    public function testConnection() {
        $this->setTable('employees');
        $query = "SELECT * FROM employees LIMIT 1";
        return $this->get_all($query);
    }

    /*
    // Updated search method to handle frontend filters
    public function searchEmployees($filters) {
        $this->setTable('employees');
        
        $conditions = [];
        $params = [];
        
        // Build search conditions based on filters
        if (!empty($filters['role'])) {
            $conditions[] = "role LIKE :role";
            $params['role'] = "%" . trim($filters['role']) . "%";
        }
        
        if (!empty($filters['email'])) {
            $conditions[] = "email LIKE :email";
            $params['email'] = "%" . trim($filters['email']) . "%";
        }
        
        if (!empty($filters['status'])) {
            $conditions[] = "status LIKE :status";
            $params['status'] = "%" . trim($filters['status']) . "%";
        }
        
        if (!empty($filters['employeeID'])) {
            $conditions[] = "employeeID LIKE :employeeID";
            $params['employeeID'] = "%" . trim($filters['employeeID']) . "%";
        }

        $sql = "SELECT * FROM employees";
        if (!empty($conditions)) {
            $sql .= " WHERE " . implode(' OR ', $conditions);
        }
        
        // Add ordering to ensure consistent results
        $sql .= " ORDER BY employeeID DESC";
        
        return $this->query($sql, $params);
    }


    // Updated update method with validation
    public function updateEmployee($employeeID, $data) {
        $this->setTable('employees');
        
        // Validate required fields
        $requiredFields = ['name', 'email', 'role', 'status'];
        foreach ($requiredFields as $field) {
            if (!isset($data[$field]) || empty(trim($data[$field]))) {
                return false;
            }
        }
        
        // Validate email format
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            return false;
        }
        
        // Clean and prepare data for update
        $updateData = [
            'name' => trim($data['name']),
            'email' => trim($data['email']),
            'role' => trim($data['role']),
            'status' => trim($data['status'])
        ];
        
        // Add contact if provided
        if (isset($data['contact']) && !empty($data['contact'])) {
            $updateData['contact'] = trim($data['contact']);
        }
        
        return $this->update($employeeID, $updateData, 'employeeID');
    }

    // Updated delete method with validation
    public function deleteEmployee($employeeID) {
        $this->setTable('employees');
        
        // Check if employee exists before deletion
        $employee = $this->find($employeeID);
        if (!$employee) {
            return false;
        }
        
        return $this->delete($employeeID, 'employeeID');
    }
 */
}

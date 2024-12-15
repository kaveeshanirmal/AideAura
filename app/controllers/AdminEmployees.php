<?php
class AdminEmployees extends Controller {
// Controller.php (Assuming this is the controller)

public function index() {
    $userModel = new UserModel();
    $allEmployees = $userModel->getAllEmployees(); // Fetch all employees from the database
    error_log("Employees in controller: " . json_encode($allEmployees));

    // Define the allowed roles for filtering
    $allowedRoles = ['hrManager', 'opManager', 'financeManager', 'admin'];

    // Filter employees based on allowed roles
    $filteredEmployees = array_filter($allEmployees, function ($employee) use ($allowedRoles) {
        return in_array($employee->role, $allowedRoles) && ($employee->isDelete == 0); // Access object property using '->'
    });

    if (!$filteredEmployees) {
        error_log("No employees with specified roles retrieved or query failed");
        $filteredEmployees = []; // Ensuring the variable is always an array
    }

    $this->view('admin/adminEmployees', ['employees' => $filteredEmployees]);
}



    public function update() {
        try {
            $data = json_decode(file_get_contents('php://input'), true);

            if (!isset($data['userID'])) {
                throw new Exception('Employee userID is required');
            }

            $userID = $data['userID'];
            unset($data['userID']); // Remove userID from update data

            $employeeModel = new UserModel();
            $success = $employeeModel->updateEmployee($userID, $data);

            header('Content-Type: application/json');
            echo json_encode([
                'success' => $success,
                'message' => $success ? 'Employee updated successfully' : 'Failed to update employee'
            ]);
        } catch (Exception $e) {
            header('Content-Type: application/json');
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    // public function delete() {
    //     try {
    //         // Log the raw input
    //         $raw_input = file_get_contents('php://input');
    //         error_log("Raw input received: " . $raw_input);
    
    //         // Decode JSON with error checking
    //         $data = json_decode($raw_input, true);
    //         if (json_last_error() !== JSON_ERROR_NONE) {
    //             throw new Exception('Invalid JSON: ' . json_last_error_msg());
    //         }
    
    //         // Log decoded data
    //         error_log("Decoded data: " . print_r($data, true));
    
    //         // Validate userID
    //         if (!isset($data['userID'])) {
    //             throw new Exception('Employee userID is required');
    //         }
    
    //         if (!is_numeric($data['userID'])) {
    //             throw new Exception('Invalid userID format');
    //         }
    
    //         // Initialize model
    //         $employeeModel = new UserModel();
            
    //         // Attempt deletion
    //         $success = $employeeModel->softDeleteEmployee($data['userID']);
            
    //         if ($success === false) {
    //             throw new Exception('Database deletion failed');
    //         }
    
    //         // Set headers before any output
    //         header('Content-Type: application/json');
            
    //         // Return success response
    //         echo json_encode([
    //             'success' => true,
    //             'message' => 'Employee deleted successfully'
    //         ]);
            
    //     } catch (Exception $e) {
    //         // Log the error
    //         error_log("Delete employee error: " . $e->getMessage());
            
    //         // Set headers
    //         header('Content-Type: application/json');
    //         http_response_code(500);
            
    //         // Return error response
    //         echo json_encode([
    //             'success' => false,
    //             'message' => $e->getMessage(),
    //             'error' => true
    //         ]);
    //     }
    //     exit; // Ensure no additional output
    // }

    public function search() {
        try {
            $filters = json_decode(file_get_contents('php://input'), true);
    
            $userModel = new UserModel();
            $employees = $userModel->searchEmployees($filters);
    
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'employees' => $employees,
            ]);
        } catch (Exception $e) {
            header('Content-Type: application/json');
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }
    
}

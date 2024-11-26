<?php
class AdminEmployees extends Controller {

    public function index() {
        $userModel = new UserModel();
        $employees = $userModel->getAllEmployees();
        error_log("Employees in controller: " . json_encode($employees));

        if (!$employees) {
            error_log("No employees retrieved or query failed");
            $employees = []; // Ensuring that the variable is always an array (empty array if no employees found)
        }
        $this->view('admin/adminEmployees', ['employees' => $employees]);
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

    public function delete()
    {
        try {
            // Capture raw input and decode JSON
            $raw_input = file_get_contents('php://input');
            $data = json_decode($raw_input, true);
    
            // Log the raw input for debugging
            error_log("Raw input: " . $raw_input);
    
            // Check for JSON decoding errors
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new Exception('Invalid JSON input: ' . json_last_error_msg());
            }
    
            // Validate 'userID'
            if (empty($data['userID']) || !is_numeric($data['userID'])) {
                throw new Exception('Invalid or missing userID');
            }
    
            // Log the userID
            error_log("Deleting user with ID: " . $data['userID']);
    
            // Initialize the UserModel and attempt deletion
            $employeeModel = new UserModel();
            $success = $employeeModel->deleteEmployee($data['userID']);
    
            if (!$success) {
                throw new Exception('Failed to delete the employee. The employee may not exist.');
            }
    
            // Return success response
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'message' => 'Employee deleted successfully',
            ]);
        } catch (Exception $e) {
            // Log the error and return an error response
            error_log("Error in delete function: " . $e->getMessage());
            header('Content-Type: application/json');
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
        exit;
    }
    

}

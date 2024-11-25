<?php
class AdminEmployees extends Controller {
    
    public function index() {
        $employeeModel = new EmployeeModel();
        $employees = $employeeModel->getAllEmployees();
    
        // Debugging the employees array
        if (!$employees) {
            error_log("No employees retrieved or query failed");
        }
    
        $this->view('admin/adminEmployees', ['employees' => $employees]);
    }

/*
    public function search() {
        try {
            // Sanitize input
            $filters = [
                'role' => filter_input(INPUT_GET, 'role', FILTER_SANITIZE_STRING),
                'email' => filter_input(INPUT_GET, 'email', FILTER_SANITIZE_EMAIL),
                'status' => filter_input(INPUT_GET, 'status', FILTER_SANITIZE_STRING),
                'id' => filter_input(INPUT_GET, 'id', FILTER_SANITIZE_STRING)
            ];

            $employeeModel = new EmployeeModel();
            $employees = $employeeModel->searchEmployees($filters);
            
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'data' => $employees
            ]);
        } catch (Exception $e) {
            header('Content-Type: application/json');
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'An error occurred while searching employees'
            ]);
        }
    }

    public function update() {
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            
            if (!isset($data['id'])) {
                throw new Exception('Employee ID is required');
            }
            
            $id = $data['id'];
            unset($data['id']); // Remove ID from update data
            
            $employeeModel = new EmployeeModel();
            $success = $employeeModel->updateEmployee($id, $data);
            
            header('Content-Type: application/json');
            if ($success) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Employee updated successfully'
                ]);
            } else {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'message' => 'Failed to update employee'
                ]);
            }
        } catch (Exception $e) {
            header('Content-Type: application/json');
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function delete() {
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            
            if (!isset($data['id'])) {
                throw new Exception('Employee ID is required');
            }
            
            $employeeModel = new EmployeeModel();
            $success = $employeeModel->deleteEmployee($data['id']);
            
            header('Content-Type: application/json');
            if ($success) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Employee deleted successfully'
                ]);
            } else {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'message' => 'Failed to delete employee'
                ]);
            }
        } catch (Exception $e) {
            header('Content-Type: application/json');
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
    */
}
?>
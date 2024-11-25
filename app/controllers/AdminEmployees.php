<?php
class AdminEmployees extends Controller {
// Controller.php (Assuming this is the controller)

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

            if (!isset($data['id'])) {
                throw new Exception('Employee ID is required');
            }

            $id = $data['id'];
            unset($data['id']); // Remove ID from update data

            $employeeModel = new UserModel();
            $success = $employeeModel->updateEmployee($id, $data);

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

    public function delete() {
        try {
            $data = json_decode(file_get_contents('php://input'), true);

            if (!isset($data['id'])) {
                throw new Exception('Employee ID is required');
            }

            $employeeModel = new UserModel();
            $success = $employeeModel->deleteEmployee($data['id']);

            header('Content-Type: application/json');
            echo json_encode([
                'success' => $success,
                'message' => $success ? 'Employee deleted successfully' : 'Failed to delete employee'
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
}
?>

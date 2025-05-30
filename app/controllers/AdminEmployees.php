<?php
class AdminEmployees extends Controller
{

    public function index()
    {
        $filteredEmployees = $this->getFilteredEmployees();
        error_log("Employees in controller: " . json_encode($filteredEmployees));
        $this->view('admin/adminEmployees', ['employees' => $filteredEmployees]);   // return array of employees
    }


    // Private helper function to get filtered employees (DRY principle)
    private function getFilteredEmployees()
    {
        $userModel = new UserModel();     // create an instance of userModel class
        $allEmployees = $userModel->getAllEmployees();

        error_log("Employees in controller: " . json_encode($allEmployees));   // Log all employees for debugging

        // Ensure $allEmployees is an array
        if (!is_array($allEmployees)) {
            error_log("getAllEmployees did not return an array");
            return [];
        }

        // Define the allowed roles for filtering
        $allowedRoles = ['hrManager', 'opManager', 'financeManager', 'admin'];

        // Filter employees based on allowed roles
        $filteredEmployees = array_filter($allEmployees, function ($employee) use ($allowedRoles) {
            return is_object($employee) && 
                   isset($employee->role, $employee->isDelete) &&
                   in_array($employee->role, $allowedRoles) && 
                   ($employee->isDelete === 0);
        });

        if (empty($filteredEmployees)) {
            error_log("No employees with specified roles retrieved or query failed");
            return []; // Ensuring the variable is always an array
        }

        return array_values($filteredEmployees); // Reset array keys
    }


    public function update()
    {
        try {
            $data = json_decode(file_get_contents('php://input'), true);  // retrieve and decode raw JSON data 

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
            // Log the raw input
            $raw_input = file_get_contents('php://input');
            error_log("Raw input received: " . $raw_input);

            // Decode JSON with error checking
            $data = json_decode($raw_input, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new Exception('Invalid JSON: ' . json_last_error_msg());
            }

            // Log decoded data
            error_log("Decoded data: " . print_r($data, true));

            // Validate userID
            if (!isset($data['userID'])) {
                throw new Exception('Employee userID is required');
            }

            if (!is_numeric($data['userID'])) {
                throw new Exception('Invalid userID format');
            }

            // Initialize model
            $employeeModel = new UserModel();

            // Attempt deletion
            $success = $employeeModel->softDeleteEmployee($data['userID']);

            if ($success === false) {
                throw new Exception('Database deletion failed');
            }

            // Set headers before any output
            header('Content-Type: application/json');

            // Return success response
            echo json_encode([
                'success' => true,
                'message' => 'Employee deleted successfully'
            ]);
        } catch (Exception $e) {
            // Log the error
            error_log("Delete employee error: " . $e->getMessage());

            // Set headers
            header('Content-Type: application/json');
            http_response_code(500);

            // Return error response
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage(),
                'error' => true
            ]);
        }
        exit; // Ensure no additional output
    }

    public function search()
    {
        header('Content-Type: application/json');

        try {
            // Decode the JSON input from the request body
            $data = json_decode(file_get_contents('php://input'), true);

            // Validate and extract filters from the input
            $filters = [
                'role' => !empty($data['role']) ? trim($data['role']) : null,
                'userID' => !empty($data['userID']) ? trim($data['userID']) : null
            ];

            // Ensure at least one filter is provided
            if (empty($filters['role']) && empty($filters['userID'])) {
                throw new Exception('At least one filter (role or userID) must be provided.');
            }

            $userModel = new UserModel();
            $employees = $userModel->searchEmployees($filters);

            echo json_encode([
                'success' => true,
                'employees' => $employees
            ]);
        } catch (Exception $e) {
            http_response_code(400); // Bad request
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
        // I was add some comments to the code
        exit; // Ensure no further output
    }

    // public function all()
    // {
    //     try {
    //         $filteredEmployees = $this->getFilteredEmployees();

    //         header('Content-Type: application/json');
    //         echo json_encode([
    //             'success' => true,
    //             'employees' => $filteredEmployees
    //         ]);
    //     } catch (Exception $e) {
    //         header('Content-Type: application/json');
    //         http_response_code(500);
    //         echo json_encode([
    //             'success' => false,
    //             'message' => $e->getMessage()
    //         ]);
    //     }
    // }
}

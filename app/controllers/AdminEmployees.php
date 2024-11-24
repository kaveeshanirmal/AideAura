<?php

class AdminEmployees extends Controller
{
    public function index()
    {
        $employeeModel = new EmployeeModel();
        $employees = $employeeModel->getAllEmployees();
        $this->view('admin/adminEmployees', ['employees' => $employees]);
    }

    public function search()
    {
        $filters = [
            'name' => $_GET['name'] ?? '',
            'role' => $_GET['role'] ?? '',
            'email' => $_GET['email'] ?? '',
            'status' => $_GET['status'] ?? '',
            'id' => $_GET['id'] ?? '',
        ];

        $employeeModel = new EmployeeModel();
        $employees = $employeeModel->searchEmployees($filters);
        echo json_encode($employees);
    }

    public function update()
    {
        $data = json_decode(file_get_contents('php://input'), true);

        $id = $data['id'];
        unset($data['id']); // Remove ID from update data

        $employeeModel = new EmployeeModel();
        $success = $employeeModel->updateEmployee($id, $data);

        echo json_encode(['success' => $success]);
    }

    public function delete()
    {
        $data = json_decode(file_get_contents('php://input'), true);

        $id = $data['id'];

        $employeeModel = new EmployeeModel();
        $success = $employeeModel->deleteEmployee($id);

        echo json_encode(['success' => $success]);
    }
}

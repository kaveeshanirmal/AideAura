<?php

class AdminEmployeeAdd extends Controller
{
    private $employeeModel;

    public function __construct()
    {
        $this->employeeModel = new EmployeeModel();
    }

    public function index($a = '', $b = '', $c = '')
    {
        $data = [
            'pageTitle' => 'Add Employee',
            'action' => 'add',
        ];
        $this->view('admin/adminEmployeeAdd', $data);
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $_POST = filter_input_array(INPUT_POST);
    
            $data = [
                'name' => trim($_POST['name']),
                'email' => trim($_POST['email']),
                'contact' => trim($_POST['contact']),
                'role' => trim($_POST['role']),
                'password' => password_hash(trim($_POST['password']), PASSWORD_DEFAULT), // Hashing the password
                'date' => trim($_POST['date']),
            ];
    
            $result = $this->employeeModel->AddAdmins($data);
    
            if ($result) {
                echo json_encode(['status' => 'success', 'message' => 'Employee added successfully!']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to add employee. Please try again.']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
        }
        exit;
    }

    
}
?>
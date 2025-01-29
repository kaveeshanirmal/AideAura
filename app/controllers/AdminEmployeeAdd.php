<?php
class AdminEmployeeAdd extends Controller
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel(); // Instantiate UserModel
    }

    public function index($a = '', $b = '', $c = '')
    {
        $data = [
            'pageTitle' => 'Add User',
            'action' => 'add',
        ];
        $this->view('admin/adminEmployeeAdd', $data); // Load the appropriate view
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            header('Content-Type: application/json'); // Ensure JSON response
            $_POST = filter_input_array(INPUT_POST);
    
            $data = [
                'firstName' => trim($_POST['firstName']),
                'lastName' => trim($_POST['lastName']),
                'username' => trim($_POST['username']),
                'phone' => trim($_POST['phone']),
                'email' => trim($_POST['email']),
                'password' => trim($_POST['password']),
                'role' => trim($_POST['role']),
            ];

            $result = $this->userModel->registerEmployee($data);

            if ($result) {
                echo json_encode(['status' => 'success', 'message' => 'User added successfully!']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to add user. Please try again.']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
        }
        exit;
    }
}
?>

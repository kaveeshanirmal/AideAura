<?php

class workerVerification extends Controller
{
    private $verificationRequestModel;

    public function __construct()
    {
        $this->verificationRequestModel = new VerificationRequestModel();
    }
    public function index($a = '', $b = '', $c = '')
    {
        $this->view('workerVerification/employeeVerificationForm');
    }

    public function submitVerificationForm()
    {
        // Ensure that the request method is POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Process the form
            $inputData = json_decode(file_get_contents('php://input'), true);

        if ($inputData) {
            // Map input data to match the table structure
            $data = [
                'workerID' => $_SESSION['workerID'],
                'full_name' => $inputData['fullName'] ?? null,
                'username' => $inputData['userName'] ?? null,
                'email' => $inputData['email'] ?? null,
                'phone_number' => $inputData['telephone'] ?? null,
                'gender' => $inputData['gender'] ?? null,
                'hometown' => $inputData['hometown'] ?? null,
                'age_range' => $inputData['age'] ?? null,
                'service_type' => $inputData['service'] ?? null,
                'experience_level' => $inputData['experience'] ?? null,
                'description' => $inputData['description'] ?? null,
                'special_notes' => $inputData['notes'] ?? null,
                'working_weekdays' => $inputData['workingWeekdays'] ?? null,
                'working_weekends' => $inputData['workingWeekends'] ?? null,
                'certificates_path' => null, // Assuming certificates_path will be handled separately
                'isEditable' => true,
                'status' => 'pending'
            ];

            // Pass $data to the model to insert into the database
            $result = $this->verificationRequestModel->createRequest($data);

            if ($result) {
                $_SESSION['message'] = "Request sent successfully!";
                $_SESSION['message_type'] = "success";
                $this->view('workerVerification/verificationStatus');
                header('Content-Type: application/json');
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Request submitted successfully',
                ]);
            } else {
                $_SESSION['message'] = "Failed to send request. Please try again.";
                $_SESSION['message_type'] = "error";
                $this->view('workerVerification/verificationStatus', ['error' => true]);
                header('Content-Type: application/json');
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Request submission failed. Please try again.',
                ]);
            }
        }
    }
}
    public function editVerificationRequest()
    {
        $requestData = $this->verificationRequestModel->findRequestByWorkerId($_SESSION['workerID']);
        $this->view('workerVerification/editRequest', ['requestData' => $requestData]);
    }

    public function update()
{
    // Retrieve existing data
    $requestData = $this->verificationRequestModel->findRequestByWorkerId($_SESSION['workerID']);
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Collect and sanitize user input
        $data = [
            'requestID' => $requestData->requestID,
            'workerID' => $_SESSION['workerID'],
            'full_name' => isset($_POST['fullName']) ? $_POST['fullName'] : $requestData->full_name,
            'username' => isset($_POST['userName']) ? $_POST['userName'] : $requestData->username,
            'email' => isset($_POST['email']) ? $_POST['email'] : $requestData->email,
            'phone_number' => isset($_POST['telephone']) ? $_POST['telephone'] : $requestData->phone_number,
            'gender' => isset($_POST['gender']) ? $_POST['gender'] : $requestData->gender,
            'hometown' => isset($_POST['hometown']) ? $_POST['hometown'] : $requestData->hometown,
            'age_range' => isset($_POST['age']) ? $_POST['age'] : $requestData->age_range,
            'service_type' => isset($_POST['service']) ? $_POST['service'] : $requestData->service_type,
            'experience_level' => isset($_POST['experience']) ? $_POST['experience'] : $requestData->experience_level,
            'description' => isset($_POST['description']) ? $_POST['description'] : $requestData->description,
            'special_notes' => isset($_POST['notes']) ? $_POST['notes'] : $requestData->special_notes,
            'working_weekdays' => isset($_POST['workingWeekdays']) ? $_POST['workingWeekdays'] : $requestData->working_weekdays,
            'working_weekends' => isset($_POST['workingWeekends']) ? $_POST['workingWeekends'] : $requestData->working_weekends,
            'certificates_path' => null, // Assuming certificates_path will be handled separately
            'isEditable' => true,
            'status' => 'pending'
        ];

        // Update the request
        $result = $this->verificationRequestModel->updateRequest($data, $requestData->requestID);

        if ($result) {
            $_SESSION['message'] = "Request updated successfully!";
            $_SESSION['message_type'] = "success";
            header('Location: ' . ROOT . '/public/workerVerification/verificationStatus');
        } else {
            $_SESSION['message'] = "Failed to update request. Please try again.";
            $_SESSION['message_type'] = "error";
            header('Location: ' . ROOT . '/public/workerVerification/editVerificationRequest');
        }
    } else {
        header('Location: ' . ROOT . '/public/workerVerification/editVerificationRequest');
    }
}


    public function deleteVerificationRequest()
    {
        $requestData = $this->verificationRequestModel->findRequestByWorkerId($_SESSION['workerID']);
        $result = $this->verificationRequestModel->deleteRequest($requestData->requestID);

        if ($result) {
            $_SESSION['message'] = "Request deleted successfully!";
            $_SESSION['message_type'] = "success";
            header('Location: ' . ROOT . '/public/workerVerification/verificationStatus');
        } else {
            $_SESSION['message'] = "Failed to delete request. Please try again.";
            $_SESSION['message_type'] = "error";
            header('Location: ' . ROOT . '/public/workerVerification/verificationStatus');
        }
    }

    public function verificationStatus()
    {
        // fetch verification data
        $requestData = $this->verificationRequestModel->findRequestByWorkerId($_SESSION['workerID']);
        $this->view('workerVerification/verificationStatus', ['requestData' => $requestData]);
    }

    public function clearSessionMessage() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            unset($_SESSION['message']);
            unset($_SESSION['message_type']);
            http_response_code(200);
            exit();
        }
    }
}




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
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $inputData = $_POST;

            // Handle array fields (e.g., workLocations, languages)
            $workLocations = isset($_POST['workLocations']) ? (is_array($_POST['workLocations']) ? implode(',', $_POST['workLocations']) : $_POST['workLocations']) : null;
            $spokenLanguages = isset($_POST['languages']) ? (is_array($_POST['languages']) ? implode(',', $_POST['languages']) : $_POST['languages']) : null;

            // Debug file uploads
            error_log("FILES array content: " . print_r($_FILES, true));

            // Handle file uploads with proper directory creation and error checking
            $certificatePath = null;
            $medicalPath = null;

            if (isset($_FILES['certificateFile']) && !empty($_FILES['certificateFile']['name'])) {
                $targetDir = __DIR__ . "/../../public/uploads/certificates/";
                
                // Create directory if it doesn't exist
                if (!is_dir($targetDir)) {
                    mkdir($targetDir, 0755, true);
                }
                
                $certificatePath = $targetDir . time() . '_' . basename($_FILES["certificateFile"]["name"]);
                
                if (move_uploaded_file($_FILES["certificateFile"]["tmp_name"], $certificatePath)) {
                    error_log("Certificate file uploaded to: " . $certificatePath);
                } else {
                    error_log("Certificate file upload failed. Error: " . $_FILES["certificateFile"]["error"]);
                    // Store error information in the path for debugging
                    $certificatePath = "UPLOAD_ERROR_" . $_FILES["certificateFile"]["error"];
                }
            }

            if (isset($_FILES['medicalFile']) && !empty($_FILES['medicalFile']['name'])) {
                $targetDir = __DIR__ . "/../../public/uploads/medical/";
                
                // Create directory if it doesn't exist
                if (!is_dir($targetDir)) {
                    mkdir($targetDir, 0755, true);
                }
                
                $medicalPath = $targetDir . time() . '_' . basename($_FILES["medicalFile"]["name"]);
                
                if (move_uploaded_file($_FILES["medicalFile"]["tmp_name"], $medicalPath)) {
                    error_log("Medical file uploaded to: " . $medicalPath);
                } else {
                    error_log("Medical file upload failed. Error: " . $_FILES["medicalFile"]["error"]);
                    // Store error information in the path for debugging
                    $medicalPath = "UPLOAD_ERROR_" . $_FILES["medicalFile"]["error"];
                }
            }  

            // if (empty($inputData['locationVerificationCode']) || !preg_match('/^\d{6}$/', $inputData['locationVerificationCode'])) {
            //     header('Content-Type: application/json');
            //     echo json_encode([
            //         'status' => 'error',
            //         'message' => 'Invalid or missing location verification code. It must be a 6-digit number.',
            //     ]);
            //     exit();
            // }

            // Map input data to match the table structure
            $data = [
                'workerID' => $_SESSION['workerID'],
                'full_name' => $inputData['fullName'] ?? null,
                'username' => $inputData['userName'] ?? null,
                'email' => $inputData['email'] ?? null,
                'phone_number' => $inputData['telephone'] ?? null,
                'gender' => $inputData['gender'] ?? null,
                'spokenLanguages' => $spokenLanguages,
                'hometown' => $inputData['hometown'] ?? null,
                'nic' => $inputData['nic'] ?? null,
                'nationality' => $inputData['nationality'] ?? null,
                'age_range' => $inputData['age'] ?? null,
                'service_type' => $inputData['service'] ?? null,
                'experience_level' => $inputData['experience'] ?? null,
                'workLocations'=> $workLocations,
                'certificates_path' => $certificatePath,
                'medical_path' => $medicalPath,
                'description' => $inputData['description'] ?? null,
                'bankNameCode' => $inputData['bankNameCode'] ?? null,
                'accountNumber' => $inputData['accountNumber'] ?? null,
                'working_weekdays' => $inputData['workingWeekdays'] ?? null,
                'working_weekends' => $inputData['workingWeekends'] ?? null,
                'allergies' => $inputData['allergies'] ?? null,
                'special_notes' => $inputData['notes'] ?? null,
                'isEditable' => true,
                'status' => 'pending',
                'in_location_verification_code' => $inputData['locationVerificationCode'],
            ];

            // Debug data before saving
            error_log("Data to be saved: " . print_r($data, true));

            $result = $this->verificationRequestModel->createRequest($data);

            if ($result) {
                $_SESSION['message'] = "Request sent successfully!";
                $_SESSION['message_type'] = "success";
                header('Content-Type: application/json');
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Request submitted successfully',
                ]);
                exit();
            } else {
                $_SESSION['message'] = "Failed to send request. Please try again.";
                $_SESSION['message_type'] = "error";
                header('Content-Type: application/json');
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Request submission failed. Please try again.',
                ]);
                exit();
            }
        } else {
            http_response_code(405);
            echo json_encode(["error" => "Method Not Allowed"]);
            exit();
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
            'spokenLanguages' => isset($_POST['languages']) ? $_POST['languages'] : $requestData->languages,
            'hometown' => isset($_POST['hometown']) ? $_POST['hometown'] : $requestData->hometown,
            'nic' => isset($_POST['nic']) ? $_POST['nic'] : $requestData->nic,
            'nationality' => isset($_POST['nationality']) ? $_POST['nationality'] : $requestData->nationality,
            'age_range' => isset($_POST['age']) ? $_POST['age'] : $requestData->age_range,
            'service_type' => isset($_POST['service']) ? $_POST['service'] : $requestData->service_type,
            'experience_level' => isset($_POST['experience']) ? $_POST['experience'] : $requestData->experience_level,
            'workLocations'=> isset($_POST['workLocations']) ? $_POST['workLocations'] : $requestData->workLocations,
            'certificates_path' => null, // Assuming certificates_path will be handled separately
            'medical_path' => null, // Assuming medical_path will be handled separately
            'description' => isset($_POST['description']) ? $_POST['description'] : $requestData->description,
            'bankNameCode' => isset($_POST['bankNameCode']) ? $_POST['bankNameCode'] : $requestData->bankNameCode,
            'accountNumber' => isset($_POST['accountNumber']) ? $_POST['accountNumber'] : $requestData->accountNumber,
            'working_weekdays' => isset($_POST['workingWeekdays']) ? $_POST['workingWeekdays'] : $requestData->working_weekdays,
            'working_weekends' => isset($_POST['workingWeekends']) ? $_POST['workingWeekends'] : $requestData->working_weekends,
            'allergies' => isset($_POST['allergies']) ? $_POST['allergies'] : $requestData->allergies,
            'in_location_verification_code' => isset($_POST['locationVerificationCode']) ? $_POST['locationVerificationCode'] : $requestData->in_location_verification_code,
            'special_notes' => isset($_POST['notes']) ? $_POST['notes'] : $requestData->special_notes,
            'isEditable' => true,
            'status' => 'pending',
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




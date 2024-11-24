<?php

class WorkerProfile extends Controller
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel(); // Instantiate UserModel
    }
    public function index()
    {
        $this->view('profile');
    }

    public function personalInfo()
    {
        // Check if the user is logged in
        if (!isset($_SESSION['userID'])) {
            header('Location: ' . ROOT . '/public/login'); // Redirect to login if not logged in
            exit;
        }

        // Fetch the user data
        $data = $this->userModel->findUserByUsername($_SESSION['username']);

        // Load the view and pass the user data to it
        $this->view('personalInfo', ['user' => $data]);
    }

    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // Retrieve existing user data
            $data = $this->userModel->findUserByUsername($_SESSION['username']);
            $profileImagePath = $data->profileImage;
            $password = $data->password;

            // Handle image upload
            if (isset($_FILES['profileImage']) && $_FILES['profileImage']['error'] === UPLOAD_ERR_OK) {
                $fileTmpPath = $_FILES['profileImage']['tmp_name'];
                $fileName = $_FILES['profileImage']['name'];

                // Extract file extension
                $fileNameCmps = explode(".", $fileName);
                $fileExtension = strtolower(end($fileNameCmps));

                $allowedfileExtensions = array('jpg', 'jpeg', 'png', 'gif');
                if (in_array($fileExtension, $allowedfileExtensions)) {
                    // Directory for uploads
                    $uploadFileDir = ROOT_PATH . '/public/assets/images/profiles/';
                    // Generate a new filename
                    $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
                    $dest_path = $uploadFileDir . $newFileName;

                    // Move the file to the upload directory
                    if (move_uploaded_file($fileTmpPath, $dest_path)) {
                        // Save the new file path in the database
                        $profileImagePath = 'public/assets/images/profiles/' . $newFileName;}
                }
            }

            //collect and sanitize user input
            $firstName = trim($_POST['firstName']);
            $lastName = trim($_POST['lastName']);
            $username = trim($_POST['username']);
            $email = trim($_POST['email']);
            $phone = $_POST['phone'];
            $address = $_POST['address'];

            // Perform validation checks here...

            $data = [
                'firstName' => $firstName,
                'lastName' => $lastName,
                'username' => $username,
                'password' => $password,
                'profileImage' => $profileImagePath,
                'email' => $email,
                'phone' => $phone,
                'address' => $address
            ];
            // Converting dataarray to object
            $data = (object) $data;

            // Update user information in the database
            $userId = $_SESSION['userID'];
            $result = $this->userModel->updateUserInfo($userId, $data);

            if ($result) {
                $_SESSION['message'] = "Profile updated successfully!";
                $_SESSION['message_type'] = "success";
                echo "Profile updated successfully!";
            } else {
                $_SESSION['message'] = "Failed to update profile. Please try again.";
                $_SESSION['message_type'] = "error";
                echo "Failed to update profile. Please try again.";
            }

            // Redirect back to the profile page
            // header("Location: " . ROOT . "/public/profile/personalInfo");
            exit();
        }
    }

    public function workingSchedule()
    {
        $this->view('workingSchedule');
    }
}

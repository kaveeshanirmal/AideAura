<?php

class PROFILE extends Controller
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
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . ROOT . '/public/login'); // Redirect to login if not logged in
            exit;
        }

        // Fetch the user data based on role
        if ($_SESSION['role'] == 'customer') {
            $data = $this->userModel->findUserByUsername($_SESSION['username'], 'customer');
        } elseif ($_SESSION['role'] == 'worker') {
            $data = $this->userModel->findUserByUsername($_SESSION['username'], 'worker');
        } else {
            echo "Invalid user role!";
            exit;
        }

        // Load the view and pass the user data to it
        $this->view('personalInfo', ['user' => $data]);
    }

    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

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
            $name = trim($_POST['name']);
            $username = trim($_POST['username']);
            $email = trim($_POST['email']);
            $phone = $_POST['phone'];
            $address = $_POST['address'];

            // Perform validation checks here...

            $data = [
                'name' => $name,
                'username' => $username,
                'profileImage' => $profileImagePath,
                'email' => $email,
                'phoneNo' => $phone,
                'address' => $address
            ];

            $role = $_SESSION['role'];

            // Update user information in the database
            $userId = $_SESSION['user_id'];
            $result = $this->userModel->updateUserInfo($userId, $data, $role);

            if ($result) {
                $_SESSION['message'] = "Profile updated successfully!";
                $_SESSION['message_type'] = "success";
            } else {
                $_SESSION['message'] = "Failed to update profile. Please try again.";
                $_SESSION['message_type'] = "error";
            }

            // Redirect back to the profile page
            header("Location: " . ROOT . "/public/profile/personalInfo");
            exit();
        }
    }

    public function bookingHistory()
    {
        // Load the view and pass the booking history data to it
        $this->view('bookingHistory');
    }

    public function paymentHistory()
    {
        // Load the view and pass the payment history data to it
        $this->view('paymentHistory');
    }
    
    public function faq()
    {
        $faqs = [
            ['question' => 'How do I book a domestic helper?', 'answer' => 'To book a domestic helper, log into your account, browse available helpers, select one that matches your needs, and follow the booking process. If you need assistance, our customer support team is ready to help.'],
            ['question' => 'What payment methods are accepted?', 'answer' => 'We accept various payment methods including credit/debit cards, PayPal, and bank transfers. All transactions are secure and encrypted.'],
            ['question' => 'How do I change my password?', 'answer' => 'To change your password, go to your account settings, select \'Change Password\', enter your current password, then your new password twice to confirm. Click \'Save\' to update your password.'],
            ['question' => 'Does AideAura provide multiple language support?', 'answer' => 'Yes, AideAura supports multiple languages. You can change your language preference in your account settings. Our customer support team also offers assistance in various languages.'],
            ['question' => 'How is my personal information protected?', 'answer' => 'We take data protection seriously. Your personal information is encrypted and stored securely. We never share your data with third parties without your consent. For more details, please review our Privacy Policy.']
        ];

        $this->view('faq', ['faqs' => $faqs]);
    }
    }

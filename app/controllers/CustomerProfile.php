<?php

class CustomerProfile extends Controller
{
    private $userModel;
    private $bookingModel;

    public function __construct()
    {
        $this->userModel = new UserModel(); // Instantiate UserModel
        $this->bookingModel = new BookingModel();
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

//             Redirect back to the profile page
             header("Location: " . ROOT . "/public/customerProfile/personalInfo");
            exit();
        }
    }

    public function bookingHistory()
    {
        $bookingListing = $this->bookingModel->getBookingDetailsByCustomer($_SESSION['customerID']);
        $this->view('bookingHistory',['bookings' => $bookingListing]); 
    }

    public function cancellingBooking()
    {

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['bookingID'])) {
            $bookingID = $_POST['bookingID'];

               // Perform the cancellation logic
                $success = $this->bookingModel->cancelBooking($bookingID);

                if ($success) {
                    $_SESSION['message'] = "Booking canceled successfully.";
                    $_SESSION['message_type'] = "success";
                } else {
                    $_SESSION['message'] = "Failed to cancel booking.";
                    $_SESSION['message_type'] = "error";
                }
                
        }


        header("Location: " . ROOT . "/public/customerProfile/bookingHistory");
       exit();
    }


    public function paymentHistory()
    {
        // Load the view and pass the payment history data to it
        $this->view('customerPaymentHistory');
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

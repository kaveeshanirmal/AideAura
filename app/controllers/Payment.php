<?php

class Payment extends Controller
{
    private $userModel;
    private $bookingModel;
    private $paymentModel;
    private $merchantId;
    private $merchantSecret;
    private $notificationModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->bookingModel = new BookingModel();
        $this->paymentModel = new PaymentModel();
        $this->notificationModel = new NotificationModel();
        $this->merchantId = $_ENV["PAYHERE_MERCHANT_ID"];
        $this->merchantSecret = $_ENV["PAYHERE_MERCHANT_SECRET"];
    }

    public function authorize()
    {
        // Validate session data
        if (!isset($_SESSION['username'])) {
            $response = ['status' => 'error', 'message' => 'User not logged in.'];
            $this->jsonResponse($response, 401);
            return;
        }

        if (!isset($_SESSION['booking'])) {
            $response = ['status' => 'error', 'message' => 'Booking information missing.'];
            $this->jsonResponse($response, 400);
            return;
        }

        // Get user data
        $userData = $this->userModel->findUserByUsername($_SESSION['username']);
        if (!$userData) {
            $response = ['status' => 'error', 'message' => 'User not found.'];
            $this->jsonResponse($response, 404);
            return;
        }

        // Generate payment parameters
        $orderId = $_SESSION['booking']['bookingID'];
        $amount = number_format($_SESSION['booking']['totalCost'], 2, '.', '');
        $currency = "LKR";

        // Generate security hash
        $hash = strtoupper(md5(
            $this->merchantId .
            $orderId .
            $amount .
            $currency .
            strtoupper(md5($this->merchantSecret))
        ));

        // Build payment data array
        $paymentData = [
            'merchant_id' => $this->merchantId,
            'return_url' => ROOT . "/public/payment/success",
            'cancel_url' => ROOT . "/public/payment/cancel",
            'notify_url' => ROOT . "/public/payment/notify",
            'order_id' => $orderId,
            'items' => 'Domestic Service Booking',
            'amount' => $amount,
            'currency' => $currency,
            'first_name' => $userData->firstName,
            'last_name' => $userData->lastName,
            'email' => $userData->email,
            'phone' => $userData->phone,
            'address' => $userData->address,
            'city' => 'Colombo',
            'country' => 'Sri Lanka',
            'hash' => $hash
        ];

        // Output auto-submitting form
        echo '<html>
        <body>
            <form id="payhereForm" action="https://sandbox.payhere.lk/pay/checkout" method="POST">';

        foreach ($paymentData as $name => $value) {
            echo '<input type="hidden" name="' . htmlspecialchars($name) . '" value="' . htmlspecialchars($value) . '">';
        }

        echo '</form>
            <script>
                document.getElementById("payhereForm").submit();
            </script>
        </body>
    </html>';
        exit();
    }

// Helper method for JSON responses
    private function jsonResponse($data, $statusCode = 200)
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit();
    }

    /**
     * @throws \Random\RandomException
     */
    public function success()
    {
        // Get payment details from PayHere response
        $orderId = $_GET['order_id'] ?? null;
        $paymentId = $_GET['payment_id'] ?? null;

        if (!$orderId) {
            header('Location: ' . ROOT . '/public/home');
            exit();
        }

        // Find booking by order ID (which is bookingID)
        $booking = $this->bookingModel->getBasicBookingData($orderId);
        if (!$booking) {
            $_SESSION['message'] = 'Booking not found';
            header('Location: ' . ROOT . '/public/home');
            exit();
        }

        // Get worker details
        $worker = $this->userModel->findWorkerByID($booking->workerID);

        // Check if payment already recorded
        $existingPayment = $this->paymentModel->findPaymentsByBookingID($orderId);

        if (empty($existingPayment)) {
            // Generate a temporary transaction ID if none provided from PayHere
            $paymentId = $paymentId ?? 'TEMP_' . uniqid();

            // Create payment record
            $paymentData = [
                'bookingID' => $orderId,
                'transactionID' => $paymentId,
                'amount' => $booking->totalCost,
                'currency' => 'LKR',
                'paymentMethod' => 'PayHere',
                'paymentStatus' => 'pending', // Set as pending until notify confirms it
                'merchantReference' => $this->merchantId,
                'responseData' => json_encode($_GET) // Convert array to JSON string
            ];

            $paymentID = $this->paymentModel->createPayment($paymentData);

            // Log the transaction
            $logData = [
                'paymentID' => $paymentID,
                'statusBefore' => 'pending',
                'statusAfter' => 'pending', // Keep as pending until notify webhook confirms
                'amount' => $booking->totalCost,
                'notes' => 'Payment redirect received via PayHere',
                'ipAddress' => $_SERVER['REMOTE_ADDR'],
                'userAgent' => $_SERVER['HTTP_USER_AGENT']
            ];

            $this->paymentModel->logPaymentTransaction($logData);
        }

        // Get payment info for the view
        $paymentInfo = [
            'transactionID' => $paymentId ?? 'Processing',
            'paymentMethod' => 'PayHere',
            'paymentDate' => date('Y-m-d H:i:s')
        ];

        // Update the booking status to 'confirmed'
        $this->bookingModel->updateBookingStatus($orderId, 'confirmed');

        // create booking confirmation OTP with 4 digits
        $code = str_pad(random_int(0, 999999), 4, '0', STR_PAD_LEFT);
        $this->bookingModel->setTable('bookings');
        $this->bookingModel->update($booking->bookingID, ['verificationCode' => $code], 'bookingID');

        // Notify both user and worker about the booking confirmation
        $this->notificationModel->create(
            $_SESSION['userID'],
            'Booking Confirmation',
            'Booking Confirmed',
            'Your booking has been confirmed. Enjoy your service!'
        );
        $this->notificationModel->create(
            $worker->userID,
            'Booking Confirmation',
            'Booking Confirmed',
            'Customer has confirmed the booking. Navigate to your dashboard for more details.'
        );

        // Send email to worker with booking details
        MailHelper::sendBookingConfirmation(
            $worker->email,
            $booking,
            'worker'
        );
        // Send email to customer with booking details
        MailHelper::sendBookingConfirmation(
            $this->userModel->findUserByUsername($_SESSION['username'])->email,
            $booking,
            'customer'
        );

        // Unset the booking session to prevent reprocessing
        unset($_SESSION['booking']);

        // Load the success view
        $this->view('paymentSuccess',
            [
                'booking' => $booking,
                'worker' => $worker,
                'paymentInfo' => $paymentInfo
            ]
        );
    }

    public function cancel()
    {
        $response = ['status' => 'cancelled', 'message' => 'Payment Cancelled.'];
        //save the error message to the session
        $_SESSION['message'] = 'Payment Cancelled. Please try again.';
        $_SESSION['message_type'] = 'error';

        // Redirect to orderSummary page
        header('Location: ' . ROOT . '/public/booking/orderSummary');
    }

    public function clearSessionMessage()
    {
        // If method is called via fetch
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Clear the session message
            unset($_SESSION['message']);
            unset($_SESSION['message_type']);
            $this->jsonResponse(['status' => 'success', 'message' => 'Session message cleared']);
            return;
        }
    }

    public function notify()
    {
        // Get POST data
        $orderId = $_POST['order_id'] ?? null;
        $paymentStatus = $_POST['status_code'] ?? null;
        $amount = $_POST['payhere_amount'] ?? null;
        $currency = $_POST['payhere_currency'] ?? null;
        $paymentId = $_POST['payment_id'] ?? null;
        $merchantId = $_POST['merchant_id'] ?? null;
        $hashReceived = $_POST['md5sig'] ?? null;

        // Verify hash
        $hashCalculated = strtoupper(md5(
            $merchantId .
            $orderId .
            number_format($amount, 2, '.', '') .
            $currency .
            $paymentStatus .
            strtoupper(md5($this->merchantSecret))
        ));

        if ($hashCalculated == $hashReceived) {
            // Valid notification, process the payment
            $booking = $this->bookingModel->getBasicBookingData($orderId);

            if (!$booking) {
                $this->jsonResponse(['status' => 'error', 'message' => 'Booking not found'], 404);
                return;
            }

            // Check if payment already recorded
            $existingPayments = $this->paymentModel->findPaymentsByBookingID($orderId);

            if (empty($existingPayments)) {
                // Map PayHere status to our status
                $status = 'pending';
                switch ($paymentStatus) {
                    case 2: $status = 'completed'; break;
                    case 0: $status = 'cancelled'; break;
                    case -1: $status = 'pending'; break;
                    default: $status = 'failed'; break;
                }

                // Create payment record
                $paymentData = [
                    'bookingID' => $orderId,
                    'transactionID' => $paymentId,
                    'amount' => $amount,
                    'currency' => $currency,
                    'paymentMethod' => 'PayHere',
                    'paymentStatus' => $status,
                    'merchantReference' => $merchantId,
                    'responseData' => json_encode($_POST)
                ];

                $paymentID = $this->paymentModel->createPayment($paymentData);

                // Log the transaction
                $logData = [
                    'paymentID' => $paymentID,
                    'statusBefore' => 'new',
                    'statusAfter' => $status,
                    'amount' => $amount,
                    'notes' => 'Payment notification via PayHere webhook',
                    'ipAddress' => $_SERVER['REMOTE_ADDR'],
                    'userAgent' => $_SERVER['HTTP_USER_AGENT'] ?? 'PayHere Webhook'
                ];

                $this->paymentModel->logPaymentTransaction($logData);

                // Update booking status if payment complete
                if ($status == 'completed') {
                    $this->bookingModel->updateBookingStatus($orderId, 'confirmed');
                }

                $this->jsonResponse(['status' => 'success', 'message' => 'Payment processed']);
            } else {
                // Payment already exists, update status if needed
                $payment = $existingPayments[0];

                // Map PayHere status to our status
                $status = 'pending';
                switch ($paymentStatus) {
                    case 2: $status = 'completed'; break;
                    case 0: $status = 'cancelled'; break;
                    case -1: $status = 'pending'; break;
                    default: $status = 'failed'; break;
                }

                if ($payment->paymentStatus != $status) {
                    // Status changed, update payment
                    $this->paymentModel->updatePaymentStatus($payment->paymentID, $status, $_POST);

                    // Log the transaction
                    $logData = [
                        'paymentID' => $payment->paymentID,
                        'statusBefore' => $payment->paymentStatus,
                        'statusAfter' => $status,
                        'amount' => $amount,
                        'notes' => 'Payment status updated via PayHere webhook',
                        'ipAddress' => $_SERVER['REMOTE_ADDR'],
                        'userAgent' => $_SERVER['HTTP_USER_AGENT'] ?? 'PayHere Webhook'
                    ];

                    $this->paymentModel->logPaymentTransaction($logData);

                    // Update booking status if payment complete
                    if ($status == 'completed') {
                        $this->bookingModel->updateBookingStatus($orderId, 'confirmed');
                    }
                }

                $this->jsonResponse(['status' => 'success', 'message' => 'Payment updated']);
            }
        } else {
            // Invalid hash, potential security issue
            $this->jsonResponse(['status' => 'error', 'message' => 'Hash verification failed'], 400);
        }
    }

}
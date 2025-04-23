<?php

class Payment extends Controller
{
    private $userModel;
    private $merchantId;
    private $merchantSecret;

    public function __construct()
    {
        $this->userModel = new UserModel();
        // $this->paymentModel = new PaymentModel(); // Instantiate if needed
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

    public function success()
    {
        $response = ['status' => 'success', 'message' => 'Payment Successful!', 'order_id' => $_GET['order_id'] ?? null];
        header('Content-Type: application/json');
        echo json_encode($response);
    }

    public function cancel()
    {
        $response = ['status' => 'cancelled', 'message' => 'Payment Cancelled.'];
        header('Content-Type: application/json');
        echo json_encode($response);
    }

    public function notify()
    {
        header('Content-Type: application/json');
        $response = [];

        $merchantSecret = $this->merchantSecret;

        $orderId = $_POST['order_id'] ?? null;
        $paymentStatus = $_POST['status'] ?? null;
        $amount = $_POST['amount'] ?? null;
        $currency = $_POST['currency'] ?? null;
        $paymentId = $_POST['payment_id'] ?? null;
        $merchantId = $_POST['merchant_id'] ?? null;
        $hashReceived = $_POST['hash'] ?? null;

        if ($orderId && $paymentStatus !== null && $amount && $currency && $merchantId && $hashReceived) {
            $hashCalculated = strtoupper(md5($merchantId . $orderId . number_format($amount, 2, '.', '') . $currency . $paymentStatus . strtoupper(md5($merchantSecret))));

            if ($hashCalculated == $hashReceived) {
                if ($paymentStatus == 2) {
                    // Payment successful
                    // Update your database
                    $response = ['status' => 'success', 'message' => 'Payment successful.', 'order_id' => $orderId, 'payment_id' => $paymentId];
                    http_response_code(200);
                } else if ($paymentStatus == 0) {
                    // Payment cancelled
                    // Update your database
                    $response = ['status' => 'cancelled', 'message' => 'Payment cancelled.', 'order_id' => $orderId];
                    http_response_code(200);
                } else if ($paymentStatus == -1) {
                    // Payment pending
                    // Update your database
                    $response = ['status' => 'pending', 'message' => 'Payment pending.', 'order_id' => $orderId, 'payment_id' => $paymentId];
                    http_response_code(200);
                } else {
                    // Other payment statuses
                    $response = ['status' => 'other', 'message' => 'Payment status: ' . $paymentStatus, 'order_id' => $orderId];
                    http_response_code(200);
                }
            } else {
                // Hash mismatch - potential fraud
                $response = ['status' => 'error', 'message' => 'Hash mismatch!', 'received_hash' => $hashReceived, 'calculated_hash' => $hashCalculated, 'post_data' => $_POST];
                http_response_code(400);
            }
        } else {
            // Missing POST data
            $response = ['status' => 'error', 'message' => 'Missing required POST data.', 'post_data' => $_POST];
            http_response_code(400);
        }

        echo json_encode($response);
    }
}
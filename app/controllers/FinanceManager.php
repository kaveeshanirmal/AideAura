<?php

class FinanceManager extends Controller
{
    public function index($a = '', $b = '', $c = '')
    {
        //session_start();
        //echo "SESSION ROLE: " . ($_SESSION['role'] ?? 'not set');
        //exit;
        $this->paymentDetails();
    }

    public function paymentDetails()

    {

        $paymentModel = new PaymentModel();
        $paymentDetails = $paymentModel->getAllPaymentsWithBookingDetails(); // Fetch all payment details from the database
        $this->view('fm/paymentDetails',['paymentDetails'=>$paymentDetails]);
    }

    // public function paymentRates()
    // {
    //     $this->view('fm/paymentRates');
    // }

    // public function paymentRates()
    // {
    //     $paymentRateModel = new PaymentRateModel();
    //     $allRates = $paymentRateModel->getAllPaymentRates(); // Fetch all payment rateas from the database
        
    //      // Filter roles that doesn't delete 
    //      $filteredRates = array_filter($allRates, function($rate){
    //         return $rate->isDelete == 0;
    //     });

    //     if(empty($filteredRates)){
    //         error_log("No roles with specified roles retrieved or query failed");
    //     }
        
        
    //     $this->view('fm/paymentRates',['rates'=>$filteredRates]);
    // }

    public function priceData()
    {

        $pricingModel = new PricingModel();
        $priceData = $pricingModel->getAllPriceDetails(); // Fetch all payment rateas from the database
        
        //  // Filter roles that doesn't delete 
        // //  $filteredRates = array_filter($allRates, function($rate){
        // //     return $rate->isDelete == 0;
        // // });

        // if(empty($filteredRates)){
        //     error_log("No roles with specified roles retrieved or query failed");
        // }
        
        
        $this->view('fm/priceDetails', ['priceData' => $priceData]);

    }

    public function updatePriceDetails()
    {
        try {
            $data = json_decode(file_get_contents('php://input'), true);

            if (!isset($data['detailID'])) {
                throw new Exception('DetailID is required');
            }
            // if (!isset($data['categoryID'])) {
            //     throw new Exception('categoryID is required');
            // }

            $detailID = $data['detailID'];
            unset($data['detailID']); // Remove userID from update data
            // $categoryID = $data['categoryID'];
            // unset($data['categoryID']); // Remove userID from update data

            $priceData =[
                'detailName' => $data['detailName'] ?? NULL,
                'price' => $data['price'] ?? NULL,
                'description' => $data['description'] ?? NULL,
            ];

            // $categoryData = [
            //     'categoryName' => $data['categoryName'] ?? NULL,
            //     'description' => $data['categoryDescription'] ?? NULL,
            //     'displayName' => $data['categoryDisplayName'] ?? NULL,
            // ];

            $pricingModel = new PricingModel();
            $success = $pricingModel->updatePriceDetails($detailID, $priceData);
            // $success1 = $pricingModel->updatePriceDetails($detailID, $priceData);
            // $success2 = $pricingModel->updateCategoryDetails($categoryID, $categoryData);

            // $success = $success1 && $success2; // Combine both success statuses

            header('Content-Type: application/json');
            echo json_encode([
                'success' => $success,
                'message' => $success ? 'Price details updated successfully' : 'Failed to update price details'
            ]);
        } catch (Exception $e) {
            header('Content-Type: application/json');
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function updatePaymentRates() {
        try {
            // Ensure proper content type header is set first
            header('Content-Type: application/json');
            
            // Read raw POST data
            $rawData = file_get_contents('php://input');
            if (!$rawData) {
                throw new Exception('No data received');
            }
            
            // Decode JSON data
            $data = json_decode($rawData, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new Exception('Invalid JSON data: ' . json_last_error_msg());
            }
            
            // Validate required fields
            if (!$data || !isset($data['ServiceID'])) {
                throw new Exception('Invalid data. ServiceID is required.');
            }
            
            // Extract and validate data
            $ServiceID = $data['ServiceID'];
            $updateData = [
                'BasePrice' => isset($data['BasePrice']) ? (float) $data['BasePrice'] : null,
                'BaseHours' => isset($data['BaseHours']) ? (float) $data['BaseHours'] : null
            ];
            
            // Validate numeric values
            if ($updateData['BasePrice'] === null || $updateData['BaseHours'] === null) {
                throw new Exception('BasePrice and BaseHours are required and must be numeric.');
            }
            
            // Update payment rate
            $paymentRateModel = new PaymentRateModel();
            $success = $paymentRateModel->updatePayrate($ServiceID, $updateData);
            
            if (!$success) {
                throw new Exception('Failed to update payment rate.');
            }
            
            echo json_encode([
                'success' => true,
                'message' => 'Payment rates updated successfully'
            ]);
            
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }


    public function bookingReports()
    {
        
        require_once "../app/controllers/BookingReports.php";
        $bookingReportsController = new BookingReports();
        $bookingReportsController->roleIndex();
    }

        //payment issues function ..name might confuse lolz
    public function workerInquiries()
    {
        // We'll use the Complaint controller to handle this
        require_once "../app/controllers/Complaint.php";
        $complaintController = new Complaint();
        $complaintController->financeIndex();
    }

    public function workerComplaints()
    {
        require_once "../app/controllers/WorkerComplaint.php";
        $workerComplaintController = new WorkerComplaint();
        $workerComplaintController->financeIndex();
    }

}

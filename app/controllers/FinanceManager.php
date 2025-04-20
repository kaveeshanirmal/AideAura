<?php

class FinanceManager extends Controller
{
    public function index($a = '', $b = '', $c = '')
    {
        $this->view('fm/paymentHistory');
    }

    public function paymentHistory()
    {
        $this->view('fm/paymentHistory');
    }

    public function paymentIssues()
    {
        $this->view('fm/paymentIssues');
    }

    // public function paymentRates()
    // {
    //     $this->view('fm/paymentRates');
    // }

    public function paymentRates()
    {
        $paymentRateModel = new PaymentRateModel();
        $allRates = $paymentRateModel->getAllPaymentRates(); // Fetch all payment rateas from the database
        
         // Filter roles that doesn't delete 
         $filteredRates = array_filter($allRates, function($rate){
            return $rate->isDelete == 0;
        });

        if(empty($filteredRates)){
            error_log("No roles with specified roles retrieved or query failed");
        }
        
        
        $this->view('fm/paymentRates',['rates'=>$filteredRates]);
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


    public function reports()
    {
        $this->view('fm/reports');
    }
}

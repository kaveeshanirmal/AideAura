<?php

class BookingReports extends Controller
{
    private $reportsModel;

    public function __construct()
    {
        
        
        $this->reportsModel = new BookingReportsModel();
    }

    public function roleIndex()
    {
        // Get date range for the form default values
        $dateRange = $this->reportsModel->getBookingDateRange();
        
        $data = [
            'minDate' => $dateRange ? $dateRange->minDate : date('Y-m-d', strtotime('-1 year')),
            'maxDate' => $dateRange ? $dateRange->maxDate : date('Y-m-d')
        ];

        $this->view('admin/adminReports', $data);
    }

    public function getTotalRevenue()
    {
        header('Content-Type: application/json');
        
        try {
            // Validate input
            $startDate = isset($_GET['startDate']) ? $_GET['startDate'] : date('Y-m-d', strtotime('-1 year'));
            $endDate = isset($_GET['endDate']) ? $_GET['endDate'] : date('Y-m-d');
            
            // Validate date format
            if (!$this->validateDate($startDate) || !$this->validateDate($endDate)) {
                throw new Exception('Invalid date format');
            }
            
            $revenue = $this->reportsModel->getTotalRevenue($startDate, $endDate);
            
            echo json_encode([
                'success' => true,
                'data' => $revenue
            ]);
            
        } catch (Exception $e) {
            error_log("Error in getTotalRevenue: " . $e->getMessage());
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function getServiceTypeRevenue()
    {
        header('Content-Type: application/json');
        
        try {
            // Validate input
            $startDate = isset($_GET['startDate']) ? $_GET['startDate'] : date('Y-m-d', strtotime('-1 year'));
            $endDate = isset($_GET['endDate']) ? $_GET['endDate'] : date('Y-m-d');
            
            // Validate date format
            if (!$this->validateDate($startDate) || !$this->validateDate($endDate)) {
                throw new Exception('Invalid date format');
            }
            
            $revenue = $this->reportsModel->getServiceTypeRevenue($startDate, $endDate);
            
            echo json_encode([
                'success' => true,
                'data' => $revenue
            ]);
            
        } catch (Exception $e) {
            error_log("Error in getServiceTypeRevenue: " . $e->getMessage());
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
    
    public function getDailyRevenue()
    {
        header('Content-Type: application/json');
        
        try {
            // Validate input
            $year = isset($_GET['year']) ? (int)$_GET['year'] : (int)date('Y');
            $month = isset($_GET['month']) ? (int)$_GET['month'] : (int)date('m');
            
            // Validate year and month
            if ($year < 2000 || $year > 2100 || $month < 1 || $month > 12) {
                throw new Exception('Invalid year or month');
            }
            
            $revenue = $this->reportsModel->getDailyRevenue($year, $month);
            
            echo json_encode([
                'success' => true,
                'data' => $revenue
            ]);
            
        } catch (Exception $e) {
            error_log("Error in getDailyRevenue: " . $e->getMessage());
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
    
    // Helper function to validate date format
    private function validateDate($date, $format = 'Y-m-d')
    {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) === $date;
    }
}
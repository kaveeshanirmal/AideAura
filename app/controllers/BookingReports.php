<?php
class BookingReports extends Controller
{
    public function roleIndex()
    {
        // Render the booking reports view
        $this->view('admin/adminReports');
    }
    
    // API endpoint for worker booking statistics
    public function worker_stats()
    {
        // Set response header to JSON
        header('Content-Type: application/json');
        
        // Check if request is POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'error' => 'Method not allowed']);
            return;
        }
        
        // Get POST data
        $data = json_decode(file_get_contents('php://input'), true);
        
        // Initialize booking model
        $bookingModel = new BookingModel();
        
        // Get worker booking statistics
        $stats = $bookingModel->getWorkerBookingStats(
            $data['workerID'] ?? null,
            $data['startDate'] ?? null,
            $data['endDate'] ?? null
        );
        
        // Return JSON response
        echo json_encode([
            'success' => true,
            'data' => $stats
        ]);
    }
    
    // API endpoint for service category statistics
    public function service_stats()
    {
        // Set response header to JSON
        header('Content-Type: application/json');
        
        // Check if request is POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'error' => 'Method not allowed']);
            return;
        }
        
        // Get POST data
        $data = json_decode(file_get_contents('php://input'), true);
        
        // Initialize booking model
        $bookingModel = new BookingModel();
        
        // Get service category statistics
        $stats = $bookingModel->getServiceCategoryStats(
            $data['serviceType'] ?? null,
            $data['startDate'] ?? null,
            $data['endDate'] ?? null
        );
        
        // Return JSON response
        echo json_encode([
            'success' => true,
            'data' => $stats
        ]);
    }
    
    // API endpoint for revenue trend data
    public function revenue_trend()
    {
        // Set response header to JSON
        header('Content-Type: application/json');
        
        // Check if request is POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'error' => 'Method not allowed']);
            return;
        }
        
        
        // Get POST data
        $data = json_decode(file_get_contents('php://input'), true);
        
        // Initialize booking model
        $bookingModel = new BookingModel();
        
        // Get revenue trend data
        $stats = $bookingModel->getRevenueTrend(
            $data['period'] ?? 'weekly',
            $data['startDate'] ?? null,
            $data['endDate'] ?? null
        );
        
        // Return JSON response
        echo json_encode([
            'success' => true,
            'data' => $stats
        ]);
    }

    
    

}
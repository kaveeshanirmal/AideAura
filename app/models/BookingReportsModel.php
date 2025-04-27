<?php
class BookingReportsModel
{
    use Model;
    
    public function __construct()
    {
        $this->setTable('bookings');
    }
    
    /**
     * Get total revenue for a specified date range
     */
    public function getTotalRevenue($startDate, $endDate)
    {
        try {
            $query = "SELECT SUM(totalCost) as totalRevenue, 
                      COUNT(*) as totalBookings,
                      DATE_FORMAT(bookingDate, '%Y-%m') as monthYear
                      FROM bookings 
                      WHERE bookingDate BETWEEN :startDate AND :endDate
                      AND status IN ('completed', 'confirmed')
                      GROUP BY monthYear
                      ORDER BY monthYear ASC";
            
            $params = [
                ':startDate' => $startDate,
                ':endDate' => $endDate
            ];
            
            return $this->get_all($query, $params);
        } catch (Exception $e) {
            error_log("Error getting total revenue: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get revenue breakdown by service type for a specified date range
     */
    public function getServiceTypeRevenue($startDate, $endDate)
    {
        try {
            $query = "SELECT serviceType, 
                      SUM(totalCost) as revenue, 
                      COUNT(*) as bookings,
                      DATE_FORMAT(bookingDate, '%Y-%m') as monthYear
                      FROM bookings 
                      WHERE bookingDate BETWEEN :startDate AND :endDate
                      AND status IN ('completed', 'confirmed')
                      GROUP BY serviceType, monthYear
                      ORDER BY serviceType, monthYear ASC";
            
            $params = [
                ':startDate' => $startDate,
                ':endDate' => $endDate
            ];
            
            return $this->get_all($query, $params);
        } catch (Exception $e) {
            error_log("Error getting service type revenue: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get available date range for bookings in the system
     */
    public function getBookingDateRange()
    {
        try {
            $query = "SELECT MIN(bookingDate) as minDate, MAX(bookingDate) as maxDate 
                      FROM bookings 
                      WHERE status IN ('completed', 'confirmed')";
            
            return $this->get_row($query);
        } catch (Exception $e) {
            error_log("Error getting booking date range: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Get daily revenue for a specific month
     */
    public function getDailyRevenue($year, $month)
    {
        try {
            $query = "SELECT DAY(bookingDate) as day,
                      SUM(totalCost) as revenue
                      FROM bookings
                      WHERE YEAR(bookingDate) = :year
                      AND MONTH(bookingDate) = :month
                      AND status IN ('completed', 'confirmed')
                      GROUP BY DAY(bookingDate)
                      ORDER BY day ASC";
            
            $params = [
                ':year' => $year,
                ':month' => $month
            ];
            
            return $this->get_all($query, $params);
        } catch (Exception $e) {
            error_log("Error getting daily revenue: " . $e->getMessage());
            return [];
        }
    }
}
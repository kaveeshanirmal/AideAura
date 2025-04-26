<?php

class BookingModel
{
    use Model;

    public function __construct()
    {
        $this->setTable('bookings');
    }
    

    public function createBooking($workerID, $customerID, $serviceType, $bookingDate, $serviceLocation, $arrivalTime, $totalCost, $details)
    {
        $data = [
            'customerID' => $customerID,
            'workerID' => $workerID,
            'serviceType' => $serviceType,
            'bookingDate' => $bookingDate,
            'startTime' => $arrivalTime,
            'location' => $serviceLocation,
            'totalCost' => $totalCost,
        ];

        // Insert booking into the database
        $this->setTable('bookings');
        $bookingID = $this->insertAndGetId($data);

        //Iteratively insert all details regarding the booking into bookingDetails
        foreach ($details as $detail) {
            $excludedKeys = ['gender', 'data_acknowledgment'];
            if (in_array($detail->key, $excludedKeys)) {
                continue; // Skip excluded keys
            }

            $detailData = [
                'bookingID' => $bookingID,
                'detailType' => $detail->key,
                'detailValue' => is_array($detail->value) ? json_encode($detail->value) : $detail->value,
            ];
            $this->setTable('booking_details');
            $this->insert($detailData);
        }

        // Update the worker's availability status
//        $this->setTable('worker');
//        $this->update(['availability_status' => 'busy'], ['workerID' => $workerID]);

        return $bookingID;
    }

    public function updateBookingStatus($bookingID, $status)
    {
        $this->setTable('bookings');
        return $this->update($bookingID, ['status' => $status], 'bookingID');
    }

    public function getBookingDetails($bookingID)
    {
        $this->setTable('bookings');
        $booking = $this->find($bookingID, 'bookingID');
        if ($booking) {
            $this->setTable('booking_details');
            $details = $this->get_all("SELECT * FROM booking_details WHERE bookingID = :bookingID", ['bookingID' => $bookingID]);
            return [
                'booking' => $booking,
                'details' => $details
            ];
        }
        return null;
    }

    public function getCustomerIdByBookingId($bookingID)
    {
        $this->setTable('bookings');
        $customer = $this->find($bookingID, 'bookingID');
        return $customer ? $customer->customerID : null;
    }

    public function getWorkerIdByBookingId($bookingID)
    {
        $this->setTable('bookings');
        $worker = $this->find($bookingID, 'bookingID');
        return $worker ? $worker->workerID : null;
    }

    public function getStatusByBookingId($bookingID)
    {
        $this->setTable('bookings');
        $status = $this->find($bookingID, 'bookingID');
        return $status ? $status->status : null;
    }

    //REPORTS
    /**
     * Get booking statistics for a specific worker
     * 
     * @param string|null $workerID Worker ID to filter by
     * @param string|null $startDate Start date for filtering (YYYY-MM-DD)
     * @param string|null $endDate End date for filtering (YYYY-MM-DD)
     * @return array Statistics including status distribution, day of week, and summary
     */
    public function getWorkerBookingStats($workerID = null, $startDate = null, $endDate = null)
    {
        // Initialize results array
        $results = [
            'status_distribution' => [],
            'day_of_week' => [],
            'summary' => null
        ];

        try {
            // Validate inputs
            if (empty($workerID)) {
                return $results;
            }

            // Base conditions
            $conditions = ["workerID = :workerID"];
            $params = [':workerID' => $workerID];

            // Add date range if provided
            if ($startDate && $endDate) {
                $conditions[] = "date_booked BETWEEN :startDate AND :endDate";
                $params[':startDate'] = $startDate;
                $params[':endDate'] = $endDate;
            }

            $whereClause = !empty($conditions) ? "WHERE " . implode(" AND ", $conditions) : "";

            // Get booking status distribution
            $statusQuery = "SELECT 
                status, 
                COUNT(*) as count 
                FROM bookings 
                $whereClause 
                GROUP BY status";

            // Replace the query, bindParams, resultSet methods
            $this->setTable('bookings');
            $results['status_distribution'] = $this->get_all($statusQuery, $params);

            // Get day of week distribution
            $dayOfWeekQuery = "SELECT 
                DAYOFWEEK(date_booked) as day_num, 
                COUNT(*) as count 
                FROM bookings 
                $whereClause 
                GROUP BY DAYOFWEEK(date_booked)";

            // Using get_all instead of query/bindParams/resultSet
            $results['day_of_week'] = $this->get_all($dayOfWeekQuery, $params);

            // Get summary statistics
            $summaryQuery = "SELECT 
                COUNT(*) as total_bookings,
                SUM(estimated_cost) as total_cost,
                AVG(estimated_cost) as avg_cost
                FROM bookings 
                $whereClause";

            // Using get_row instead of query/bindParams/single
            $results['summary'] = $this->get_row($summaryQuery, $params);

            return $results;
        } catch (Exception $e) {
            error_log('Error in getWorkerBookingStats: ' . $e->getMessage());
            return $results;
        }
    }

    /**
     * Get statistics for service categories
     * 
     * @param string|null $serviceType Service type to filter by (optional)
     * @param string|null $startDate Start date for filtering (YYYY-MM-DD)
     * @param string|null $endDate End date for filtering (YYYY-MM-DD)
     * @return array Statistics including category revenue and summary
     */
    public function getServiceCategoryStats($serviceType = null, $startDate = null, $endDate = null)
    {
        // Initialize results array
        $results = [
            'category_revenue' => [],
            'summary' => null
        ];

        try {
            // Base conditions
            $conditions = [];
            $params = [];

            // Add service type filter if provided
            if (!empty($serviceType) && $serviceType !== 'all') {
                $conditions[] = "serviceType = :serviceType";
                $params[':serviceType'] = $serviceType;
            }

            // Add date range if provided
            if ($startDate && $endDate) {
                $conditions[] = "date_booked BETWEEN :startDate AND :endDate";
                $params[':startDate'] = $startDate;
                $params[':endDate'] = $endDate;
            }

            $whereClause = !empty($conditions) ? "WHERE " . implode(" AND ", $conditions) : "";

            // Get category revenue
            $categoryQuery = "SELECT 
                serviceType, 
                COUNT(*) as booking_count,
                SUM(estimated_cost) as total_cost
                FROM bookings 
                $whereClause 
                GROUP BY serviceType";

            // Using get_all instead of query/bindParams/resultSet
            $this->setTable('bookings');
            $results['category_revenue'] = $this->get_all($categoryQuery, $params);

            // Get summary statistics
            $summaryQuery = "SELECT 
                COUNT(*) as total_bookings,
                SUM(estimated_cost) as total_cost
                FROM bookings 
                $whereClause";

            // Using get_row instead of query/bindParams/single
            $results['summary'] = $this->get_row($summaryQuery, $params);

            return $results;
        } catch (Exception $e) {
            error_log('Error in getServiceCategoryStats: ' . $e->getMessage());
            return $results;
        }
    }

    /**
     * Get revenue trend data over time
     * 
     * @param string $period Period type (daily, weekly, monthly)
     * @param string|null $startDate Start date for filtering (YYYY-MM-DD)
     * @param string|null $endDate End date for filtering (YYYY-MM-DD)
     * @return array Trend data and summary statistics
     */
    public function getRevenueTrend($period = 'weekly', $startDate = null, $endDate = null)
    {
        // Initialize results array
        $results = [
            'trend_data' => [],
            'summary' => null
        ];

        try {
            // Validate inputs
            if (!in_array($period, ['daily', 'weekly', 'monthly'])) {
                $period = 'weekly';
            }

            // Base conditions
            $conditions = [];
            $params = [];

            // Add date range if provided
            if ($startDate && $endDate) {
                $conditions[] = "date_booked BETWEEN :startDate AND :endDate";
                $params[':startDate'] = $startDate;
                $params[':endDate'] = $endDate;
            }

            $whereClause = !empty($conditions) ? "WHERE " . implode(" AND ", $conditions) : "";

            // Group by expression based on period
            $groupBy = '';
            $datePart = '';
            switch ($period) {
                case 'daily':
                    $groupBy = "DATE(date_booked)";
                    $datePart = "DATE_FORMAT(date_booked, '%Y-%m-%d')";
                    break;
                case 'weekly':
                    $groupBy = "YEARWEEK(date_booked, 1)";
                    $datePart = "CONCAT('Week ', WEEK(date_booked, 1), ', ', YEAR(date_booked))";
                    break;
                case 'monthly':
                    $groupBy = "DATE_FORMAT(date_booked, '%Y-%m')";
                    $datePart = "DATE_FORMAT(date_booked, '%b %Y')";
                    break;
            }

            // Get trend data
            $trendQuery = "SELECT 
                $datePart as period_label,
                $groupBy as period_value,
                COUNT(*) as booking_count,
                SUM(estimated_cost) as total_cost
                FROM bookings 
                $whereClause 
                GROUP BY $groupBy
                ORDER BY period_value";

            // Using get_all instead of query/bindParams/resultSet
            $this->setTable('bookings');
            $results['trend_data'] = $this->get_all($trendQuery, $params);

            // Get summary statistics
            $summaryQuery = "SELECT 
                COUNT(*) as total_bookings,
                SUM(estimated_cost) as total_cost,
                AVG(estimated_cost) as avg_cost
                FROM bookings 
                $whereClause";

            // Using get_row instead of query/bindParams/single
            $results['summary'] = $this->get_row($summaryQuery, $params);

            return $results;
        } catch (Exception $e) {
            error_log('Error in getRevenueTrend: ' . $e->getMessage());
            return $results;
        }
    }

    public function hasUnconfirmedBookings($customerID) {
        $this->setTable('bookings');
        $query = "SELECT * FROM bookings 
              WHERE customerID = :customerID 
                AND (status = 'pending' OR status = 'accepted')";
        $result = $this->get_all($query, ['customerID' => $customerID]);
        return ($result !== false) && !empty($result);
    }

    public function getUnconfirmedBookings($customerID)
    {
        $this->setTable('bookings');
        $query = "SELECT * FROM bookings WHERE customerID = :customerID AND status = 'pending' OR status = 'accepted'";
        return $this->get_all($query, ['customerID' => $customerID]);
    }

    public function getBasicBookingData($bookingID)
    {
        $this->setTable('bookings');
        return $this->find($bookingID, 'bookingID');
    }

    public function getBookingAllDetails()
    {
    $this->setTable('bookings');
    $sql = "SELECT 
        b.*, -- All columns from bookings table
        bd.*, -- All columns from booking_details table
        CONCAT(cu.firstName, ' ', cu.lastName) AS customerName,
        CONCAT(wu.firstName, ' ', wu.lastName) AS workerName
    FROM bookings b
    JOIN booking_details bd ON b.bookingID = bd.bookingID
    JOIN customer c ON b.customerID = c.customerID
    JOIN users cu ON c.userID = cu.userID
    JOIN worker w ON b.workerID = w.workerID
    JOIN users wu ON w.userID = wu.userID;
    ";

        return $this->get_all($sql,[]);
            } 

    public function deleteUnconfirmedBooking($bookingID)
    {
        $this->setTable('bookings');
        return $this->delete($bookingID, 'bookingID');
    }

    public function verifyAndCompleteBooking($bookingID, $verificationCode)
    {
        $this->setTable('bookings');
        $booking = $this->find($bookingID, 'bookingID');

        // if verificationCode is valid and bookingDate is 2 days within past
        if ($booking && $booking->verificationCode === $verificationCode) {
            $currentDate = new DateTime();
            $bookingDate = new DateTime($booking->bookingDate);
            $interval = $currentDate->diff($bookingDate);

            // Check if booking date is within the past 2 days
            if ($interval->days <= 2 && $interval->invert == 1) {
                // Update booking status to 'confirmed'
                $this->updateBookingStatus($bookingID, 'completed');
                return true;
            }
        }
        return false;
    }

    public function hasExpiredBookings($workerID)
    {
        $this->setTable('bookings');
        // Check for bookings those which bookingDate is past 2 days
        $query = "SELECT * FROM bookings WHERE workerID = :workerID 
                         AND bookingDate < NOW() - INTERVAL 2 DAY
                         AND status = 'expired'";
        $result = $this->get_all($query, ['workerID' => $workerID]);
        return ($result !== false) && !empty($result);
    }
    public function getExpiredBookings($workerID)
    {
        $this->setTable('bookings');
        // Get all expired bookings
        $query = "SELECT * FROM bookings WHERE workerID = :workerID 
                         AND bookingDate < NOW() - INTERVAL 2 DAY
                         AND status = 'expired'";
        return $this->get_all($query, ['workerID' => $workerID]);
    }

    public function hasUncompletedBookings($workerID)
    {
        $this->setTable('bookings');
        // Check for confirmed bookings that happened in the past 2 days
        $query = "SELECT * FROM bookings WHERE workerID = :workerID 
                 AND bookingDate > (NOW() - INTERVAL 2 DAY)
                 AND bookingDate < NOW()
                 AND status = 'confirmed'";
        $result = $this->get_all($query, ['workerID' => $workerID]);
        return ($result !== false) && !empty($result);
    }

    public function getUncompletedBookings($workerID)
    {
        $this->setTable('bookings');
        // Get confirmed bookings that happened in the past 2 days
        $query = "SELECT * FROM bookings WHERE workerID = :workerID 
                 AND bookingDate > (NOW() - INTERVAL 2 DAY)
                 AND bookingDate < NOW()
                 AND status = 'confirmed'";
        return $this->get_all($query, ['workerID' => $workerID]);
    }
}
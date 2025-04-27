<?php

class BookingReviewModel
{
    use Model;

    public function __construct()
    {
        $this->setTable('booking_reviews');
    }

    /**
     * Get completed bookings that haven't been reviewed by the customer
     * 
     * @param int $customerID The customer ID
     * @return array List of completed bookings without reviews
     */
    public function getCompletedBookingsWithoutReviews($customerID)
{
    $query = "SELECT b.bookingID, b.workerID, b.serviceType, b.bookingDate 
              FROM bookings b 
              LEFT JOIN booking_reviews r ON b.bookingID = r.bookingID 
              WHERE b.customerID = :customerID 
              AND b.status = 'completed' 
              AND r.reviewID IS NULL 
              ORDER BY b.bookingDate DESC, b.bookingID DESC 
              LIMIT 10"; // Limit to 10 but we'll only show the first one in JS
    
    $params = [':customerID' => $customerID];
    return $this->get_all($query, $params);
}

    /**
     * Create a new review
     * 
     * @param array $data Review data
     * @return int|bool The new review ID on success, false on failure
     */
    public function createReview($data)
    {
        // Add created_at timestamp
        $data['created_at'] = date('Y-m-d H:i:s');
        
        // Insert review
        return $this->insert($data);
    }

    /**
     * Check if a review already exists for a booking
     * 
     * @param int $bookingID The booking ID
     * @return bool True if review exists, false otherwise
     */
    public function checkReviewExists($bookingID)
    {
        $query = "SELECT COUNT(*) as count FROM booking_reviews WHERE bookingID = :bookingID";
        $result = $this->get_row($query, [':bookingID' => $bookingID]);
        
        return ($result && $result->count > 0);
    }

    /**
     * Get worker name from the users table by properly joining with workers table
     *
     * @param int $workerID The worker ID
     * @return object|null Worker name object with firstName and lastName properties
     */
    public function getWorkerName($workerID)
    {
        $query = "SELECT u.firstName, u.lastName 
                FROM users u
                JOIN worker w ON u.userID = w.userID
                WHERE w.workerID = :workerID";
        
        return $this->get_row($query, [':workerID' => $workerID]);
    }

    /**
     * Get average rating for a worker
     * 
     * @param int $workerID The worker ID
     * @return float Average rating
     */
    public function getWorkerAverageRating($workerID)
    {
        $query = "SELECT AVG(rating) as avg_rating FROM booking_reviews WHERE workerID = :workerID";
        $result = $this->get_row($query, [':workerID' => $workerID]);
        
        return ($result && $result->avg_rating) ? round($result->avg_rating, 1) : 0;
    }

    /**
     * Get review count for a worker
     * 
     * @param int $workerID The worker ID
     * @return int Number of reviews
     */
    public function getWorkerReviewCount($workerID)
    {
        $query = "SELECT COUNT(*) as count FROM booking_reviews WHERE workerID = :workerID";
        $result = $this->get_row($query, [':workerID' => $workerID]);
        
        return ($result) ? $result->count : 0;
    }
}
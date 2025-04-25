<!-- Review Modal -->
<div class="review-modal-overlay" id="reviewModalOverlay">
    <div class="review-modal">
        <div class="review-modal-header">
            <h3>Rate Your Experience</h3>
            <button class="close-modal">&times;</button>
        </div>
        
        <div class="worker-info">
            <div class="worker-avatar">
                <span id="workerInitials">JD</span>
            </div>
            <div class="worker-details">
                <h4 id="workerName">John Doe</h4>
                <div class="service-type" id="serviceType">Loading service...</div>
            </div>
        </div>
        
        <form id="reviewForm">
            <input type="hidden" id="bookingID" name="bookingID" value="">
            <input type="hidden" id="workerID" name="workerID" value="">
            
            <div class="star-rating">
                <div class="star-rating-text">How was the service?</div>
                <div class="stars">
                    <span class="star" data-value="1">★</span>
                    <span class="star" data-value="2">★</span>
                    <span class="star" data-value="3">★</span>
                    <span class="star" data-value="4">★</span>
                    <span class="star" data-value="5">★</span>
                </div>
                <input type="hidden" id="ratingValue" name="rating" value="0">
            </div>
            
            <div class="comment-section">
                <label for="reviewComment">Additional feedback (optional)</label>
                <textarea id="reviewComment" name="comment" placeholder="Share your experience..."></textarea>
            </div>
            
            <button type="submit" class="submit-review">Submit Review</button>
        </form>
    </div>
</div>

<style>
/* Modal Background */
.review-modal-overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    z-index: 1000;
    justify-content: center;
    align-items: center;
}

/* Modal Content */
.review-modal {
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    padding: 25px;
    width: 90%;
    max-width: 500px;
    position: relative;
}

/* Modal Header */
.review-modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.review-modal-header h3 {
    font-size: 22px;
    margin: 0;
    color: #333;
}

/* Close Button */
.close-modal {
    background: none;
    border: none;
    font-size: 24px;
    cursor: pointer;
    color: #777;
}

/* Worker Info */
.worker-info {
    display: flex;
    align-items: center;
    margin-bottom: 20px;
}

.worker-avatar {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background-color: #e9e9e9;
    margin-right: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    color: #777;
}

.worker-details h4 {
    margin: 0 0 5px;
    font-size: 18px;
}

.service-type {
    color: #666;
    font-size: 14px;
}

/* Star Rating */
.star-rating {
    margin: 20px 0;
}

.star-rating-text {
    margin-bottom: 10px;
    font-weight: 500;
}

.stars {
    display: flex;
    gap: 10px;
}

.star {
    color: #ddd;
    font-size: 30px;
    cursor: pointer;
}

.star.active {
    color: #ffc107;
}

/* Comment Section */
.comment-section {
    margin: 20px 0;
}

.comment-section label {
    display: block;
    margin-bottom: 8px;
    font-weight: 500;
}

.comment-section textarea {
    width: 100%;
    padding: 12px;
    border: 1px solid #ddd;
    border-radius: 4px;
    resize: vertical;
    min-height: 100px;
    font-family: inherit;
}

/* Submit Button */
.submit-review {
    background-color: #4CAF50;
    color: white;
    border: none;
    padding: 12px 20px;
    border-radius: 4px;
    cursor: pointer;
    font-size: 16px;
    font-weight: 500;
    width: 100%;
    margin-top: 20px;
    transition: background-color 0.2s;
}

.submit-review:hover {
    background-color: #45a049;
}

/* Responsive adjustments */
@media (max-width: 600px) {
    .review-modal {
        width: 95%;
        padding: 15px;
    }
    
    .star {
        font-size: 26px;
    }
}
</style>
<!-- Review Modal -->
<div class="review-modal-overlay" id="reviewModalOverlay">
    <div class="review-modal">
        <div class="review-modal-header">
            <h3>Rate Your Experience</h3>
            <button class="close-modal">&times;</button>
        </div>
        
        <div class="worker-info">
            <div class="worker-avatar" id="workerAvatarContainer">
                <!-- Worker profile image will be set via JS -->
                <img id="workerAvatar" src="" alt="Worker Avatar" 
                     onerror="this.style.display='none'; document.getElementById('workerInitials').style.display='flex';">
                <span id="workerInitials"></span>
            </div>
            <div class="worker-details">
                <h4 id="workerName">Loading Name...</h4>
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
:root {
    --primary: #7f5539;
    --primary-light: #a87c5b;
    --primary-dark: #6b4830;
}

/* Modal Background */
.review-modal-overlay {
    display: flex; /* Changed from 'none' to 'flex' for testing */
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.6);
    z-index: 1000;
    justify-content: center;
    align-items: center;
}

/* Modal Content */
.review-modal {
    background-color: #fff;
    border-radius: 12px;
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
    padding: 30px;
    width: 90%;
    max-width: 500px;
    position: relative;
}

/* Modal Header */
.review-modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 24px;
}

.review-modal-header h3 {
    font-size: 24px;
    margin: 0;
    color: var(--primary);
    font-weight: 600;
}

/* Close Button */
.close-modal {
    background: none;
    border: none;
    font-size: 28px;
    cursor: pointer;
    color: #777;
    transition: color 0.2s;
}

.close-modal:hover {
    color: var(--primary);
}

/* Worker Info */
.worker-info {
    display: flex;
    align-items: center;
    margin-bottom: 24px;
    padding: 10px;
    background-color: #f9f5f1;
    border-radius: 8px;
}

.worker-avatar {
    width: 64px;
    height: 64px;
    border-radius: 50%;
    background-color: var(--primary);
    margin-right: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    color: white;
    font-weight: 500;
    position: relative;
    overflow: hidden;
}

.worker-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    position: absolute;
    top: 0;
    left: 0;
}

.worker-avatar span {
    display: none; /* Hidden by default, will be shown if image fails to load */
}

.worker-details h4 {
    margin: 0 0 5px;
    font-size: 18px;
    color: var(--primary-dark);
}

.service-type {
    color: var(--primary-light);
    font-size: 14px;
    font-weight: 500;
}

/* Star Rating */
.star-rating {
    margin: 24px 0;
}

.star-rating-text {
    margin-bottom: 12px;
    font-weight: 500;
    color: #444;
}

.stars {
    display: flex;
    gap: 12px;
    justify-content: center;
}

.star {
    color: #ddd;
    font-size: 36px;
    cursor: pointer;
    transition: transform 0.2s, color 0.2s;
}

.star:hover {
    transform: scale(1.1);
}

.star.active {
    color: #FFB800;
}

/* Comment Section */
.comment-section {
    margin: 24px 0;
}

.comment-section label {
    display: block;
    margin-bottom: 10px;
    font-weight: 500;
    color: #444;
}

.comment-section textarea {
    width: 100%;
    padding: 14px;
    border: 1px solid #e0d5cc;
    border-radius: 8px;
    resize: vertical;
    min-height: 120px;
    font-family: inherit;
    transition: border-color 0.3s;
}

.comment-section textarea:focus {
    outline: none;
    border-color: var(--primary-light);
    box-shadow: 0 0 0 2px rgba(168, 124, 91, 0.2);
}

/* Submit Button */
.submit-review {
    background: linear-gradient(135deg, var(--primary-light), var(--primary));
    color: white;
    border: none;
    padding: 14px 24px;
    border-radius: 8px;
    cursor: pointer;
    font-size: 16px;
    font-weight: 600;
    width: 100%;
    margin-top: 24px;
    transition: all 0.3s;
    box-shadow: 0 4px 8px rgba(127, 85, 57, 0.3);
}

.submit-review:hover {
    background: linear-gradient(135deg, var(--primary), var(--primary-dark));
    transform: translateY(-2px);
    box-shadow: 0 6px 12px rgba(127, 85, 57, 0.4);
}

.submit-review:active {
    transform: translateY(1px);
    box-shadow: 0 2px 4px rgba(127, 85, 57, 0.4);
}

/* Responsive adjustments */
@media (max-width: 600px) {
    .review-modal {
        width: 95%;
        padding: 20px;
    }
    
    .star {
        font-size: 32px;
    }
}
</style>
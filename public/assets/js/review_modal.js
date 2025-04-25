document.addEventListener('DOMContentLoaded', function() {
    //had to add this since i dont have separate dashboards for customers and workers
    const isCustomer = sessionStorage.getItem('role') === 'customer';
    
    // Only run for customers - removed duplicate call
    if (isCustomer) {
        checkForPendingReviews();
    }
    
    
    // Elements
    const modal = document.getElementById('reviewModalOverlay');

    
     // Check if modal exists before trying to use it
     if (!modal) {
         console.error("Review modal element not found in the DOM. Make sure review_modal.php is included correctly.");
         return; // Exit the script if modal doesn't exist
     }
     
    const closeBtn = modal.querySelector('.close-modal');
    const stars = modal.querySelectorAll('.star');
    const ratingInput = document.getElementById('ratingValue');
    const reviewForm = document.getElementById('reviewForm');
    
    // Close modal when clicking X
    closeBtn.addEventListener('click', function() {
        hideModal();
    });
    
    // Close modal when clicking outside
    modal.addEventListener('click', function(event) {
        if (event.target === modal) {
            hideModal();
        }
    });
    
    // Star rating functionality
    stars.forEach(star => {
        star.addEventListener('mouseover', function() {
            const value = this.getAttribute('data-value');
            highlightStars(value);
        });
        
        star.addEventListener('mouseout', function() {
            const currentRating = ratingInput.value;
            highlightStars(currentRating);
        });
        
        star.addEventListener('click', function() {
            const value = this.getAttribute('data-value');
            ratingInput.value = value;
            highlightStars(value);
        });
    });
    
    // Submit form
    reviewForm.addEventListener('submit', function(event) {
        event.preventDefault();
        
        // Validate rating is selected
        if (ratingInput.value === '0') {
            alert('Please select a rating');
            return;
        }
        
        // Get form data
        const formData = new FormData(reviewForm);
        
        // Send via AJAX
        submitReview(formData);
    });
    
    // Functions
    function highlightStars(value) {
        stars.forEach(star => {
            const starValue = star.getAttribute('data-value');
            if (starValue <= value) {
                star.classList.add('active');
            } else {
                star.classList.remove('active');
            }
        });
    }
    
    function showModal(bookingData) {
        // Set modal data
        document.getElementById('bookingID').value = bookingData.bookingID;
        document.getElementById('workerID').value = bookingData.workerID;
        document.getElementById('workerName').textContent = bookingData.firstName + ' ' + bookingData.lastName;
        document.getElementById('serviceType').textContent = bookingData.serviceType;
        
        // Set worker avatar initials
        const initials = bookingData.firstName.charAt(0) + bookingData.lastName.charAt(0);
        document.getElementById('workerInitials').textContent = initials;
        
        // Reset form
        ratingInput.value = '0';
        document.getElementById('reviewComment').value = '';
        highlightStars(0);
        
        // Show modal
        modal.style.display = 'flex';
        
        // Save to session that we've shown this review
        saveReviewShown(bookingData.bookingID);
    }
    
    function hideModal() {
        modal.style.display = 'none';
    }
    
    function checkForPendingReviews() {
        // AJAX call to backend to check for pending reviews
        fetch(`${ROOT_URL}/public/bookingReview/checkPendingReviews`)
            .then(response => response.json())
            .then(data => {
                if (data.success && data.pendingReviews && data.pendingReviews.length > 0) {
                    // Get first pending review that hasn't been shown yet
                    const reviewToShow = data.pendingReviews.find(review => !isReviewShown(review.bookingID));
                    
                    if (reviewToShow) {
                        showModal(reviewToShow);
                    }
                }
            })
            .catch(error => console.error('Error checking for pending reviews:', error));
    }
    
    function submitReview(formData) {
        fetch(`${ROOT_URL}/public/bookingReview/submitReview`, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                hideModal();
                // Show success message
                alert('Thank you for your review!');
                
                // Check if there are more pending reviews
                checkForPendingReviews();
            } else {
                alert(data.error || 'There was an error submitting your review. Please try again.');
            }
        })
        .catch(error => {
            console.error('Error submitting review:', error);
            alert('There was an error submitting your review. Please try again.');
        });
    }
    
    // Session storage to avoid showing the same review multiple times in one session
    function isReviewShown(bookingID) {
        const shownReviews = JSON.parse(sessionStorage.getItem('shownReviews') || '[]');
        return shownReviews.includes(parseInt(bookingID));
    }
    
    function saveReviewShown(bookingID) {
        const shownReviews = JSON.parse(sessionStorage.getItem('shownReviews') || '[]');
        if (!shownReviews.includes(parseInt(bookingID))) {
            shownReviews.push(parseInt(bookingID));
            sessionStorage.setItem('shownReviews', JSON.stringify(shownReviews));
        }
    }
});
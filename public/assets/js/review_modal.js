document.addEventListener('DOMContentLoaded', function() {
    console.log("Review modal JS loaded!");
    
    // Check role
    const isCustomer = true; // We'll rely on PHP session to check this now
    
    // Elements
    const modal = document.getElementById('reviewModalOverlay');
    console.log("Modal element found:", !!modal);
    
    // Check if modal exists before trying to use it
    if (!modal) {
        console.error("Review modal element not found in the DOM.");
        return; // Exit the script if modal doesn't exist
    }
    
    const closeBtn = modal.querySelector('.close-modal');
    const stars = modal.querySelectorAll('.star');
    const ratingInput = document.getElementById('ratingValue');
    const reviewForm = document.getElementById('reviewForm');
    
    // Initialize event listeners
    initializeEventListeners();
    
    // Check for pending reviews
    checkForPendingReviews();
    
    // Initialize all event listeners
    function initializeEventListeners() {
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
    }
    
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
        console.log("Showing modal with data:", bookingData);
        
        try {
            // Set modal data
            document.getElementById('bookingID').value = bookingData.bookingID;
            document.getElementById('workerID').value = bookingData.workerID;
            document.getElementById('workerName').textContent = bookingData.firstName + ' ' + bookingData.lastName;
            document.getElementById('serviceType').textContent = bookingData.serviceType;
            
            // Set worker profile image with correct ROOT path
            const workerAvatar = document.getElementById('workerAvatar');
            const workerInitials = document.getElementById('workerInitials');
            
            // Set worker avatar initials as fallback right away
            const initials = bookingData.firstName.charAt(0) + bookingData.lastName.charAt(0);
            workerInitials.textContent = initials;
            
            // First try worker-specific image as JPG
            workerAvatar.src = `${ROOT}/public/assets/images/workers/${bookingData.workerID}.jpg`;
            console.log(`Trying to load worker JPG image from: ${workerAvatar.src}`);
            
            // When image loads successfully, show it and hide initials
            workerAvatar.onload = function() {
                console.log("Worker JPG image loaded successfully");
                this.style.display = 'block';
                workerInitials.style.display = 'none';
            };
            
            // If JPG fails, try PNG format
            workerAvatar.onerror = function() {
                console.log("Worker JPG failed, trying PNG format");
                this.src = `${ROOT}/public/assets/images/workers/${bookingData.workerID}.png`;
                
                // When PNG loads successfully
                this.onload = function() {
                    console.log("Worker PNG image loaded successfully");
                    this.style.display = 'block';
                    workerInitials.style.display = 'none';
                };
                
                // If PNG also fails, try default avatar
                this.onerror = function() {
                    console.log("Worker PNG failed, trying default avatar");
                    // Try default avatar as JPG first
                    this.src = `${ROOT}/public/assets/images/avatar-image.jpg`;
                    
                    // When default JPG avatar loads successfully
                    this.onload = function() {
                        console.log("Default JPG avatar loaded successfully");
                        this.style.display = 'block';
                        workerInitials.style.display = 'none';
                    };
                    
                    // If default JPG fails, try default PNG
                    this.onerror = function() {
                        console.log("Default JPG failed, trying default PNG");
                        this.src = `${ROOT}/public/assets/images/avatar-image.png`;
                        
                        // When default PNG avatar loads successfully
                        this.onload = function() {
                            console.log("Default PNG avatar loaded successfully");
                            this.style.display = 'block';
                            workerInitials.style.display = 'none';
                        };
                        
                        // If all image attempts fail, show initials
                        this.onerror = function() {
                            console.log("All image attempts failed, showing initials");
                            this.style.display = 'none';
                            workerInitials.style.display = 'flex';
                        };
                    };
                };
            };
            
            // Reset form
            ratingInput.value = '0';
            document.getElementById('reviewComment').value = '';
            highlightStars(0);
            
            // Show modal with direct styling to ensure visibility
            modal.style.cssText = `
                display: flex !important;
                position: fixed !important;
                top: 0 !important;
                left: 0 !important;
                width: 100% !important;
                height: 100% !important;
                background-color: rgba(0, 0, 0, 0.5) !important;
                z-index: 99999 !important;
                justify-content: center !important;
                align-items: center !important;
            `;
            
            console.log("Modal should now be visible");
            
            // Mark the review as shown in PHP session
            markReviewAsShown(bookingData.bookingID);
        } catch (error) {
            console.error("Error in showModal:", error);
        }
    }
    
    function hideModal() {
        modal.style.display = 'none';
    }
    
    function markReviewAsShown(bookingID) {
        const formData = new FormData();
        formData.append('bookingID', bookingID);
        
        fetch(`${ROOT_URL}/public/bookingReview/markAsShown`, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            console.log("Review marked as shown:", data);
        })
        .catch(error => {
            console.error("Error marking review as shown:", error);
        });
    }
    
    function checkForPendingReviews() {
        console.log("Checking for pending reviews...");
        
        // Add a timestamp to prevent caching
        const timestamp = new Date().getTime();
        const apiUrl = `${ROOT_URL}/public/bookingReview/getPendingReviews?t=${timestamp}`;
        
        console.log("Calling API:", apiUrl);
        
        fetch(apiUrl)
            .then(response => {
                console.log("Response status:", response.status);
                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log("Pending reviews data:", data);
                
                if (data.success && data.pendingReviews && data.pendingReviews.length > 0) {
                    console.log(`Found ${data.pendingReviews.length} pending reviews`);
                    
                    // Show the first review
                    const reviewToShow = data.pendingReviews[0];
                    console.log("Review to show:", reviewToShow);
                    
                    // Add delay to ensure no conflict with other UI operations
                    setTimeout(() => {
                        showModal(reviewToShow);
                    }, 500);
                } else {
                    console.log("No pending reviews found or all have been shown");
                }
            })
            .catch(error => {
                console.error('Error checking for pending reviews:', error);
            });
    }
    
    function submitReview(formData) {
        fetch(`${ROOT_URL}/public/bookingReview/submitReview`, {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                hideModal();
                // Show success message
                alert('Thank you for your review!');
                
                // Check if there are more reviews to show after a short delay
                setTimeout(() => {
                    checkForPendingReviews();
                }, 1000);
            } else {
                alert(data.error || 'There was an error submitting your review. Please try again.');
            }
        })
        .catch(error => {
            console.error('Error submitting review:', error);
            alert('There was an error submitting your review. Please try again.');
        });
    }
    
    // Test function to manually show modal with sample data (for debugging)
    window.testReviewModal = function() {
        const testData = {
            bookingID: 30,
            workerID: 13,
            firstName: "Test",
            lastName: "Worker",
            serviceType: "Maid"
        };
        showModal(testData);
    };
});
/**
 * Customer Help Desk JavaScript
 * Enhanced UI interactions for the help desk system
 */

document.addEventListener("DOMContentLoaded", function () {
    initializeIssueTypeHandler();
    initializeStatusFilter();
    initializeOverlayHandler();
    setupFormValidation();
    setupAnimations();
});

/**
 * Tracks the selected issue type and its parent category
 */
function initializeIssueTypeHandler() {
    const issueTypeSelect = document.getElementById("issue-type");
    const form = document.querySelector(".issue-form");
    
    // Check if elements exist
    if (!issueTypeSelect || !form) return;

    // Create a hidden input field to hold the optgroup label (category)
    const optgroupLabelInput = document.createElement("input");
    optgroupLabelInput.type = "hidden";
    optgroupLabelInput.name = "issue_type"; // Match the database column name
    form.appendChild(optgroupLabelInput);

    // Listen for changes in the select dropdown
    issueTypeSelect.addEventListener("change", function () {
        const selectedOption = issueTypeSelect.options[issueTypeSelect.selectedIndex];
        const optgroup = selectedOption.parentElement;

        // Set the value of the hidden input to the optgroup label
        if (optgroup.tagName === "OPTGROUP") {
            optgroupLabelInput.value = optgroup.label;
        } else {
            optgroupLabelInput.value = ""; // Clear if no optgroup
        }
    });
}

/**
 * Setup filtering of complaints by status
 */
function initializeStatusFilter() {
    const statusButtons = document.querySelectorAll('.status-filter button');
    const complaintCards = document.querySelectorAll('.complaint-card');
    
    if (!statusButtons.length) return;

    statusButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Remove active class from all buttons
            statusButtons.forEach(btn => btn.classList.remove('active'));
            
            // Add active class to clicked button
            this.classList.add('active');
            
            const filterValue = this.getAttribute('data-filter');
            
            // Show/hide complaint cards based on filter with animation
            complaintCards.forEach(card => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(10px)';
                
                setTimeout(() => {
                    if (filterValue === 'all' || card.getAttribute('data-status') === filterValue) {
                        card.style.display = 'block';
                        setTimeout(() => {
                            card.style.opacity = '1';
                            card.style.transform = 'translateY(0)';
                        }, 50);
                    } else {
                        card.style.display = 'none';
                    }
                }, 300);
            });
        });
    });
    
    // Set 'All' as default active filter
    const allFilter = document.querySelector('.status-filter button[data-filter="all"]');
    if (allFilter) allFilter.classList.add('active');
}

/**
 * Handle the solution display for resolved complaints
 * @param {string} complaintId - The ID of the complaint
 */
function toggleSolution(complaintId) {
    const solutionElement = document.getElementById(`solution-${complaintId}`);
    const buttonElement = document.querySelector(`button[data-complaint-id="${complaintId}"]`);
    
    if (!solutionElement) return;
    
    if (solutionElement.style.display === "none") {
        // Change button text and add loading state
        if (buttonElement) {
            buttonElement.textContent = "Loading...";
            buttonElement.classList.add('loading');
        }
        
        // Fetch solution from server
        fetch(`${ROOT_URL}/public/customerHelpDesk/getSolution/${encodeURIComponent(complaintId)}`)
        .then((response) => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then((data) => {
            if (data && data.solution) {
                solutionElement.innerHTML = `<p>${data.solution}</p>`;
                
                // Animate solution appearance
                solutionElement.style.display = "block";
                solutionElement.style.maxHeight = '0';
                solutionElement.style.opacity = '0';
                
                setTimeout(() => {
                    solutionElement.style.maxHeight = '500px';
                    solutionElement.style.opacity = '1';
                }, 10);
                
                if (buttonElement) {
                    buttonElement.textContent = "Hide Solution";
                    buttonElement.classList.remove('loading');
                }
            } else {
                solutionElement.innerHTML = "<p>No solution details available yet.</p>";
                solutionElement.style.display = "block";
                
                if (buttonElement) {
                    buttonElement.textContent = "Hide Solution";
                    buttonElement.classList.remove('loading');
                }
            }
        })
        .catch(error => {
            console.error('Error fetching solution:', error);
            solutionElement.innerHTML = "<p>Error loading solution. Please try again.</p>";
            solutionElement.style.display = "block";
            
            if (buttonElement) {
                buttonElement.textContent = "View Solution";
                buttonElement.classList.remove('loading');
            }
        });
    } else {
        // Animate solution hiding
        solutionElement.style.maxHeight = '0';
        solutionElement.style.opacity = '0';
        
        setTimeout(() => {
            solutionElement.style.display = "none";
        }, 300);
        
        if (buttonElement) {
            buttonElement.textContent = "View Solution";
        }
    }
}

/**
 * Handle the session message overlay
 */
function initializeOverlayHandler() {
    const overlay = document.getElementById("overlay-message");
    const closeBtn = document.getElementById("overlay-close-btn");
    
    if (!overlay || !closeBtn) return;

    // Show the overlay if a session message exists
    if (overlay.textContent.trim() !== "") {
        overlay.classList.remove("hidden");
    }

    // Close the overlay and clear the session message when the "Okay" button is clicked
    closeBtn.addEventListener("click", function () {
        overlay.classList.add("hidden");

        // Clear the session message on the server side
        fetch(`${ROOT_URL}/public/customerHelpDesk/clearSessionMessage`)
            .then(response => {
                if (!response.ok) {
                    console.error("Failed to clear session message.");
                }
            })
            .catch(error => console.error("Error clearing session message:", error));
    });
}

/**
 * Setup form validation with dynamic feedback
 */
function setupFormValidation() {
    const form = document.querySelector(".issue-form");
    
    if (!form) return;
    
    const issueType = document.getElementById("issue-type");
    const description = document.getElementById("issue-description");
    
    // Real-time validation feedback
    if (issueType) {
        issueType.addEventListener('change', function() {
            if (this.value !== "") {
                this.classList.remove("error");
                const errorLabel = this.parentElement.querySelector('.error-label');
                if (errorLabel) errorLabel.remove();
            }
        });
    }
    
    if (description) {
        description.addEventListener('input', function() {
            if (this.value.trim().length >= 10) {
                this.classList.remove("error");
                const errorLabel = this.parentElement.querySelector('.error-label');
                if (errorLabel) errorLabel.remove();
            }
        });
    }
    
    // Form submission validation
    form.addEventListener("submit", function(event) {
        let isValid = true;
        
        // Validate issue type
        if (issueType && issueType.value === "") {
            event.preventDefault();
            isValid = false;
            issueType.classList.add("error");
            
            // Add error message if not exists
            if (!issueType.parentElement.querySelector('.error-label')) {
                const errorLabel = document.createElement('div');
                errorLabel.className = 'error-label';
                errorLabel.textContent = 'Please select an issue type';
                issueType.parentElement.appendChild(errorLabel);
            }
        }
        
        // Validate description (minimum 10 characters)
        if (description && description.value.trim().length < 10) {
            event.preventDefault();
            isValid = false;
            description.classList.add("error");
            
            // Add error message if not exists
            if (!description.parentElement.querySelector('.error-label')) {
                const errorLabel = document.createElement('div');
                errorLabel.className = 'error-label';
                errorLabel.textContent = 'Please enter at least 10 characters';
                description.parentElement.appendChild(errorLabel);
            }
        }
        
        if (!isValid) {
            // Show general error message
            const errorMsg = document.getElementById("form-error-msg") || document.createElement("div");
            errorMsg.id = "form-error-msg";
            errorMsg.className = "error-message";
            errorMsg.textContent = "Please fix the errors in the form before submitting.";
            
            if (!document.getElementById("form-error-msg")) {
                form.insertBefore(errorMsg, form.firstChild);
                
                // Scroll to error
                errorMsg.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        }
    });
}

/**
 * Set up animations for page elements
 */
function setupAnimations() {
    // Add status badges to complaint cards
    const complaintCards = document.querySelectorAll('.complaint-card');
    
    complaintCards.forEach(card => {
        const status = card.getAttribute('data-status');
        
        if (status) {
            const statusBadge = document.createElement('div');
            statusBadge.className = `status-badge ${status}`;
            statusBadge.textContent = status;
            card.appendChild(statusBadge);
        }
        
        // Add subtle hover effect
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(-3px)';
        });
    });
    
    // Add loading animation for solution button
    const solutionButtons = document.querySelectorAll('.view-solution-btn');
    
    solutionButtons.forEach(button => {
        button.addEventListener('click', function() {
            if (!this.classList.contains('loading')) {
                this.classList.add('clicked');
                setTimeout(() => {
                    this.classList.remove('clicked');
                }, 300);
            }
        });
    });
}
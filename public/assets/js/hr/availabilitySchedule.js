document.addEventListener('DOMContentLoaded', function() {
    // DOM element references
    const viewButtons = document.querySelectorAll('.view-btn');
    const prevBtn = document.querySelector('.nav-btn.prev');
    const nextBtn = document.querySelector('.nav-btn.next');
    const mainScheduleGrid = document.getElementById('main-schedule-grid');
    const notification = document.getElementById('notification');
    const scheduleContainer = document.getElementById('schedule-container');
    const backButton = document.getElementById('back-button');


    // Global variables
    let currentView = 'week'; // Default view
    let offset = 0; // Offset for navigation (days, weeks, or months)
    let currentUserID = null; // Store user ID

    // Get the userID from URL parameter or from the passed data
    const urlParams = new URLSearchParams(window.location.search);
    currentUserID = urlParams.get('userID') || (typeof userID !== 'undefined' ? userID : null);
    console.log("User ID = ", currentUserID);

    if (!currentUserID) {
        showNotification('No user ID provided', 'error');
        return;
    }

    // Initialize with default view
    loadWorkerScheduleView('week', 0, currentUserID);

    /**
     * Show notification to user
     * @param {string} message - The message to display
     * @param {string} type - The type of notification (success, error, warning)
     */
    function showNotification(message, type) {
        notification.textContent = message;
        notification.className = `notification ${type} show`;
        
        setTimeout(() => {
            notification.className = 'notification hidden';
        }, 3000);
    }
    
    /**
     * Load worker schedule view via AJAX
     * @param {string} view - The view type (day, week, month)
     * @param {number} offset - The offset for navigation
     * @param {number} userID - The user ID to fetch schedule for
     */
    function loadWorkerScheduleView(view, offset, userID) {
        // Show loading state
        scheduleContainer.innerHTML = '<div class="loading">Loading...</div>';
        
        const formData = new FormData();
        formData.append('view', view);
        formData.append('offset', offset);
        formData.append('userID', userID);
        
        fetch(`${ROOT_URL}/HrManager/getScheduleView`, {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.text();
        })
        .then(html => {
            scheduleContainer.innerHTML = html;
        })
        .catch(error => {
            console.error('Error loading schedule view:', error);
            scheduleContainer.innerHTML = `
                <div class="error-message">
                    <p>Error loading schedule view.</p>
                    <p>Please try again or contact support if the problem persists.</p>
                </div>
            `;
            showNotification('Failed to load schedule view', 'error');
        });
    }
    
    // View button event listeners
    viewButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Remove active class from all buttons
            viewButtons.forEach(btn => btn.classList.remove('active'));
            
            // Add active class to clicked button
            this.classList.add('active');
            
            // Update current view
            currentView = this.getAttribute('data-view');
            
            // Reset offset when changing views
            offset = 0;
            
            // Fetch new view
            loadWorkerScheduleView(currentView, offset, currentUserID);
        });
    });
    
    // Previous and Next button event listeners
    prevBtn.addEventListener('click', function() {
        offset--;
        loadWorkerScheduleView(currentView, offset, currentUserID);
    });
    
    nextBtn.addEventListener('click', function() {
        offset++;
        loadWorkerScheduleView(currentView, offset, currentUserID);
    });

    // Add event listener to back button
    if (backButton) {
        backButton.addEventListener('click', function(e) {
            backToTheWorkerInfo();
        });
    } else {
        console.error("Back button element not found");
    }

    function backToTheWorkerInfo() {
        console.log("Navigating back with userID:", currentUserID);
        
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `${ROOT_URL}/HrManager/workerDetails`;
        
        // Add workerData input (userID)
        const workerInput = document.createElement('input');
        workerInput.type = 'hidden';
        workerInput.name = 'workerData';
        workerInput.value = currentUserID;
        
        form.appendChild(workerInput);
        document.body.appendChild(form);
        form.submit();
    }
});
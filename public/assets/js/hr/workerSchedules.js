/**
 * Worker Scheduling JavaScript
 * Handles all the interactive functionality for the HR worker scheduling dashboard
 */

document.addEventListener('DOMContentLoaded', function() {
    // DOM element references
    const viewScheduleBtn = document.getElementById('view-schedule-btn');
    const modal = document.getElementById('schedule-modal');
    const closeModalBtn = document.getElementById('close-modal-btn');
    const workerIdInput = document.getElementById('worker-id-input');
    const modalScheduleGrid = document.getElementById('modal-schedule-grid');
    const viewButtons = document.querySelectorAll('.view-btn');
    const prevBtn = document.querySelector('.nav-btn.prev');
    const nextBtn = document.querySelector('.nav-btn.next');
    const mainScheduleGrid = document.getElementById('main-schedule-grid');
    const matchCustomerBtn = document.getElementById('match-customer-btn');
    const customerIdInput = document.getElementById('customer-id');
    const notification = document.getElementById('notification');
    
    // Global variables
    let currentView = 'week'; // Default view
    let offset = 0; // Offset for navigation (days, weeks, or months)
    let currentWorkerId = null; // Store worker ID from modal
    
    // Debug check if elements exist
    console.log("Match button exists:", !!matchCustomerBtn);
    console.log("Customer ID input exists:", !!customerIdInput);
    console.log("Notification div exists:", !!notification);
    console.log("Schedules loaded:", schedules);

    // Initialize with default view
    loadScheduleView('week', 0);
    
    /**
     * Show notification to user
     * @param {string} message - The message to display
     * @param {string} type - The type of notification (success, error, warning)
     */
    const showNotification = (message, type) => {
        notification.textContent = message;
        notification.className = `notification ${type} show`;
        
        setTimeout(() => {
            notification.className = 'notification hidden';
        }, 3000);
    };
    
    /**
     * Load schedule view via AJAX
     * @param {string} view - The view type (day, week, month)
     * @param {number} offset - The offset for navigation
     */
    function loadScheduleView(view, offset) {
        const formData = new FormData();
        formData.append('view', view);
        formData.append('offset', offset);
        
        fetch(`${ROOT_URL}/HrManager/getScheduleView`, {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(html => {
            document.getElementById('schedule-container').innerHTML = html;
        })
        .catch(error => {
            console.error('Error loading schedule view:', error);
            showNotification('Failed to load schedule view', 'error');
        });
    }

    /**
     * Fetch schedule view via AJAX with loading state
     * @param {string} view - The view type (day, week, month)
     * @param {number} offset - The offset for navigation
     */
    function fetchScheduleView(view, offset) {
        // Show loading state
        mainScheduleGrid.innerHTML = '<div class="loading">Loading...</div>';
        
        // Create a form data object
        const formData = new FormData();
        formData.append('view', view);
        formData.append('offset', offset);
        
        // Make AJAX request
        fetch(`${ROOT_URL}/HrManager/getScheduleView`, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.text();
        })
        .then(html => {
            mainScheduleGrid.innerHTML = html;
        })
        .catch(error => {
            console.error('Error fetching schedule view:', error);
            mainScheduleGrid.innerHTML = `
                <div class="error-message">
                    <p>Error loading schedule view.</p>
                    <p>Please try again or contact support if the problem persists.</p>
                </div>
            `;
            showNotification('Failed to fetch schedule view', 'error');
        });
    }
    
    /**
     * Update current worker ID
     * @param {string} workerId - The worker ID
     */
    const updateCurrentWorkerId = function(workerId) {
        console.log("Setting current worker ID to:", workerId);
        currentWorkerId = workerId;
    };
    
    // ===== EVENT LISTENERS =====
    
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
            fetchScheduleView(currentView, offset);
        });
    });
    
    // Previous and Next button event listeners
    prevBtn.addEventListener('click', function() {
        offset--;
        fetchScheduleView(currentView, offset);
    });
    
    nextBtn.addEventListener('click', function() {
        offset++;
        fetchScheduleView(currentView, offset);
    });

    // Show modal on view schedule button click
    viewScheduleBtn.addEventListener('click', function() {
        const workerId = workerIdInput.value;
        
        if (workerId) {
            // Update the current worker ID
            updateCurrentWorkerId(workerId);
            
            // Filter schedules for the specific worker
            const workerSchedules = schedules.filter(schedule => schedule.workerID == workerId);
            
            if (workerSchedules.length > 0) {
                // Get worker name and role from the first schedule
                const workerName = workerSchedules[0].fullName;
                const workerRole = workerSchedules[0].role;
                
                // Create table for the worker's schedule
                let tableHTML = `
                    <table>
                        <tr>
                            <th>Monday</th>
                            <th>Tuesday</th>
                            <th>Wednesday</th>
                            <th>Thursday</th>
                            <th>Friday</th>
                            <th>Saturday</th>
                            <th>Sunday</th>
                        </tr>
                        <tr>
                `;
                
                // Organize schedules by day
                const days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
                
                days.forEach(day => {
                    const daySchedule = workerSchedules.find(schedule => schedule.day_of_week === day);
                    
                    tableHTML += '<td>';
                    if (daySchedule) {
                        // Keep 24-hour format as requested
                        tableHTML += `
                            <div class="schedule-item">
                                <div class="worker-name">${workerName}</div>
                                <div class="worker-role">${workerRole}</div>
                                <div class="work-time">${daySchedule.start_time} - ${daySchedule.end_time}</div>
                            </div>
                        `;
                    }
                    tableHTML += '</td>';
                });
                
                tableHTML += '</tr></table>';
                
                // Update modal with worker's schedule
                modalScheduleGrid.innerHTML = tableHTML;
                
                modal.style.display = 'flex';
            } else {
                showNotification('No schedules found for this worker ID', 'warning');
            }
        } else {
            showNotification('Please enter a Worker ID', 'error');
        }
    });

    // Hide modal on close button click
    closeModalBtn.addEventListener('click', function() {
        modal.style.display = 'none';
    });

    // Hide modal when clicking outside the modal content
    window.addEventListener('click', function(event) {
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    });

    // Match customer button event listener
    if (matchCustomerBtn) {
        matchCustomerBtn.addEventListener('click', function(event) {
            console.log("Match button clicked");
            event.preventDefault(); // Prevent form submission if in a form
            
            const customerId = customerIdInput.value;
            console.log("Customer ID:", customerId);
            console.log("Current Worker ID:", currentWorkerId);
            
            // Validate inputs
            if (!customerId) {
                showNotification('Please enter a Customer ID', 'error');
                return;
            }
            
            if (!currentWorkerId) {
                // If we don't have the worker ID stored, try to get it from the input
                const workerId = document.getElementById('worker-id-input').value;
                if (workerId) {
                    currentWorkerId = workerId;
                } else {
                    showNotification('Worker ID not found', 'error');
                    return;
                }
            }
            
            console.log("Sending match request for worker", currentWorkerId, "and customer", customerId);
            
            // Create form data for the request
            const formData = new FormData();
            formData.append('workerID', currentWorkerId);
            formData.append('customerID', customerId);
            
            // Send the request to the workerMatching endpoint
            fetch(`${ROOT_URL}/HrManager/workerMatching`, {
                method: 'POST',
                body: formData
            })
            .then(response => {
                console.log("Response status:", response.status);
                if (!response.ok) {
                    return response.text().then(text => {
                        console.error("Error response:", text);
                        throw new Error(text || 'Server returned error: ' + response.status);
                    });
                }
                return response.text();
            })
            .then(result => {
                console.log("Success response:", result);
                // Try to parse as JSON if possible
                try {
                    const data = JSON.parse(result);
                    showNotification(data.message || `Worker ${currentWorkerId} successfully matched with customer ${customerId}`, 
                        data.success ? 'success' : 'error');
                } catch (e) {
                    // If not JSON, just show success message
                    showNotification(`Worker ${currentWorkerId} successfully matched with customer ${customerId}`, 'success');
                }
                
                // Clear the customer ID field
                customerIdInput.value = '';
                
                // Refresh the schedule view to show updated data
                fetchScheduleView(currentView, offset);
                
                // Close the modal after successful matching
                modal.style.display = 'none';
            })
            .catch(error => {
                console.error('Error matching worker with customer:', error);
                showNotification(error.message || 'Error during matching process', 'error');
            });
        });
        
        console.log("Match button event listener attached");
    } else {
        console.error("Could not find match-customer-btn element!");
    }
});
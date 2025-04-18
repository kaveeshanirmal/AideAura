<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HR Dashboard - Worker Scheduling</title>
    <link rel="stylesheet" href="<?=ROOT?>/public/assets/css/hrWorkerSchedules.css">
</head>
<script>
    const schedules = <?= json_encode($schedules) ?>;
    console.log(schedules);
</script>
<body>
    <!-- Add notification element -->
<div id="notification" class="notification hidden"></div>

    <div class="container">
        <!-- Navbar Component -->
        <?php include(ROOT_PATH . '/app/views/components/employeeNavbar.view.php'); ?>
        <div class="main-content">
            <div class="dashboard-container">
                <div class="scheduling-controls">
                    <div class="field-group">
                        <label>Worker ID : </label>
                        <input type="number" id="worker-id-input" class="field-input">
                    </div>

                    <button class="schedule-btn" id="view-schedule-btn">see worker schedule</button>
                    
                    <div class="view-controls">
                        <button class="view-btn" data-view="day">Days</button>
                        <button class="view-btn active" data-view="week">Weeks</button>
                        <button class="view-btn" data-view="month">Months</button>
                    </div>
                </div>

                <div class="schedule-container">
                    <button class="nav-btn prev">&lt;</button>
                    
                    <div class="schedule-grid" id="main-schedule-grid">
                    <?php
// Default view is week
$currentView = 'week';

// Output a loading div instead of calling the undefined function
?>
<div id="schedule-container">
    <?php echo $initialView; ?>
</div>
                    </div>
                    
                    <button class="nav-btn next">&gt;</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="schedule-modal" style="display: none;">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Worker Schedule</h3>
            <button class="close-btn" id="close-modal-btn">&times;</button>
        </div>
        <div class="modal-body">
            <div class="schedule-grid" id="modal-schedule-grid">
                <!-- Worker specific schedule will be dynamically populated here -->
            </div>
        </div>
        <div class="worker-details-container" id="worker-details">
                    <div class="customer-match-section">
                        <div class="field-group1">
                            <label for="customer-id">Customer ID:</label>
                            <input type="number" id="customer-id" class="field-input">
                        </div>
                        <button class="match-btn" id="match-customer-btn">Match</button>
                    </div>
                    <!-- <div class="worker-info-section" id="worker-info">
                        // Worker details will be dynamically populated here 
                    </div> -->
         </div>
    </div>
    </div>
    
    <script>
document.addEventListener('DOMContentLoaded', function() {
    const viewScheduleBtn = document.getElementById('view-schedule-btn');
    const modal = document.getElementById('schedule-modal');
    const closeModalBtn = document.getElementById('close-modal-btn');
    const workerIdInput = document.getElementById('worker-id-input');
    const modalScheduleGrid = document.getElementById('modal-schedule-grid');
    const viewButtons = document.querySelectorAll('.view-btn');
    const prevBtn = document.querySelector('.nav-btn.prev');
    const nextBtn = document.querySelector('.nav-btn.next');
    const mainScheduleGrid = document.getElementById('main-schedule-grid');
    loadScheduleView('week', 0); // Load initial schedule view
 // Get references to all needed elements
   const matchCustomerBtn = document.getElementById('match-customer-btn');
    const customerIdInput = document.getElementById('customer-id');
    const notification = document.getElementById('notification');
    
     // Debug check if elements exist
     console.log("Match button exists:", !!matchCustomerBtn);
    console.log("Customer ID input exists:", !!customerIdInput);
    console.log("Notification div exists:", !!notification);

       // Notification function
       const showNotification = (message, type) => {
        notification.textContent = message;
        notification.className = `notification ${type} show`;
        
        setTimeout(() => {
            notification.className = 'notification hidden';
        }, 3000);
    };
    
    let currentView = 'week'; // Default view
    let offset = 0; // Offset for navigation (days, weeks, or months)
    

    function loadScheduleView(view, offset) {
    const formData = new FormData();
    formData.append('view', view);
    formData.append('offset', offset);
    
    fetch('<?=ROOT?>/public/HrManager/getScheduleView', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(html => {
        document.getElementById('schedule-container').innerHTML = html;
    })
    .catch(error => {
        console.error('Error loading schedule view:', error);
        showNotification('Failed to load schedule view', 'error');    });
}

    // Function to fetch schedule view via AJAX
    function fetchScheduleView(view, offset) {
        // Show loading state
        mainScheduleGrid.innerHTML = '<div class="loading">Loading...</div>';
        
        // Create a form data object
        const formData = new FormData();
        formData.append('view', view);
        formData.append('offset', offset);
        
        // Make AJAX request - fixed URL path
        fetch('<?=ROOT?>/public/HrManager/getScheduleView', {
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
    
    // Add event listeners to view buttons
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

    // Show modal on button click and populate worker schedule
    viewScheduleBtn.addEventListener('click', function() {
        const workerId = workerIdInput.value;
        
        if (workerId) {
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
                
                // // Populate worker info section
                // document.getElementById('worker-info').innerHTML = `
                //     <div class="worker-info-item">
                //         <span class="info-label">Worker ID:</span>
                //         <span class="info-value">${workerId}</span>
                //     </div>
                //     <div class="worker-info-item">
                //         <span class="info-label">Name:</span>
                //         <span class="info-value">${workerName}</span>
                //     </div>
                //     <div class="worker-info-item">
                //         <span class="info-label">Role:</span>
                //         <span class="info-value">${workerRole}</span>
                //     </div>
                // `;
                
                modal.style.display = 'flex';
            } else {
                showNotification('No schedules found for this worker ID', 'warning');            }
        } else {
            showNotification('Please enter a Worker ID', 'error');        }
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
    
  // Store worker ID from modal
  let currentWorkerId = null;
  const updateCurrentWorkerId = function(workerId) {
        console.log("Setting current worker ID to:", workerId);
        currentWorkerId = workerId;
    };

 // Add click event listener to the match button
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
            fetch('<?=ROOT?>/public/HrManager/workerMatching', {
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
                    // Refresh the schedule view
               fetchScheduleView(currentView, offset);
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

// Load initial schedule view when the page loads
// fetchScheduleView(currentView, offset);
    </script>
</body>
</html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HR Dashboard - Worker Scheduling</title>
    <link rel="stylesheet" href="<?=ROOT?>/public/assets/css/hrWorkerSchedules.css">
    <style>
        /* Additional styles to ensure consistent card sizing */
        .schedule-item {
            min-height: 80px;
            width: 100%;
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
        }
        
        /* Ensure table cells have consistent sizing */
        td {
            width: 14.28%; /* Equal width for 7 days */
            box-sizing: border-box;
        }
        
        /* Loading indicator */
        .loading {
            text-align: center;
            padding: 20px;
            font-style: italic;
            color: #777;
        }
    </style>
</head>
<script>
    const schedules = <?= json_encode($schedules) ?>;
    console.log(schedules);
</script>
<body>

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
    });
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
                alert('No schedules found for this worker ID');
            }
        } else {
            alert('Please enter a Worker ID');
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
    
    // Match customer button functionality
    document.getElementById('match-customer-btn').addEventListener('click', function() {
        const customerId = document.getElementById('customer-id').value;
        if (customerId) {
            // Here you would typically make an AJAX request to match the worker with a customer
            alert(`Matching worker with customer ID: ${customerId}`);
        } else {
            alert('Please enter a Customer ID');
        }
    });
});

// Load initial schedule view when the page loads
fetchScheduleView(currentView, offset);
    </script>
</body>
</html>
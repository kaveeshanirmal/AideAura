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
                        
                        // Function to generate schedule display
                        function generateScheduleView($schedules, $view = 'week') {
                            $output = '';
                            
                            // Get current date
                            $currentDate = new DateTime();
                            
                            if ($view == 'day') {
                                // Day view - show single day with hourly slots
                                $dayOfWeek = $currentDate->format('l');
                                $formattedDate = $currentDate->format('l, d M Y');
                                
                                $output .= '<table>';
                                $output .= '<tr><th>' . $formattedDate . '</th></tr>';
                                
                                // Filter schedules for current day
                                $daySchedules = array_filter($schedules, function($schedule) use ($dayOfWeek) {
                                    return $schedule->day_of_week == $dayOfWeek;
                                });
                                
                                // Sort schedules by start time
                                usort($daySchedules, function($a, $b) {
                                    return strtotime($a->start_time) - strtotime($b->start_time);
                                });
                                
                                foreach ($daySchedules as $schedule) {
                                    $output .= '<tr><td>';
                                    $output .= '<div class="schedule-item">';
                                    $output .= '<div class="worker-name">' . htmlspecialchars($schedule->fullName) . '</div>';
                                    $output .= '<div class="worker-role">' . htmlspecialchars($schedule->role) . '</div>';
                                    $output .= '<div class="work-time">' . $schedule->start_time . ' - ' . $schedule->end_time . '</div>';
                                    $output .= '</div>';
                                    $output .= '</td></tr>';
                                }
                                
                                // Ensure minimum number of rows for consistent UI
                                $rowCount = count($daySchedules);
                                for ($i = $rowCount; $i < 7; $i++) {
                                    $output .= '<tr><td></td></tr>';
                                }
                                
                                $output .= '</table>';
                                
                            } elseif ($view == 'week') {
                                // Week view - show current week with days
                                $weekStart = clone $currentDate;
                                $weekStart->modify('monday this week');
                                
                                // Array to store days of the week with dates
                                $days = [];
                                for ($i = 0; $i < 7; $i++) {
                                    $currentDay = clone $weekStart;
                                    $currentDay->modify("+$i days");
                                    $days[] = [
                                        'name' => $currentDay->format('l'),
                                        'date' => $currentDay->format('d M Y')
                                    ];
                                }
                                
                                $output .= '<table>';
                                $output .= '<tr>';
                                foreach ($days as $day) {
                                    $output .= '<th>' . $day['name'] . ', ' . $day['date'] . '</th>';
                                }
                                $output .= '</tr>';
                                
                                // Create an associative array to organize schedules by day
                                $schedulesByDay = [
                                    'Monday' => [],
                                    'Tuesday' => [],
                                    'Wednesday' => [],
                                    'Thursday' => [],
                                    'Friday' => [],
                                    'Saturday' => [],
                                    'Sunday' => []
                                ];
                                
                                // Organize schedules by day of the week
                                foreach ($schedules as $schedule) {
                                    $dayOfWeek = $schedule->day_of_week;
                                    if (isset($schedulesByDay[$dayOfWeek])) {
                                        $schedulesByDay[$dayOfWeek][] = $schedule;
                                    }
                                }
                                
                                // Sort schedules by start time within each day
                                foreach ($schedulesByDay as &$daySchedules) {
                                    usort($daySchedules, function($a, $b) {
                                        return strtotime($a->start_time) - strtotime($b->start_time);
                                    });
                                }
                                
                                // Get the maximum number of schedules in any day
                                $maxSchedules = 0;
                                foreach ($schedulesByDay as $daySchedules) {
                                    $count = count($daySchedules);
                                    if ($count > $maxSchedules) {
                                        $maxSchedules = $count;
                                    }
                                }
                                
                                // Ensure at least 7 rows (as in the original design)
                                $maxSchedules = max($maxSchedules, 7);
                                
                                // Output schedule cells
                                for ($i = 0; $i < $maxSchedules; $i++) {
                                    $output .= '<tr>';
                                    foreach ($schedulesByDay as $daySchedules) {
                                        $output .= '<td>';
                                        if (isset($daySchedules[$i])) {
                                            $schedule = $daySchedules[$i];
                                            $output .= '<div class="schedule-item">';
                                            $output .= '<div class="worker-name">' . htmlspecialchars($schedule->fullName) . '</div>';
                                            $output .= '<div class="worker-role">' . htmlspecialchars($schedule->role) . '</div>';
                                            $output .= '<div class="work-time">' . $schedule->start_time . ' - ' . $schedule->end_time . '</div>';
                                            $output .= '</div>';
                                        }
                                        $output .= '</td>';
                                    }
                                    $output .= '</tr>';
                                }
                                
                                $output .= '</table>';
                                
                            } else {
                                // Month view - show calendar view
                                $currentMonth = $currentDate->format('F Y');
                                $firstDay = new DateTime('first day of ' . $currentMonth);
                                $lastDay = new DateTime('last day of ' . $currentMonth);
                                
                                $firstDayOfWeek = $firstDay->format('N') - 1; // 0 = Monday, 6 = Sunday
                                $daysInMonth = $lastDay->format('d');
                                
                                // Calculate total cells needed (previous month days + current month days)
                                $totalCells = $firstDayOfWeek + $daysInMonth;
                                $totalRows = ceil($totalCells / 7);
                                
                                $output .= '<table>';
                                $output .= '<tr><th colspan="7">' . $currentMonth . '</th></tr>';
                                $output .= '<tr><th>Mon</th><th>Tue</th><th>Wed</th><th>Thu</th><th>Fri</th><th>Sat</th><th>Sun</th></tr>';
                                
                                // Create day cells
                                $day = 1;
                                for ($i = 0; $i < $totalRows; $i++) {
                                    $output .= '<tr>';
                                    
                                    for ($j = 0; $j < 7; $j++) {
                                        $output .= '<td>';
                                        
                                        if (($i == 0 && $j < $firstDayOfWeek) || ($day > $daysInMonth)) {
                                            // Empty cell (previous or next month)
                                        } else {
                                            // Current month day
                                            $output .= '<div class="day-number">' . $day . '</div>';
                                            
                                            // Get day of week name for this date
                                            $dayDate = clone $firstDay;
                                            $dayDate->modify('+' . ($day - 1) . ' days');
                                            $dayOfWeek = $dayDate->format('l');
                                            
                                            // Filter schedules for this day
                                            $daySchedules = array_filter($schedules, function($schedule) use ($dayOfWeek) {
                                                return $schedule->day_of_week == $dayOfWeek;
                                            });
                                            
                                            // Show first schedule for this day (if any)
                                            if (!empty($daySchedules)) {
                                                $schedule = reset($daySchedules);
                                                $output .= '<div class="schedule-item">';
                                                $output .= '<div class="worker-name">' . htmlspecialchars($schedule->fullName) . '</div>';
                                                $output .= '<div class="worker-role">' . htmlspecialchars($schedule->role) . '</div>';
                                                $output .= '<div class="work-time">' . $schedule->start_time . ' - ' . $schedule->end_time . '</div>';
                                                $output .= '</div>';
                                                
                                                // If there are more schedules, add indicator
                                                if (count($daySchedules) > 1) {
                                                    $output .= '<div class="more-schedules">+' . (count($daySchedules) - 1) . ' more</div>';
                                                }
                                            }
                                            
                                            $day++;
                                        }
                                        
                                        $output .= '</td>';
                                    }
                                    
                                    $output .= '</tr>';
                                }
                                
                                $output .= '</table>';
                            }
                            
                            return $output;
                        }
                        
                        // Generate initial week view
                        echo generateScheduleView($schedules, 'week');
                        ?>
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
                    <div class="worker-info-section" id="worker-info">
                        <!-- Worker details will be dynamically populated here -->
                    </div>
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
    
    let currentView = 'week'; // Default view
    let currentDate = new Date();
    let offset = 0; // Offset for navigation (days, weeks, or months)
    
    // Function to generate new schedule view via AJAX
    function fetchScheduleView(view, offset) {
        // In a real application, this would be an AJAX call to the server
        // For this example, we'll simulate it with different views
        
        // Create a form data object
        const formData = new FormData();
        formData.append('view', view);
        formData.append('offset', offset);
        
        // Simulate AJAX request
        fetch('<?=ROOT?>/hr/getScheduleView', {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(html => {
            mainScheduleGrid.innerHTML = html;
        })
        .catch(error => {
            console.error('Error fetching schedule view:', error);
            // For now, we'll just show a message in the grid
            let message = '';
            
            if (view === 'day') {
                const date = new Date(currentDate);
                date.setDate(date.getDate() + offset);
                message = `<div class="view-message">Day view for ${date.toDateString()}</div>`;
            } else if (view === 'week') {
                const date = new Date(currentDate);
                date.setDate(date.getDate() + (offset * 7));
                message = `<div class="view-message">Week view starting ${date.toDateString()}</div>`;
            } else if (view === 'month') {
                const date = new Date(currentDate);
                date.setMonth(date.getMonth() + offset);
                message = `<div class="view-message">Month view for ${date.toLocaleString('default', { month: 'long', year: 'numeric' })}</div>`;
            }
            
            // In a real implementation, you would implement the view loading here
            // For now, we'll just show the current view with a message
            mainScheduleGrid.innerHTML = `
                <table>
                    <tr>
                        <th colspan="7">${message}</th>
                    </tr>
                    <tr>
                        <td colspan="7">The actual ${view} view would be loaded here</td>
                    </tr>
                </table>
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
            console.log(`View changed to: ${currentView}`);
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
                
                // Populate worker info section
                document.getElementById('worker-info').innerHTML = `
                    <div class="worker-info-item">
                        <span class="info-label">Worker ID:</span>
                        <span class="info-value">${workerId}</span>
                    </div>
                    <div class="worker-info-item">
                        <span class="info-label">Name:</span>
                        <span class="info-value">${workerName}</span>
                    </div>
                    <div class="worker-info-item">
                        <span class="info-label">Role:</span>
                        <span class="info-value">${workerRole}</span>
                    </div>
                `;
                
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
    </script>
</body>
</html>
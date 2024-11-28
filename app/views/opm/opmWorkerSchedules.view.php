<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OPM Worker Schedule</title>
    <link rel="stylesheet" href="<?=ROOT?>/public/assets/css/opmWorkerSchedules.css">
</head>
<body>
    <div class="container">
        <!-- Navbar Component -->
        <?php include(ROOT_PATH . '/app/views/components/OPM_navbar.view.php');  ?>
        <div class="main-content">
            <div class="dashboard-container">
                <div class="scheduling-controls">
                <div class="field-group">
                    <label for="worker-field">Select the Worker Field:</label>        
                    <select id="worker-field" class="field-input field-select">
                        <option value="cleaner" selected>Cleaner</option>
                        <option value="cook">Cook</option>
                        <option value="nanny">Nanny</option>
                        <option value="allRounder">All Rounder</option>
                    </select>
                </div>

                    <div class="field-group">
                        <label>Worker ID : </label>
                        <input type="number" value="" class="field-input">
                    </div>

                    <button class="schedule-btn" id="view-schedule-btn">View Schedule</button>
                    
                    <div class="view-controls">
                        <button class="view-btn">Days</button>
                        <button class="view-btn active">Weeks</button>
                        <button class="view-btn">Months</button>
                    </div>
                </div>

                <div class="schedule-container">
                    <button class="nav-btn prev">&lt;</button>
                    
                    <div class="schedule-grid">
                        <?php
                        // Array of days for the week
                        $days = array_fill(0, 7, "Monday, 20 2024");
                        
                        // Output table header
                        echo '<table>';
                        echo '<tr>';
                        foreach ($days as $day) {
                            echo "<th>$day</th>";
                        }
                        echo '</tr>';
                        
                        // Create time slots (7 slots shown in the image)
                        $timeSlots = [
                            ['8:00 - 5:00'],
                            ['9:00 - 6:00'],
                            ['9:30 - 5:30'],
                            ['9:35 - 5:45'],
                            ['10:00 - 5:00'],
                            ['10:15 - 5:00'],
                            ['12:00 - 3:00']
                        ];
                        
                        // Output schedule cells
                        foreach ($timeSlots as $slot) {
                            echo '<tr>';
                            for ($i = 0; $i < 7; $i++) {
                                echo '<td>';
                                echo '<div class="schedule-item">';
                                echo '<div class="worker-name">Mr.kamal Ranathunga</div>';
                                echo '<div class="worker-role">Cleaner</div>';
                                echo '<div class="work-time">' . $slot[0] . '</div>';
                                echo '</div>';
                                echo '</td>';
                            }
                            echo '</tr>';
                        }
                        echo '</table>';
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
                        <td>
                            <div class="schedule-item">
                                <div class="worker-name">John Doe</div>
                                <div class="worker-role">Cleaner</div>
                                <div class="work-time">8:00 - 5:00</div>
                            </div>
                        </td>
                        <td>
                            <div class="schedule-item">
                                <div class="worker-name">Jane Smith</div>
                                <div class="worker-role">Cook</div>
                                <div class="work-time">9:00 - 6:00</div>
                            </div>
                        </td>
                        <td>
                            <div class="schedule-item">
                                <div class="worker-name">Mike Johnson</div>
                                <div class="worker-role">Nanny</div>
                                <div class="work-time">9:30 - 5:30</div>
                            </div>
                        </td>
                        <td>
                            <div class="schedule-item">
                                <div class="worker-name">Sarah Williams</div>
                                <div class="worker-role">All Rounder</div>
                                <div class="work-time">10:00 - 5:00</div>
                            </div>
                        </td>
                        <td>
                            <div class="schedule-item">
                                <div class="worker-name">Tom Brown</div>
                                <div class="worker-role">Cleaner</div>
                                <div class="work-time">12:00 - 3:00</div>
                            </div>
                        </td>
                        <td>
                            <div class="schedule-item">
                                <div class="worker-name">Emily Davis</div>
                                <div class="worker-role">Cook</div>
                                <div class="work-time">8:30 - 4:30</div>
                            </div>
                        </td>
                        <td>
                            <div class="schedule-item">
                                <div class="worker-name">David Wilson</div>
                                <div class="worker-role">Nanny</div>
                                <div class="work-time">10:15 - 5:00</div>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="worker-details-container" id="worker-details">
                    <div class="customer-match-section">
                        <div class="field-group">
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

    // Show modal on button click
    viewScheduleBtn.addEventListener('click', function() {
        modal.style.display = 'flex';
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
});

    </script>
</body>
</html>
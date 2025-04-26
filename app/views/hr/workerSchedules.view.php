<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HR Dashboard - Worker Scheduling</title>
    <link rel="stylesheet" href="<?=ROOT?>/public/assets/css/hrWorkerSchedules.css">
    <!-- Include the separate JS file -->
    <script src="<?=ROOT?>/public/assets/js/hr/workerSchedules.js" defer></script>
</head>
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
            <!-- <div class="worker-details-container" id="worker-details">
                <div class="customer-match-section">
                    <div class="field-group1">
                        <label for="customer-id">Customer ID:</label>
                        <input type="number" id="customer-id" class="field-input">
                    </div>
                    <button class="match-btn" id="match-customer-btn">Match</button>
                </div> -->
                <!-- <div class="worker-info-section" id="worker-info">
                    // Worker details will be dynamically populated here 
                </div> -->
            <!-- </div> -->
        </div>
    </div>
    
    <!-- Add this script to pass PHP variables to JavaScript -->
    <script>
        // Pass PHP data to JavaScript
        const ROOT_URL = '<?=ROOT?>/public';
        const schedules = <?= json_encode($schedules) ?>;
    </script>
</body>
</html>
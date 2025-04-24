<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Worker Availability Schedule</title>
    <link rel="stylesheet" href="<?=ROOT?>/public/assets/css/hrWorkerProfileSchedule.css">
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
                <button class="back-button" id="back-button">
                        &lt;
                    </button>
                    
                    <div class="view-controls">
                        <button class="view-btn" data-view="day">Day</button>
                        <button class="view-btn active" data-view="week">Week</button>
                        <button class="view-btn" data-view="month">Month</button>
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
    
    <!-- Pass PHP variables to JavaScript -->
    <script>
        const ROOT_URL = '<?=ROOT?>/public';
        const userID = <?= json_encode($userID) ?>;
        const schedules = <?= json_encode($schedule) ?>;
    </script>
    
    <!-- Include the JavaScript file -->
    <script src="<?=ROOT?>/public/assets/js/hr/availabilitySchedule.js" defer></script>
</body>
</html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Worker Scheduling</title>
    <link rel="stylesheet" href="<?=ROOT?>/public/assets/css/hrWorkerProfileSchedule.css">
</head>
<body>
    <div class="container">
        <!-- Navbar Component -->
        <?php include(ROOT_PATH . '/app/views/components/admin_navbar.view.php');  ?>
        <div class="main-content">
            <div class="dashboard-container">
                <div class="scheduling-controls">
                <button class="back-button">
                    <span class="back-icon"><a href="worker1" class="back-button">  < </a>
                    </span>
                </button>
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
</body>
</html>
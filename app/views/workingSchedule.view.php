<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Working Schedule</title>
    <link rel="stylesheet" href="<?=ROOT?>/public/assets/css/workingSchedule.css" />
    <script src="<?=ROOT?>/public/assets/js/workingSchedule.js"></script>
</head>
<body>
    <?php include(ROOT_PATH . '/app/views/components/navbar.view.php'); ?>
    <div class="schedule-container">
        <h1>My Weekly Schedule</h1>
        <button class="edit-btn">Edit Schedule</button>
        <table class="schedule-table">
            <thead>
                <tr>
                    <th>Day</th>
                    <th>Start Time</th>
                    <th>End Time</th>
                    <th class="action-column" style="display: none">Actions</th>
                </tr>
            </thead>
            <tbody>
                <!-- Hardcoded time slots -->
                <tr>
                    <td>Monday</td>
                    <td>09:00</td>
                    <td>12:00</td>
                    <td class="action-column" style="display: none"></td>
                </tr>
                <tr>
                    <td>Tuesday</td>
                    <td>07:00</td>
                    <td>09:00</td>
                    <td class="action-column" style="display: none"></td>
                </tr>
    
                <tr>
                    <td>Tuesday</td>
                    <td>10:00</td>
                    <td>13:00</td>
                    <td class="action-column" style="display: none"></td>
                </tr>
                <tr>
                    <td>Wednesday</td>
                    <td>14:00</td>
                    <td>16:00</td>
                    <td class="action-column" style="display: none"></td>
                </tr>
                <tr>
                    <td>Thursday</td>
                    <td>08:00</td>
                    <td>10:30</td>
                    <td class="action-column" style="display: none"></td>
                </tr>
                <tr>
                    <td>Friday</td>
                    <td>15:00</td>
                    <td>17:00</td>
                    <td class="action-column" style="display: none"></td>
                </tr>
            </tbody>
        </table>
        <div class="error-overlay" style="display: none">
            <div class="error-modal">
                <h2 class="error-name">Error!</h2>
                <ul class="error-list"></ul>
                <button class="close-overlay-btn">Close</button>
            </div>
        </div>
        <!-- Buttons hidden initially -->
        <button class="add-schedule-btn" style="display: none">Add new day</button>
        <button class="save-changes-btn" style="display: none">Save Changes</button>
        <button class="cancel-changes-btn" style="display: none">
            Cancel Changes
        </button>
    </div>
    <?php include(ROOT_PATH . '/app/views/components/footer.view.php'); ?>
</body>

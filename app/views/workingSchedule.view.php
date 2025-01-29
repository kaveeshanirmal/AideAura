<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Working Schedule</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="<?=ROOT?>/public/assets/css/workingSchedule.css" />
</head>
<body>
    <div id="alert-container"></div>
    <?php include(ROOT_PATH . '/app/views/components/navbar.view.php'); ?>
    <div class="schedule-container">
        <h1>My Weekly Schedule</h1>
        
        
        <form id="scheduleForm" method="POST" action="<?=ROOT?>/public/WorkingSchedule/saveSchedule">
            <input type="hidden" name="workerID" value="<?=$_SESSION['workerID']?>">
            
            <table class="schedule-table">
                <thead>
                    <tr>
                        <th>Day</th>
                        <th>Start Time</th>
                        <th>End Time</th>
                        <th class="action-column">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($data['schedules'])): ?>
                        <?php foreach ($data['schedules'] as $schedule): ?>
                            <tr data-schedule-id="<?= $schedule->scheduleID ?>">
                                <td>
                                    <input type="hidden" name="schedules[<?= $schedule->scheduleID ?>][id]" value="<?= $schedule->scheduleID ?>">
                                    <span class="display-text"><?= htmlspecialchars($schedule->days_of_week) ?></span>
                                    <select class="day-select" name="schedules[<?= $schedule->scheduleID ?>][days_of_week]" style="display: none">
                                        <option value="monday" <?= $schedule->days_of_week == 'monday' ? 'selected' : '' ?>>Monday</option>
                                        <option value="tuesday" <?= $schedule->days_of_week == 'tuesday' ? 'selected' : '' ?>>Tuesday</option>
                                        <option value="wednesday" <?= $schedule->days_of_week == 'wednesday' ? 'selected' : '' ?>>Wednesday</option>
                                        <option value="thursday" <?= $schedule->days_of_week == 'thursday' ? 'selected' : '' ?>>Thursday</option>
                                        <option value="friday" <?= $schedule->days_of_week == 'friday' ? 'selected' : '' ?>>Friday</option>
                                        <option value="saturday" <?= $schedule->days_of_week == 'saturday' ? 'selected' : '' ?>>Saturday</option>
                                        <option value="sunday" <?= $schedule->days_of_week == 'sunday' ? 'selected' : '' ?>>Sunday</option>
                                    </select>
                                </td>
                                <td>
                                    <span class="display-text"><?= htmlspecialchars($schedule->startTime) ?></span>
                                    <input type="time" class="time-input" name="schedules[<?= $schedule->scheduleID ?>][startTime]" 
                                           value="<?= htmlspecialchars($schedule->startTime) ?>" style="display: none">
                                </td>
                                <td>
                                    <span class="display-text"><?= htmlspecialchars($schedule->endTime) ?></span>
                                    <input type="time" class="time-input" name="schedules[<?= $schedule->scheduleID ?>][endTime]" 
                                           value="<?= htmlspecialchars($schedule->endTime) ?>" style="display: none">
                                </td>
                                <td class="action-column">
                                    <i class="fas fa-edit update-btn" title="Edit Icon"></i>
                                    <i class="fas fa-trash-alt delete-btn" title="Delete Schedule"></i>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="no-schedules">No schedules found. Click 'Edit Schedule' to add new schedules.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
            <div class="button-container">
                <button class="edit-btn" type="button">Edit Schedule</button>
            </div>
            <!-- Form buttons -->
            <div class="schedule-actions">
                <button type="button" class="schedule-btn add-schedule-btn">Add New Day</button>
                <button type="button" class="schedule-btn save-changes-btn">Save Changes</button>
            </div>
            
        </form>

        <div class="error-overlay" style="display: none">
            <div class="error-modal">
                <h2 class="error-name">Error!</h2>
                <ul class="error-list"></ul>
                <button class="close-overlay-btn">Close</button>
            </div>
        </div>
    </div>
    <?php include(ROOT_PATH . '/app/views/components/footer.view.php'); ?>
    
    <script>
        const ROOT = '<?=ROOT?>';
        const FULL_URL = window.location.href;
        console.log('Debug info:', {
            ROOT: ROOT,
            currentURL: FULL_URL,
            baseURL: window.location.origin,
            pathname: window.location.pathname
        });
    </script>
    <script src="<?=ROOT?>/public/assets/js/workingSchedule.js"></script>
</body>
</html>

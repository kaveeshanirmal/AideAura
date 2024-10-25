<!-- Notification Side Panel -->
<link rel="stylesheet" href="<?=ROOT?>/public/assets/css/notificationPanel.css">
<script src="<?=ROOT?>/public/assets/js/notificationPanel.js"></script>
<div id="notification-panel" class="notification-panel">
    <div class="notification-header">
        <h3>Notifications</h3>
        <button class="notification-close" id="notification-close">×</button>
    </div>
    <div class="notification-content">
        <ul class="notification-list">
            <li>
                <p><strong>Worker Found</strong> - A worker has been assigned to your request.</p>
                <span class="notification-time">5 mins ago</span>
            </li>
            <li>
                <p><strong>Work Started</strong> - The worker has started the task at your location.</p>
                <span class="notification-time">1 hour ago</span>
            </li>
            <li>
                <p><strong>Work Completed</strong> - The worker has completed the job. Please review the work.</p>
                <span class="notification-time">3 hours ago</span>
            </li>
            <li>
                <p><strong>Payment Reminder</strong> - Please complete payment for the recent service.</p>
                <span class="notification-time">Yesterday</span>
            </li>
        </ul>
    </div>
</div>

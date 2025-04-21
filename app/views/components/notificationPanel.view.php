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
            <?php if (!empty($notifications)): ?>
                <?php foreach ($notifications as $notif): ?>
                    <li class="notification-item" data-notification-id="<?= $notif->notificationID ?>">
                        <div class="notification-text">
                            <p><strong><?= esc($notif->title) ?></strong> - <?= esc($notif->message) ?></p>
                            <span class="notification-time"><?= timeAgo($notif->created_at) ?></span>
                        </div>
                        <button class="mark-read-btn" title="Mark as read">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="20 6 9 17 4 12"></polyline>
                            </svg>
                        </button>
                    </li>
                <?php endforeach; ?>
            <?php else: ?>
                <li class="no-notifications"><p>No new notifications</p></li>
            <?php endif; ?>
        </ul>
    </div>
    <?php if (!empty($notifications)): ?>
        <div class="notification-footer">
            <button id="mark-all-read">Mark all as read</button>
        </div>
    <?php endif; ?>
</div>
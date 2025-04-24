<li class="notification-item" data-notification-id="<?= $notification->notificationID ?>">
    <div class="notification-text">
        <p><strong><?= esc($notification->title) ?></strong> - <?= esc($notification->message) ?></p>
        <span class="notification-time"><?= timeAgo($notification->created_at) ?></span>
    </div>
    <button class="mark-read-btn" title="Mark as read">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="20 6 9 17 4 12"></polyline>
        </svg>
    </button>
</li>
<!-- Combined Navbar and Notification Panel -->
<link rel="stylesheet" href="<?=ROOT?>/public/assets/css/navbar.css">
<script>const ROOT = "<?php echo ROOT; ?>";</script>
<script src="<?=ROOT?>/public/assets/js/navbar.js"></script>
<div class="header">
    <div class="logo-container">
        <a href="<?=ROOT?>/public/home">
            <img src="<?=ROOT?>/public/assets/images/logo.png" alt="logo" id="logo">
        </a>
    </div>
    <nav class="navlinks">
        <a href="<?=ROOT?>/public/home">Home</a>
        <a href="<?=ROOT?>/public/about">About</a>
        <a href="<?=ROOT?>/public/contact">Contact</a>
        <?php if (!isset($_SESSION['loggedIn']) || !$_SESSION['loggedIn']) : ?>
            <a href="<?=ROOT?>/public/login">Login</a>
        <?php endif; ?>
        <?php if (isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] && ($_SESSION['role'] == 'customer')) : ?>
            <div class="dropdown">
                <span class="dropdown-toggle">Services</span>
                <div class="dropdown-menu">
                    <a href="<?=ROOT?>/public/selectService/cook" class="dropdown-item" data-service="Cook">Cooking</a>
                    <a href="<?=ROOT?>/public/selectService/maid" class="dropdown-item" data-service="Maid">Maid</a>
                    <a href="<?=ROOT?>/public/selectService/nanny" class="dropdown-item" data-service="Nanny">Nanny</a>
                    <a href="<?=ROOT?>/public/selectService/cook24" class="dropdown-item" data-service="Cook 24-hour Live in">Cook 24H</a>
                    <a href="<?=ROOT?>/public/selectService/allRounder" class="dropdown-item" data-service="All rounder">All-rounder</a>
                </div>
            </div>
        <?php endif; ?>
    </nav>
    <div class="right-section">
        <?php if (!isset($_SESSION['loggedIn']) || !$_SESSION['loggedIn']) : ?>
            <button class="reg-btn" onclick="window.location.href='<?=ROOT?>/public/signup'">Register</button>
        <?php endif; ?>
        <?php if (isset($_SESSION['loggedIn']) && $_SESSION['loggedIn']) : ?>
            <img class="icon" id="profile-toggle" src="<?=ROOT?>/public/assets/images/profile_icon.svg" alt="profile logo">
            <div class="notification-button <?= !empty($notifications) ? 'unread' : '' ?>">
                <img class="icon" id="notification-bell" src="<?=ROOT?>/public/assets/images/bell_icon.svg" alt="notifications logo">
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- conditionally add worker or customer menubar -->
<?php if (isset($_SESSION['loggedIn']) && $_SESSION['loggedIn']) : ?>
    <?php if ($_SESSION['role'] == 'worker') : ?>
        <?php include ROOT_PATH . '/app/views/components/workerMenubar.view.php'; ?>
    <?php else : ?>
        <?php include ROOT_PATH . '/app/views/components/customerMenubar.view.php'; ?>
    <?php endif; ?>
<?php endif; ?>

<!-- Notification Panel -->
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
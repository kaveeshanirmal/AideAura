               <!-- navbar -->
<link rel="stylesheet" href="<?=ROOT?>/public/assets/css/admin_navbar.css">
<?php include ROOT_PATH . '/app/views/components/admin_menubar.view.php'; ?>
<div class="header">
    
    <div class="right-section">
            <img class="icon" id="profile-toggle" src="<?=ROOT?>/public/assets/images/Menu.png" alt="profile logo">
    </div>

    <div class="logo-container">
        <img src="<?=ROOT?>/public/assets/images/logo.png" alt="logo" id="logo">
    </div>

    <div class="toggle-button">
  <div class="toggle-button-container">
    <p>Admin</p>
  </div>
</div>

    <div class="right-section">
            <img class="icon" id="notification-bell" src="<?=ROOT?>/public/assets/images/bell.png" alt="notifications logo">
    </div>
</div>
<?php include ROOT_PATH . '/app/views/components/notificationPanel.view.php'; ?>

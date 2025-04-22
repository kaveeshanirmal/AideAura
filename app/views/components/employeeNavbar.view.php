<!-- navbar -->
<?php $roleName = "user"; ?>

<?php
    if (isset($_SESSION['role'])) {
        switch ($_SESSION['role']) {
            case 'admin':
                $roleName = 'admin';
                break;
            case 'financeManager':
                $roleName = 'Finance Manager';
                break;
            case 'hrManager':
                $roleName = 'HR Manager';
                break;
            case 'opManager':
                $roleName = 'Op Manager';
                break;
        }
    }
?>

<link rel="stylesheet" href="<?=ROOT?>/public/assets/css/<?=$_SESSION['role']?>_navbar.css">
<?php include ROOT_PATH . '/app/views/components/' . $_SESSION['role'] . 'Menubar.view.php'; ?>
<div class="header">
    <div class="right-section">
        <img class="icon" id="profile-toggle" src="<?=ROOT?>/public/assets/images/Menu.png" alt="profile logo">
    </div>

    <div class="logo-container">
        <img src="<?=ROOT?>/public/assets/images/logo.png" alt="logo" id="logo">
    </div>

    <div class="toggle-button">
        <div class="toggle-button-container">

            <p><?=$roleName?></p>
        </div>
    </div>

    <div class="right-section">
        <img class="icon" id="notification-bell" src="<?=ROOT?>/public/assets/images/bell.png" alt="notifications logo">
    </div>
</div>
<?php include ROOT_PATH . '/app/views/components/notificationPanel.view.php'; ?>


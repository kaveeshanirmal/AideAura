<link rel="stylesheet" href="<?=ROOT?>/public/assets/css/OPM_menubar.css">
<script src="<?=ROOT?>/public/assets/js/menubar.js"></script>
<div class="menubar">
<div class="menubar-header">
        <img src="<?=ROOT?>/public/assets/images/womenprofile1.png" alt="Profile" class="profile-image">
        <div class="profile-info">
            <h4><?= $_SESSION['user_name'] ?? 'Oparational Manager' ?></h4>
            </div>
        <button class="menubar-close" id="menubar-close">×</button>
    </div>
    <div class="menubar-content">
        <ul class="menu-items">
        <li><a href="<?=ROOT?>/public/opManager/customers"><img src="<?=ROOT?>/public/assets/images/Roles.png" alt=""> Customers </a></li>
            <li><a href="<?=ROOT?>/public/opManager/workerSchedules"><img src="<?=ROOT?>/public/assets/images/worker-schedule.png" alt=""> Worker Schedules</a></li>
            <li><a href="<?=ROOT?>/public/opManager/bookingDetails"><img src="<?=ROOT?>/public/assets/images/booking-history-icon.png" alt=""> Booking Details </a></li>
            <li><a href="<?=ROOT?>/public/opManager/workerInquiries"><img src="<?=ROOT?>/public/assets/images/help-icon.png" alt=""> Complaints </a></li>
        </ul>
    </div>
    <a href="<?=ROOT?>/public/login/logout" class="logout">
        <img src="<?=ROOT?>/public/assets/images/logout-icon.png" alt=""> Logout
    </a>         
</div>




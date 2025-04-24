<!-- menubar -->
<link rel="stylesheet" href="<?=ROOT?>/public/assets/css/menubar.css">
<script src="<?=ROOT?>/public/assets/js/menubar.js"></script>
<div class="menubar">
    <div class="menubar-header">
        <h3>Profile</h3>
        <button class="menubar-close" id="menubar-close">×</button>
    </div>
    <div class="menubar-content">
        <ul class="menu-items">
            <li><a href="<?=ROOT?>/public/workerProfile/personalInfo"><img src="<?=ROOT?>/public/assets/images/personal-info-icon.png" alt=""> Personal Info</a></li>
            <li><a href="<?=ROOT?>/public/workerVerification/verificationStatus"><img src="<?=ROOT?>/public/assets/images/verification-icon.png" alt=""> Verification Status</a></li>
            <li><a href="<?=ROOT?>/public/workerProfile/workingSchedule"><img src="<?=ROOT?>/public/assets/images/calender-icon.png" alt=""> Working Schedule</a></li>
            <li><a href="<?=ROOT?>/public/workerProfile/faqworker"><img src="<?=ROOT?>/public/assets/images/help-icon.png" alt="">Help</a></li>
        </ul>
    </div>
    <a href="<?=ROOT?>/public/login/logout" class="logout">
        <img src="<?=ROOT?>/public/assets/images/logout-icon.png" alt=""> Logout
    </a>
</div>

<link rel="stylesheet" href="<?=ROOT?>/public/assets/css/HR_menubar.css">
<script src="<?=ROOT?>/public/assets/js/menubar.js"></script>
<div class="menubar">
<div class="menubar-header">
        <img src="<?=ROOT?>/public/assets/images/profile1.jpg" alt="Profile" class="profile-image">
        <div class="profile-info">
            <h4><?= $_SESSION['user_name'] ?? 'HR Manager' ?></h4>
            </div>
        <button class="menubar-close" id="menubar-close">×</button>
    </div>
    <div class="menubar-content">
        <ul class="menu-items">
            <li><a href="<?=ROOT?>/public/HrManager/workerProfiles"><img src="<?=ROOT?>/public/assets/images/worker-profile.png" alt=""> Worker Profiles </a></li>
            <li><a href="<?=ROOT?>/public/HrManager/workerSchedules"><img src="<?=ROOT?>/public/assets/images/worker-schedule.png" alt=""> Worker Schedules</a></li>
            <li><a href="<?=ROOT?>/public/HrManager/verificationRequests"><img src="<?=ROOT?>/public/assets/images/verification-request.png" alt="">Verification Requests</a></li>
            <li><a href="<?=ROOT?>/public/HrManager/managePhysicalVerifications"><img src="<?=ROOT?>/public/assets/images/visiting_worker.webp" alt="">Manage Physical Verifications</a></li>
            <li><a href="<?=ROOT?>/public/HrManager/workerComplaints"><img src="<?=ROOT?>/public/assets/images/help-icon.png" alt=""> Worker Inquiries </a></li>
        </ul>
    </div>
    <a href="<?=ROOT?>/public/login/logout" class="logout">
        <img src="<?=ROOT?>/public/assets/images/logout-icon.png" alt=""> Logout
    </a>         
</div>

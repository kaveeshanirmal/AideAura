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
            <li><a href="<?=ROOT?>/public/profile/personalInfo"><img src="<?=ROOT?>/public/assets/images/personal-info-icon.png" alt=""> Personal Info</a></li>
            <li><a href="<?=ROOT?>/public/profile/bookingHistory"><img src="<?=ROOT?>/public/assets/images/booking-history-icon.png" alt=""> Booking History</a></li>
            <li><a href="<?=ROOT?>/public/profile/paymentHistory"><img src="<?=ROOT?>/public/assets/images/payment-history-icon.png" alt=""> Payment History</a></li>
            <li><a href="<?=ROOT?>/public/profile/faq"><img src="<?=ROOT?>/public/assets/images/help-icon.png" alt=""> Help</a></li>
        </ul>
    </div>
    <a href="<?=ROOT?>/public/login/logout" class="logout">
        <img src="<?=ROOT?>/public/assets/images/logout-icon.png" alt=""> Logout
    </a>
</div>

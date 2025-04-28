<link rel="stylesheet" href="<?=ROOT?>/public/assets/css/accountant_menubar.css">
<script src="<?=ROOT?>/public/assets/js/menubar.js"></script>
<div class="menubar">
<div class="menubar-header">
        <img src="<?=ROOT?>/public/assets/images/profile3.jpg" alt="Profile" class="profile-image">
        <div class="profile-info">
            <h4><?= $_SESSION['user_name'] ?? 'Finance Manager' ?></h4>
        </div>
        <button class="menubar-close" id="menubar-close">×</button>
    </div>
    <div class="menubar-content">
        <ul class="menu-items">
            <li><a href="<?=ROOT?>/public/FinanceManager/bookingReports"><img src="<?=ROOT?>/public/assets/images/Report.png" alt=""> Reports </a></li>
            <li><a href="<?=ROOT?>/public/FinanceManager/priceData"><img src="<?=ROOT?>/public/assets/images/Currency Exchange.png" alt="">Price Details</a></li>
            <li><a href="<?=ROOT?>/public/FinanceManager/cancelledBookings"><img src="<?=ROOT?>/public/assets/images/order history.png" alt="">Cancelled Bookings </a></li>
            <li><a href="<?=ROOT?>/public/FinanceManager/paymentHistory"><img src="<?=ROOT?>/public/assets/images/paymentHistory_icon.png" alt=""> Payment Details </a></li>
            <li><a href="<?=ROOT?>/public/FinanceManager/workerInquiries"><img src="<?=ROOT?>/public/assets/images/Inquiry.png" alt=""> Customer Payment Issues </a></li>
            <li><a href="<?=ROOT?>/public/FinanceManager/workerComplaints"><img src="<?=ROOT?>/public/assets/images/help-icon.png" alt=""> Worker Payment Issues </a></li>
        </ul>
    </div>
    <a href="<?=ROOT?>/public/login/logout" class="logout">
        <img src="<?=ROOT?>/public/assets/images/logout-icon.png" alt=""> Logout
    </a>         
</div>

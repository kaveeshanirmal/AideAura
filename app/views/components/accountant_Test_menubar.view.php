<!-- menubar -->
<link rel="stylesheet" href="<?=ROOT?>/public/assets/css/accountant_Test_menubar.css">
<script src="<?=ROOT?>/public/assets/js/menubar.js"></script>
<div class="menubar">
    <div class="menubar-header">
        <h3>Accountant</h3>
        <button class="menubar-close" id="menubar-close">×</button>
    </div>
    <div class="menubar-content">
        <ul class="menu-items">
            <li><a href="<?=ROOT?>/public/accountantReports"><img src="assets/images/Report.png" alt=""> Reports</a></li>
            <li><a href="<?=ROOT?>/public/accountantpayrate"><img src="<?=ROOT?>/public/assets/images/Currency Exchange.png" alt=""> Payment Rates</a></li>
            <li><a href="<?=ROOT?>/public/accountantPaymentHistory"><img src="<?=ROOT?>/public/assets/images/paymentHistory_icon.png" alt=""> Payment History</a></li>
        </ul>
    </div>
    <a href="<?=ROOT?>/public/login" class="logout">
        <img src="<?=ROOT?>/public/assets/images/logout-icon.png" alt=""> Logout
    </a>         
</div>

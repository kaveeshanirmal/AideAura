<style>
    body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f0f0f0;
    overflow-x: hidden;
    z-index: 999
}
.menubar {
    position: fixed;
    top: 85px;
    right: -250px;
    width: 250px;
    height: calc(100vh - 60px);
    background-color: #ffffff;
    box-shadow: -2px 0 5px rgba(0,0,0,0.1);
    padding: 20px;
    box-sizing: border-box;
    transition: right 0.3s ease-in-out;
    z-index: 999;
}
.menubar.active {
    right: 0;
    position: fixed;
}
.profile {
    display: flex;
    align-items: center;
    margin-bottom: 30px;
}
.profile img {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    margin-right: 15px;
}
.profile-info h2 {
    margin: 0;
    font-size: 18px;
}
.profile-info p {
    margin: 5px 0 0;
    font-size: 14px;
    color: #666;
}
.menu-items {
    list-style-type: none;
    padding: 0;
    margin: 0;
}
.menu-items li {
    margin-bottom: 20px;
}
.menu-items a {
    display: flex;
    align-items: center;
    text-decoration: none;
    color: #333;
    font-size: 16px;
}
.menu-items img {
    width: 20px;
    height: 20px;
    margin-right: 15px;
}
.logout {
    position: absolute;
    bottom: 20px;
    left: 20px;
    display: flex;
    align-items: center;
    text-decoration: none;
    color: #333;
    font-size: 16px;
}
.logout img {
    width: 20px;
    height: 20px;
    margin-right: 15px;
}

</style>
<script>
        document.addEventListener('DOMContentLoaded', function() {
            const menuToggle = document.getElementById('profile-toggle');
            const menubar = document.querySelector('.menubar');

            if (menuToggle && menubar) {
                menuToggle.addEventListener('click', () => {
                    menubar.classList.toggle('active');
                });
            } else {
                console.error('Menu toggle or menubar element not found');
            }
        });
</script>
<div class="menubar">
    <div class="profile">
        <img src="<?=ROOT?>/public/assets/images/avatar-image.png" alt="profile image">
        <div class="profile-info">
            <h2>Moda Tharindu</h2>
            <p>+94 77 8475154</p>
        </div>
    </div>
    <ul class="menu-items">
        <li><a href="<?=ROOT?>/public/profile/personalInfo"><img src="<?=ROOT?>/public/assets/images/personal-info-icon.png" alt=""> Personal Info</a></li>
        <li><a href="#"><img src="<?=ROOT?>/public/assets/images/booking-history-icon.png" alt=""> Booking History</a></li>
        <li><a href="#"><img src="<?=ROOT?>/public/assets/images/payment-history-icon.png" alt=""> Payment History</a></li>
        <li><a href="#"><img src="<?=ROOT?>/public/assets/images/help-icon.png" alt=""> Help</a></li>
    </ul>
    <a href="#" class="logout"><img src="<?=ROOT?>/public/assets/images/logout-icon.png" alt=""> Logout</a>
</div>

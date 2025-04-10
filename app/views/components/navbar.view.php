<!-- navbar -->
<link rel="stylesheet" href="<?=ROOT?>/public/assets/css/navbar.css">
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
            <img class="icon" id="notification-bell" src="<?=ROOT?>/public/assets/images/bell_icon.svg" alt="notifications logo">
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
<!-- notification panel -->
<?php include ROOT_PATH . '/app/views/components/notificationPanel.view.php'; ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const dropdownToggle = document.querySelector('.dropdown-toggle');
        const dropdownMenu = document.querySelector('.dropdown-menu');
        const ROOT = "<?php echo ROOT; ?>";

        // Toggle dropdown on mobile/touch devices
        dropdownToggle.addEventListener('hover', function(e) {
            e.preventDefault();
            dropdownMenu.style.display = dropdownMenu.style.display === 'block' ? 'none' : 'block';
        });

        // Close dropdown when hovering outside
        document.addEventListener('hover', function(e) {
            if (!dropdownToggle.contains(e.target) && !dropdownMenu.contains(e.target)) {
                dropdownMenu.style.display = 'none';
            }
        });

        // Handle service selection from dropdown
        const serviceLinks = document.querySelectorAll('.dropdown-item');
        serviceLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const service = this.dataset.service;
                const href = this.href;

                fetch(`${ROOT}/public/SelectService`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'service=' + encodeURIComponent(service)
                })
                    .then(function(response) {
                        if (response.ok) {
                            window.location.href = href;
                        } else {
                            console.error('Error saving service selection');
                        }
                    })
                    .catch(function(error) {
                        console.error('Error:', error);
                    });
            });
        });
    });
</script>
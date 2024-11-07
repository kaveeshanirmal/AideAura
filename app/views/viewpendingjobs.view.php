<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Complete</title>
    <link rel="stylesheet" href="assets/css/paymentcomplete.css">
</head>

<body>
    <!-- Navbar -->
    <?php include(ROOT_PATH . '/app/views/components/navbar.view.php');?>

<div class="job-container">
    <!-- Back Button -->
    <a href="javascript:history.back()" class="back-btn">
        <span class="back-arrow">⟨</span>
        Back
    </a>

    <!-- Pending Jobs Title -->
    <div class="pending-title">
        <img src="assets/images/pending-icon.png" alt="Pending" class="pending-icon">
        <h2>Pending Jobs</h2>
    </div>

    <!-- Job Cards Grid -->
    <div class="job-cards-grid">
        <?php for($i = 0; $i < 4; $i++) { ?>
            <div class="job-card">
                <div class="card-header">
                    <div class="user-info">
                        <img src="assets/images/user-icon.png" alt="User" class="user-icon">
                        <span>Mr.Samuwel John</span>
                    </div>
                    <button class="rating-btn">Rating/Reviews ›</button>
                </div>

                <div class="job-time-info">
                    <div>START: 10 August 2024</div>
                    <div>Time: 8:00 am</div>
                </div>

                <div class="job-details">
                    <div class="detail-row">
                        <span class="label">Location:</span>
                        <span class="value">Colombo - Rathmalana</span>
                    </div>

                    <div class="detail-row">
                        <span class="label">Contact:</span>
                        <span class="value">+94 70 150 6785</span>
                    </div>

                    <div class="detail-row">
                        <span class="label">Job discription:</span>
                        <span class="value">House with Bathrooms 2, Bed rooms 3, kitchen, dinning room</span>
                    </div>

                    <div class="detail-row">
                        <span class="label">Email:</span>
                        <span class="value">john546@gmail.com</span>
                    </div>

                    <div class="detail-row">
                        <span class="label">Time:</span>
                        <span class="value">8.00a.m-5.00p.m</span>
                    </div>
                </div>

                <div class="card-actions">
                    <button class="accept-btn">Accept</button>
                    <button class="decline-btn">Decline</button>
                </div>
            </div>
        <?php } ?>
    </div>
</div>

<!-- Footer -->
<?php include(ROOT_PATH . '/app/views/components/footer.view.php'); ?>
</body>
</html>
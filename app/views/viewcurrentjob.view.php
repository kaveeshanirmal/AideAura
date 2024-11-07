<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Orders</title>
    <link rel="stylesheet" href="assets/css/viewcurrentjob.css">
</head>
<body>
    <!-- Navbar -->
    <?php include(ROOT_PATH . '/app/views/components/navbar.view.php');?>

<main  style="margin-top: 180px;">
<!-- Back Button -->
<div class="back-button">
    <button onclick="history.back()"><i class="arrow-left"></i> Back</button>
</div>

<!-- Job Cards Container -->
<div class="job-cards-container">
<?php for ($i = 0; $i < 4; $i++) : ?>
    <!-- Job Card -->
    <div class="job-card">
        <div class="card-header">
            <div class="user-info">
                <img src="assets/images/user_icon.png" alt="User" class="user-icon">
                <span class="user-name">Mr.Samuwel John</span>
            </div>
            <button class="rating-button">Rating/Reviews ></button>
        </div>
        
        <div class="job-dates">
            <span>START: 10 August 2024</span>
            <span>END: 20 August 2024</span>
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
    </div>
    <?php endfor; ?>
    <!-- Repeat the job-card div structure three more times for the other cards -->
</div>
</main>

  <!-- Footer -->
  <?php include(ROOT_PATH . '/app/views/components/footer.view.php'); ?>
</body>
</html>

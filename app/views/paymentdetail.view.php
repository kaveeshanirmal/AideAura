<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Details</title>
    <link rel="stylesheet" href="assets/css/paymentdetail.css">
</head>
<body>
    <!-- Navbar -->
    <?php include(ROOT_PATH . '/app/views/components/navbar.view.php');?>

    <!-- Main Content -->
    <main>
        <div class="center-content">
            <button class="previous-orders-btn">Previous orders ></button>
            <h1>Your Orders</h1>
        </div>
        
        <div class="order-card">
            <div class="order-details">
                <div class="user-info">
                    <img src="assets/images/user_icon.png" alt="User Icon" class="user-icon">
                    <span class="user-name">Mr.Samuwel John</span>
                </div>
                <div class="service-info">
                    <span class="service-type">Cleaner</span>
                    <span class="order-date">10 August 2024</span>
                </div>
                <div class="price-breakdown">
                    <div class="price-row123">
                        <span class="span2">Amount(Rs)</span>
                        <hr class="small-line">
                    </div>
                    <div class="price-row">
                        <span>Order price</span>
                        <span>35000.00</span>
                    </div>
                    <div class="price-row">
                        <span>Discount</span>
                        <span>-1500.00</span>
                    </div>
                    <div class="price-rowlast">
                        <hr class="small-line12">
                        <span class="span1">Total price</span>
                        <span class="span2">33500.00</span>
                        <hr class="small-line12">
                    </div>
                </div>
                <button class="pay-btn">Pay</button>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <?php include(ROOT_PATH . '/app/views/components/footer.view.php'); ?>
</body>
</html>

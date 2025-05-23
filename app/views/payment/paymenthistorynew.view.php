<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Orders</title>
    <link rel="stylesheet" href="assets/css/paymenthistorynew.css">
</head>
<body>
    <!-- Navbar -->
    <?php include(ROOT_PATH . '/app/views/components/navbar.view.php');?>

    <!-- Main Content -->
    <main>
        <div class="container">
            <div class="navigation-buttons">
                <button class="back-button">&lt; Back</button>
                <button class="see-more-button">See more &gt;</button>
            </div>
            <div class="section-title">
                    <img src="assets/images/Vector.png" alt="Clock Icon" class="section-icon">
                    <h3>Pending Payments</h3>
                </div>
            <div class="order-grid">
                <?php for ($i = 0; $i < 4; $i++) : ?>
                    <div class="order-card">
                        <div class="order-header">
                            <div class="user-info">
                                <img src="assets/images/user_icon.png" alt="User Icon" class="user-icon">
                                <span class="user-name">Mr.Samuwel John</span>
                            </div>
                        </div>
                        <div class="service-info">
                            <span class="service-type">Cleaner</span>
                            <span class="order-date">10 August 2024</span>
                        </div>
                        
                    <div class="order-details">
                        <div class="price-breakdown">
                            <div class="price-row amount-header">
                                <span></span>
                                <span>Amount(Rs)</span>
                            </div>
                            <div class="price-row">
                                <span></span>
                                <hr class="top-line">
                            </div>
                            <div class="price-row">
                                <span>Order Price</span>
                                <span>35000.00</span>
                            </div>
                            <div class="price-row">
                                <span>Discount</span>
                                <span>-1500.00</span>
                            </div>
                            <div class="price-row">
                                <span></span>
                                <hr class="middle-line">
                            </div>
                            <div class="price-row total">
                                <span>Total price</span>
                                <span>33500.00</span>
                            </div>
                            <div class="price-row">
                                <span></span>
                                <hr class="bottom-line">
                            </div>
                        </div>
                    </div class="Clock">
                    <button class="paid-button"><img src="assets/images/white_clock.png" alt="User Icon" class="white-clock">Pending!</button>
                </div>
                <?php endfor; ?>
            </div>

            <div class="section-title">
                    <h3>Completed Payments</h3>
                </div>
            <div class="order-grid">
                <?php for ($i = 0; $i < 4; $i++) : ?>
                    <div class="order-card2">
                        <div class="order-header">
                            <div class="user-info">
                                <img src="assets/images/user_icon.png" alt="User Icon" class="user-icon">
                                <span class="user-name">Mr.Samuwel John</span>
                            </div>
                            <button class="rate-now-button">Rate Now &gt;</button>
                        </div>
                        <div class="service-info">
                            <span class="service-type">10.30 pm</span>                            <span class="order-date">10 August 2024</span>
                        </div>
                        
                    <div class="order-details">
                        <div class="price-breakdown">
                            <div class="price-row amount-header">
                                <span></span>
                                <span>Amount(Rs)</span>
                            </div>
                            <div class="price-row">
                                <span></span>
                                <hr class="top-line">
                            </div>
                            <div class="price-row">
                                <span>Order Price</span>
                                <span>35000.00</span>
                            </div>
                            <div class="price-row">
                                <span>Discount</span>
                                <span>-1500.00</span>
                            </div>
                            <div class="price-row">
                                <span></span>
                                <hr class="middle-line">
                            </div>
                            <div class="price-row total">
                                <span>Total price</span>
                                <span>33500.00</span>
                            </div>
                            <div class="price-row">
                                <span></span>
                                <hr class="bottom-line">
                            </div>
                        </div>
                    </div>
                    <button class="paid-button">✓ Paid</button>
                </div>
                <?php endfor; ?>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <?php include(ROOT_PATH . '/app/views/components/footer.view.php'); ?>
</body>
</html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment History</title>
    <link rel="stylesheet" href="assets/css/paymenthistory.css">
</head>
<body>
    <!-- Navbar -->
    <?php include(ROOT_PATH . '/app/views/components/navbar.view.php');?>

    <!-- Main Content -->
    <main>
        <div class="container">
            <!-- Navigation Buttons -->
            <div class="navigation-buttons">
                <button class="back-button">&lt; Back</button>
                <button class="see-more-button">See more &gt;</button>
            </div>

            <!-- Pending Payments Section -->
            <section class="pending-payments">
            <div class="section-title">
                    <img src="assets/images/Vector.png" alt="Clock Icon" class="section-icon">
                    <h3>Pending Payments</h3>
                </div>
                <div class="order-grid">
                    <?php for ($i = 0; $i < 2; $i++) : ?>
                    <div class="order-card">
                        <div class="order-header">
                            <div class="user-info">
                                <img src="assets/images/user_icon.png" alt="User Icon" class="user-icon">
                                <span class="user-name">Mr. Samuwel John</span>
                            </div>
                        </div>
                        <div class="service-info">
                            <span class="service-type">Cleaner</span>
                            <span class="order-date">10 August 2024</span>
                        </div>
                        <div class="order-details">
                            <div class="price-breakdown">
                                <div class="price-row">
                                    <span>Order Price</span>
                                    <span>35,000.00</span>
                                </div>
                                <div class="price-row">
                                <span></span>
                                <hr class="top-line">
                            </div>
                                <div class="price-row">
                                    <span>Discount</span>
                                    <span>-1,500.00</span>
                                </div>
                                <div class="price-row">
                                <span></span>
                                <hr class="middle-line">
                            </div>
                                <div class="price-row total">
                                    <span>Total Price</span>
                                    <span>33,500.00</span>
                                </div>
                                <div class="price-row">
                                <span></span>
                                <hr class="bottom-line">
                            </div>
                            </div>
                        </div>
                        <button class="pay-now-button">Pay Now</button>
                    </div>
                    <?php endfor; ?>
                </div>
            </section>

            <!-- Completed Payments Section -->
            <section class="completed-payments">
            <div class="section-title">               
            <h3>Completed Payments</h3>
                </div>
                <div class="order-grid">
                    <?php for ($i = 0; $i < 4; $i++) : ?>
                    <div class="order-card11">
                        <div class="order-header">
                            <div class="user-info">
                                <img src="assets/images/user_icon.png" alt="User Icon" class="user-icon">
                                <span class="user-name">Ms. Anna Smith</span>
                            </div>
                            <div class="rating">
                            <button class="see-more-buttonupdate">Rate Now &gt;</button>
                            </div>
                        </div>
                        <div class="service-info">
                            <span class="service-type">Cleaner</span>
                            <span class="order-date">15 July 2024</span>
                        </div>
                        <div class="order-details">
                            <div class="price-breakdown">
                                <div class="price-row">
                                    <span>Order Price</span>
                                    <span>45,000.00</span>
                                </div>
                                <div class="price-row">
                                    <span>Discount</span>
                                    <span>-2,000.00</span>
                                </div>
                                <div class="price-row total">
                                    <span>Total Price</span>
                                    <span>43,000.00</span>
                                </div>
                            </div>
                        </div>
                        <button class="paid-button">✓ Paid</button>
                    </div>
                    <?php endfor; ?>
                </div>
            </section>
        </div>
    </main>

    <!-- Footer -->
    <?php include(ROOT_PATH . '/app/views/components/footer.view.php'); ?>
</body>
</html>
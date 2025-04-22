<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Form</title>
    <link rel="stylesheet" href="<?php echo ROOT; ?>/public/assets/css/paymentform.css">
</head>
<body>
    <!-- Navbar -->
    <?php include(ROOT_PATH . '/app/views/components/navbar.view.php');?>

    <!-- Main Content -->
    <main>
        <section class="payment-form">
            <h1>Enter Your Card Details</h1>
            <form action="paymentproceed1">
                <div class="payment-method">
                    <label for="payment-method">Payment Method</label>
                    <div class="card-logos">
                        <img src="assets/images/mastercard.png" alt="Mastercard" class="card-logo" id="mastercard">
                        <img src="assets/images/visa.png" alt="Visa" class="card-logo" id="visa">
                        <img src="assets/images/americanexpress.png" alt="American Express" class="card-logo" id="americanexpress">
                        <img src="assets/images/discover.png" alt="Discover" class="card-logo" id="discover">
                    </div>
                </div>
                <div class="form-group">
                    <label for="name-on-card">Name on card</label>
                    <input type="text" id="name-on-card" placeholder="Meet Patel" required>
                </div>
                <div class="form-group">
                    <label for="card-number">Card number</label>
                    <input type="text" id="card-number" placeholder="0000 0000 0000 0000" required>
                </div>
                <div class="form-group">
                    <label for="card-expiration">Card expiration</label>
                    <div class="expiration-date">
                        <select id="expiration-month" required>
                            <option value="">Month</option>
                            <option value="01">01</option>
                            <option value="02">02</option>
                            <option value="03">03</option>
                            <option value="04">04</option>
                            <option value="05">05</option>
                            <option value="06">06</option>
                            <option value="07">07</option>
                            <option value="08">08</option>
                            <option value="09">09</option>
                            <option value="10">10</option>
                            <option value="11">11</option>
                            <option value="12">12</option>
                        </select>
                        <select id="expiration-year" required>
                            <option value="">Year</option>
                            <option value="2024">2024</option>
                            <option value="2025">2025</option>
                            <option value="2027">2027</option>
                            <option value="2028">2028</option>
                            <option value="2029">2029</option>
                            <option value="2030">2030</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="card-security-code">Card Security Code</label>
                    <div class="security-code-input">
                        <input type="text" id="card-security-code" placeholder="Code" required>
                        <span class="tooltip">?</span>
                    </div>
                </div>
                <button type="submit">Continue</button>
            </form>
        </section>
    </main>

    <!-- Footer -->
    <?php include(ROOT_PATH . '/app/views/components/footer.view.php'); ?>
</body>
</html>
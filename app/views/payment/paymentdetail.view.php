<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment Summary</title>
    <link rel="stylesheet" href="<?php echo ROOT; ?>/public/assets/css/paymentdetail.css">
</head>
<body>
<?php include(ROOT_PATH . '/app/views/components/navbar.view.php');?>
<div class="container">
    <h2>Payment Summary</h2>
    <div class="progress-bar">
        <div class="progress"></div>
    </div>

    <div class="summary-container">
        <div class="summary-section">
            <h3 class="section-title">Booking Summary</h3>

            <div class="summary-row">
                <span class="summary-label">Service Type:</span>
                <span class="summary-value"><?php echo isset($_SESSION['booking_info']['serviceType']) ? htmlspecialchars(ucfirst($_SESSION['booking_info']['serviceType'])) : 'N/A'; ?></span>
            </div>

            <div class="summary-row">
                <span class="summary-label">Date:</span>
                <span class="summary-value"><?php echo isset($_SESSION['booking_info']['preferred_date']) ? htmlspecialchars($_SESSION['booking_info']['preferred_date']) : 'N/A'; ?></span>
            </div>

            <div class="summary-row">
                <span class="summary-label">Time:</span>
                <span class="summary-value"><?php echo isset($_SESSION['booking_info']['arrival_time']) ? htmlspecialchars($_SESSION['booking_info']['arrival_time']) : 'N/A'; ?></span>
            </div>

            <div class="summary-row">
                <span class="summary-label">Location:</span>
                <span class="summary-value"><?php echo isset($_SESSION['booking_info']['service_location']) ? htmlspecialchars($_SESSION['booking_info']['service_location']) : 'N/A'; ?></span>
            </div>
        </div>

        <div class="summary-section">
            <h3 class="section-title">Service Details</h3>

            <div class="summary-row">
                <span class="summary-label">Worker Gender:</span>
                <span class="summary-value">
                    <?php
                    if(isset($_SESSION['booking_info']['gender'])) {
                        if(is_array($_SESSION['booking_info']['gender'])) {
                            echo htmlspecialchars(implode(', ', $_SESSION['booking_info']['gender']));
                        } else {
                            echo htmlspecialchars($_SESSION['booking_info']['gender']);
                        }
                    } else {
                        echo 'N/A';
                    }
                    ?>
                </span>
            </div>

            <div class="summary-row">
                <span class="summary-label">People Count:</span>
                <span class="summary-value">
                    <?php
                    if(isset($_SESSION['booking_info']['num_people'])) {
                        if(is_array($_SESSION['booking_info']['num_people'])) {
                            echo htmlspecialchars(implode(', ', $_SESSION['booking_info']['num_people']));
                        } else {
                            echo htmlspecialchars($_SESSION['booking_info']['num_people']);
                        }
                    } else {
                        echo 'N/A';
                    }
                    ?>
                </span>
            </div>

            <div class="summary-row">
                <span class="summary-label">Meals:</span>
                <span class="summary-value">
                    <?php
                    if(isset($_SESSION['booking_info']['num_meals'])) {
                        if(is_array($_SESSION['booking_info']['num_meals'])) {
                            echo htmlspecialchars(implode(', ', array_map('ucfirst', $_SESSION['booking_info']['num_meals'])));
                        } else {
                            echo htmlspecialchars(ucfirst($_SESSION['booking_info']['num_meals']));
                        }
                    } else {
                        echo 'N/A';
                    }
                    ?>
                </span>
            </div>

            <div class="summary-row">
                <span class="summary-label">Diet:</span>
                <span class="summary-value">
                    <?php
                    if(isset($_SESSION['booking_info']['diet'])) {
                        if(is_array($_SESSION['booking_info']['diet'])) {
                            echo htmlspecialchars(implode(', ', array_map('ucfirst', $_SESSION['booking_info']['diet'])));
                        } else {
                            echo htmlspecialchars(ucfirst($_SESSION['booking_info']['diet']));
                        }
                    } else {
                        echo 'N/A';
                    }
                    ?>
                </span>
            </div>

            <div class="summary-row">
                <span class="summary-label">Add-ons:</span>
                <span class="summary-value">
                    <?php
                    if(isset($_SESSION['booking_info']['addons']) && is_array($_SESSION['booking_info']['addons'])) {
                        echo htmlspecialchars(implode(', ', array_map('ucfirst', $_SESSION['booking_info']['addons'])));
                    } else {
                        echo 'None';
                    }
                    ?>
                </span>
            </div>
        </div>

        <div class="summary-section">
            <h3 class="section-title">Customer Details</h3>

            <div class="summary-row">
                <span class="summary-label">Name:</span>
                <span class="summary-value"><?php echo isset($_SESSION['booking_info']['customer_name']) ? htmlspecialchars($_SESSION['booking_info']['customer_name']) : 'N/A'; ?></span>
            </div>

            <div class="summary-row">
                <span class="summary-label">Phone:</span>
                <span class="summary-value"><?php echo isset($_SESSION['booking_info']['contact_phone']) ? htmlspecialchars($_SESSION['booking_info']['contact_phone']) : 'N/A'; ?></span>
            </div>

            <div class="summary-row">
                <span class="summary-label">Email:</span>
                <span class="summary-value"><?php echo isset($_SESSION['booking_info']['contact_email']) ? htmlspecialchars($_SESSION['booking_info']['contact_email']) : 'N/A'; ?></span>
            </div>
        </div>

        <div class="payment-section">
            <div class="price-breakdown">
                <div class="price-row">
                    <span class="price-label">Base Service:</span>
                    <span class="price-value">Rs. <?php echo isset($_SESSION['booking_info']['base_cost']) ? number_format($_SESSION['booking_info']['base_cost'], 2) : '0.00'; ?></span>
                </div>

                <div class="price-row">
                    <span class="price-label">Add-ons:</span>
                    <span class="price-value">Rs. <?php echo isset($_SESSION['booking_info']['addon_cost']) ? number_format($_SESSION['booking_info']['addon_cost'], 2) : '0.00'; ?></span>
                </div>

                <div class="price-row">
                    <span class="price-label">Service Fee:</span>
                    <span class="price-value">Rs. <?php echo isset($_SESSION['booking_info']['service_fee']) ? number_format($_SESSION['booking_info']['service_fee'], 2) : '100.00'; ?></span>
                </div>

                <div class="price-row total-row">
                    <span class="price-label">Total:</span>
                    <span class="price-value total-amount">Rs. <?php echo isset($_SESSION['booking_info']['total_cost']) ? number_format($_SESSION['booking_info']['total_cost'], 2) : '0.00'; ?></span>
                </div>
            </div>

            <!-- Rest of your payment form remains the same -->
            <div class="payment-methods">
                <h3 class="section-title">Select Payment Method</h3>
                <div class="payment-options">
                    <div class="payment-option">
                        <input type="radio" id="card-payment" name="payment_method" value="card" checked>
                        <label for="card-payment">Card Payment</label>
                    </div>
                    <div class="payment-option">
                        <input type="radio" id="cash-payment" name="payment_method" value="cash">
                        <label for="cash-payment">Cash on Delivery</label>
                    </div>
                </div>

                <div id="card-details" class="payment-details">
                    <div class="form-row">
                        <label for="card-number">Card Number</label>
                        <input type="text" id="card-number" name="card_number" placeholder="1234 5678 9012 3456" maxlength="19">
                    </div>
                    <div class="form-row card-info-row">
                        <div class="card-expiry">
                            <label for="expiry-date">Expiry Date</label>
                            <input type="text" id="expiry-date" name="expiry_date" placeholder="MM/YY" maxlength="5">
                        </div>
                        <div class="card-cvv">
                            <label for="cvv">CVV</label>
                            <input type="text" id="cvv" name="cvv" placeholder="123" maxlength="3">
                        </div>
                    </div>
                    <div class="form-row">
                        <label for="card-name">Name on Card</label>
                        <input type="text" id="card-name" name="card_name" placeholder="Enter name as on card">
                    </div>
                </div>
            </div>

            <div class="terms-section">
                <div class="checkbox-container">
                    <input type="checkbox" id="terms-checkbox" name="terms_accepted" required>
                    <label for="terms-checkbox" class="checkbox-label">
                        I agree to the terms and conditions and cancellation policy
                    </label>
                </div>
                <div id="terms-error" class="error-message" style="display: none">You must agree to the terms and conditions</div>
            </div>
        </div>

        <div class="button-group">
            <button type="button" class="btn-previous" id="back-btn">Previous</button>
            <button type="button" class="btn-next" id="pay-btn">Complete Payment</button>
        </div>
    </div>
</div>
<?php include(ROOT_PATH . '/app/views/components/footer.view.php');?>

<!-- Rest of your JavaScript remains the same -->
<script>
    const sessionDebug = <?php echo isset($session_debug) ? $session_debug : '{}'; ?>;
    console.log("Full Session Data:", sessionDebug);

    const bookingInfo = <?php echo json_encode($_SESSION['booking_info'] ?? []); ?>;
    console.log("Booking Info:", bookingInfo);

    const ROOT = "<?php echo ROOT; ?>";
    console.log(bookingInfo);
    document.addEventListener('DOMContentLoaded', function() {
        // Set minimum date to today for the date picker
        const backButton = document.getElementById("back-btn");
        backButton.addEventListener("click", (event) => {
            event.preventDefault();
            window.history.back(); // Navigate to the previous page
        });

        const payButton = document.getElementById("pay-btn");
        payButton.addEventListener("click", (event) => {
            event.preventDefault();

            // Validate form
            if (validatePaymentForm()) {
                // Submit payment
                window.location.href = `${ROOT}/public/selectService/confirmation`;
            }
        });

        // Toggle payment method details
        const cardPayment = document.getElementById('card-payment');
        const cashPayment = document.getElementById('cash-payment');
        const cardDetails = document.getElementById('card-details');

        cardPayment.addEventListener('change', function() {
            if (this.checked) {
                cardDetails.style.display = 'block';
            }
        });

        cashPayment.addEventListener('change', function() {
            if (this.checked) {
                cardDetails.style.display = 'none';
            }
        });

        // Card number formatting
        const cardNumber = document.getElementById('card-number');
        cardNumber.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            let formattedValue = '';

            for (let i = 0; i < value.length; i++) {
                if (i > 0 && i % 4 === 0) {
                    formattedValue += ' ';
                }
                formattedValue += value[i];
            }

            e.target.value = formattedValue;
        });

        // Expiry date formatting
        const expiryDate = document.getElementById('expiry-date');
        expiryDate.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');

            if (value.length > 2) {
                value = value.substring(0, 2) + '/' + value.substring(2, 4);
            }

            e.target.value = value;
        });
    });

    function validatePaymentForm() {
        let isValid = true;

        // Clear previous error messages
        document.getElementById('terms-error').style.display = 'none';

        // Validate terms acceptance
        const termsChecked = document.getElementById('terms-checkbox').checked;
        if (!termsChecked) {
            document.getElementById('terms-error').style.display = 'block';
            isValid = false;
        }

        // Validate card details if card payment is selected
        if (document.getElementById('card-payment').checked) {
            const cardNumber = document.getElementById('card-number').value.trim();
            const expiryDate = document.getElementById('expiry-date').value.trim();
            const cvv = document.getElementById('cvv').value.trim();
            const cardName = document.getElementById('card-name').value.trim();

            if (!cardNumber || !expiryDate || !cvv || !cardName) {
                // Show error for card details
                isValid = false;
                alert('Please fill in all card details');
            }
        }

        return isValid;
    }
</script>
</body>
</html>
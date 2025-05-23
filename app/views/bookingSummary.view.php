<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment Summary</title>
    <link rel="stylesheet" href="<?php echo ROOT; ?>/public/assets/css/bookingSummary.css">
</head>
<body>
<?php include(ROOT_PATH . '/app/views/components/navbar.view.php');?>
<div class="container">
    <h2>Booking Summary</h2>
    <div class="progress-bar">
        <div class="progress"></div>
    </div>

    <div class="summary-container">
        <div class="summary-section">
            <h3 class="section-title">Booking Details</h3>

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

            <!-- Dynamically display service-specific fields -->
            <?php
            $excludedKeys = [
                'serviceType', 'total_cost', 'base_price', 'addon_price',
                'customer_name', 'contact_phone', 'contact_email',
                'service_location', 'preferred_date', 'arrival_time',
                'data_acknowledgment', 'gender'
            ];
            foreach ($_SESSION['booking_info'] as $key => $value) {
                if (in_array($key, $excludedKeys) || empty($value)) {
                    continue;
                }
                $label = ucwords(str_replace(['_', '-'], ' ', $key));
                ?>
                <div class="summary-row">
                    <span class="summary-label"><?php echo $label; ?>:</span>
                    <span class="summary-value">
                        <?php
                        if (is_array($value)) {
                            echo htmlspecialchars(implode(', ', array_map('ucfirst', $value)));
                        } else {
                            echo htmlspecialchars(ucfirst($value));
                        }
                        ?>
                    </span>
                </div>
            <?php } ?>

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
                    <span class="price-value">Rs. <?php echo isset($_SESSION['booking_info']['base_price']) ? number_format($_SESSION['booking_info']['base_price'], 2) : '0.00'; ?></span>
                </div>

                <div class="price-row">
                    <span class="price-label">Add-ons:</span>
                    <span class="price-value">Rs. <?php echo isset($_SESSION['booking_info']['addon_price']) ? number_format($_SESSION['booking_info']['addon_price'], 2) : '0.00'; ?></span>
                </div>

                <div class="price-row">
                    <span class="price-label">Service Fee:</span>
                    <span class="price-value">Rs. <?php echo isset($_SESSION['booking_info']['service_fee']) ? number_format($_SESSION['booking_info']['service_fee'], 2) : '0.00'; ?></span>
                </div>

                <div class="price-row total-row">
                    <span class="price-label">Total:</span>
                    <span class="price-value total-amount">Rs. <?php echo isset($_SESSION['booking_info']['total_cost']) ? number_format($_SESSION['booking_info']['total_cost'], 2) : '0.00'; ?></span>
                </div>
            </div>
        </div>

        <div class="button-group">
            <button type="button" class="btn-previous" id="back-btn">Previous</button>
            <button type="button" class="btn-next" id="pay-btn">Find a Worker</button>
        </div>
    </div>
</div>
<?php include(ROOT_PATH . '/app/views/components/footer.view.php');?>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const backButton = document.getElementById("back-btn");
        if (backButton) {
            backButton.addEventListener("click", (event) => {
                event.preventDefault();
                window.history.back();
            });
        }

        const payButton = document.getElementById("pay-btn");
        if (payButton) {
            payButton.addEventListener("click", (event) => {
                event.preventDefault();
                const redirectUrl = `${ROOT}/public/SearchForWorker/processing`;
                console.log("Redirecting to:", redirectUrl);
                window.location.href = redirectUrl;
            });
        }
    });
</script>

</body>
</html>
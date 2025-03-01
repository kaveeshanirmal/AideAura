<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Requirement Gathering Form</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <link rel="stylesheet" href="<?php echo ROOT; ?>/public/assets/css/serviceForms/bookingInfo.css">
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script src="<?php echo ROOT; ?>/public/assets/js/bookingInfo.js" defer></script>
</head>
<body>
<?php include(ROOT_PATH . '/app/views/components/navbar.view.php');?>
<div class="container">
    <h2>Booking Details</h2>
    <div class="progress-bar">
        <div class="progress"></div>
    </div>

    <form>
        <!-- Personal Information Section (Collapsible) -->
        <div class="form-section collapsible-section">
            <div class="section-header">
                <h3 class="section-title">Your Information</h3>
                <button type="button" class="toggle-btn" aria-label="Toggle personal information">
                    <span class="toggle-icon"></span>
                </button>
            </div>
            <div class="section-content collapsed">
                <div class="form-row">
                    <label for="customer-name">Name</label>
                    <input type="text" id="customer-name" name="customer_name" placeholder="Enter your full name" required value="Kasun Kalhara">
                    <div id="name-error" class="error-message" style="display: none">Full Name is required.</div>
                </div>

                <div class="form-row">
                    <label for="contact-phone">Phone Number</label>
                    <input type="tel" id="contact-phone" name="contact_phone" placeholder="e.g., 0712345678" required value="0712345678">
                    <div id="phone-error" class="error-message" style="display: none">Phone number must be exactly 10 digits.</div>
                </div>

                <div class="form-row">
                    <label for="contact-email">Email Address</label>
                    <input type="email" id="contact-email" name="contact_email" placeholder="your.email@example.com" required value="KasunKalhara@example.com">
                    <div id="email-error" class="error-message" style="display: none">Please enter a valid email address.</div>
                </div>
            </div>
            <!-- Summary of personal info (shown when collapsed) -->
            <div class="section-summary">
                <div class="summary-row">
                    <span class="summary-label">Name:</span>
                    <span class="summary-value" data-field="customer-name">Kasun Kalhara</span>
                </div>
                <div class="summary-row">
                    <span class="summary-label">Contact:</span>
                    <span class="summary-value" data-field="contact-phone">0712345678</span>
                </div>
                <div class="summary-row">
                    <span class="summary-label">Email:</span>
                    <span class="summary-value" data-field="contact-email">KasunKalhara@gmail.com</span>
                </div>
            </div>
        </div>

        <!-- Service Details Section -->
        <div class="form-section">
            <h3 class="section-title">Service Details</h3>
            <div class="form-row">
                <label for="service-location">Service Location</label>
                <input type="text" id="service-location" name="service_location" placeholder="Enter your full address" required>
                <div id="location-error" class="error-message" style="display: none">Service location is required.</div>
                <button type="button" id="pick-location">Pick Location on Map</button>
                <div id="map-modal" class="modal" style="display: none">
                    <div class="modal-content">
                        <span class="close-modal">&times;</span>
                        <h3>Select a Location</h3>
                        <div id="map" style="width: 100%; height: 400px;"></div>
                    </div>
                </div>
            </div>

            <div class="form-row">
                <label for="preferred-date">Service Date</label>
                <input type="date" id="preferred-date" name="preferred_date" required>
                <div id="date-error" class="error-message" style="display: none">Service date must be in the future.</div>
            </div>

            <div class="form-row">
                <label for="arrival-time">Worker Arrival Time</label>
                <input type="time" id="arrival-time" name="arrival_time" required>
                <div id="time-error" class="error-message" style="display: none">Arrival time must be in the future.</div>
            </div>
        </div>

        <!-- Summary Section -->
        <div class="form-section">
            <div class="total-container">
                <span class="total-label">Total Cost:</span>
                <span class="total-amount">
                    Rs. <?php echo isset($_SESSION['total_cost']) ? number_format($_SESSION['total_cost'], 2) : '0.00'; ?>
                </span>
            </div>

            <div class="form-row acknowledgment-row">
                <div class="checkbox-container">
                    <input type="checkbox" id="data-acknowledgment" name="data_acknowledgment" required>
                    <label for="data-acknowledgment" class="checkbox-label">
                        I acknowledge that this information will be shared with the service provider when the booking is confirmed.
                    </label>
                    <div id="acknowledgment-error" class="error-message" style="display: none">You must acknowledge the data-sharing statement.</div>
                </div>
            </div>
        </div>

        <div class="button-group">
            <button type="button" class="btn-previous" id="back-btn">Previous</button>
            <button type="button" class="btn-next" id="nxt-btn">Proceed to Payment</button>
        </div>
    </form>
</div>
<?php include(ROOT_PATH . '/app/views/components/footer.view.php');?>
</body>
</html>
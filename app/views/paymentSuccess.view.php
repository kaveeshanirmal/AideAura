<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment Success</title>
    <link rel="stylesheet" href="<?=ROOT?>/public/assets/css/paymentSuccess.css">
</head>
<body>
<?php include(ROOT_PATH . '/app/views/components/navbar.view.php'); ?>

<div class="success-container">
    <!-- Success animation -->
    <div class="success-animation">
        <svg class="checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
            <circle class="checkmark__circle" cx="26" cy="26" r="25" fill="none" />
            <path class="checkmark__check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8" />
        </svg>
    </div>

    <h1>Payment Successful!</h1>
    <p class="success-message">Your booking has been confirmed and the worker has been notified.</p>

    <div class="booking-details">
        <h2>Booking Information</h2>
        <div class="booking-info-grid">
            <div class="info-item">
                <span class="info-label">Booking ID:</span>
                <span class="info-value"><?=htmlspecialchars($booking->bookingID) ?></span>
            </div>
            <div class="info-item">
                <span class="info-label">Service Type:</span>
                <span class="info-value"><?=htmlspecialchars($booking->serviceType) ?></span>
            </div>
            <div class="info-item">
                <span class="info-label">Date:</span>
                <span class="info-value"><?=htmlspecialchars($booking->bookingDate) ?></span>
            </div>
            <div class="info-item">
                <span class="info-label">Time:</span>
                <span class="info-value"><?=htmlspecialchars($booking->startTime) ?></span>
            </div>
            <div class="info-item">
                <span class="info-label">Location:</span>
                <span class="info-value"><?=htmlspecialchars($booking->location) ?></span>
            </div>
            <div class="info-item">
                <span class="info-label">Amount Paid:</span>
                <span class="info-value price">LKR <?php echo number_format($booking->totalCost, 2); ?></span>
            </div>
        </div>
    </div>

    <div class="worker-section">
        <h2>Your Service Provider</h2>
        <div class="worker-card">
            <img src="<?=ROOT?>/<?=htmlspecialchars($worker->profileImage)?>" alt="Worker Image">
            <div class="worker-details">
                <h3><?=htmlspecialchars($worker->firstName . ' ' . $worker->lastName) ?></h3>
                <p><i class="icon-star"></i> Rating: <strong><?=htmlspecialchars($worker->avg_rating) ?></strong> (<?=htmlspecialchars($worker->total_reviews ?? 0) ?> reviews)</p>

                <div class="contact-info">
                    <div class="contact-item">
                        <i class="icon-phone"></i>
                        <span><?=htmlspecialchars($worker->phone) ?></span>
                    </div>
                    <div class="contact-item">
                        <i class="icon-email"></i>
                        <span><?=htmlspecialchars($worker->email) ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="payment-info">
        <h2>Payment Information</h2>
        <div class="payment-details">
            <div class="payment-item">
                <span class="payment-label">Transaction ID:</span>
                <span class="payment-value"><?=htmlspecialchars($paymentInfo['transactionID']) ?></span>
            </div>
            <div class="payment-item">
                <span class="payment-label">Payment Method:</span>
                <span class="payment-value"><?=htmlspecialchars($paymentInfo['paymentMethod']) ?></span>
            </div>
            <div class="payment-item">
                <span class="payment-label">Payment Date:</span>
                <span class="payment-value"><?=date('Y-m-d H:i', strtotime($paymentInfo['paymentDate'])) ?></span>
            </div>
        </div>
    </div>

    <div class="next-steps">
        <h2>What's Next?</h2>
        <div class="steps-container">
            <div class="step">
                <div class="step-icon">📱</div>
                <div class="step-content">
                    <h3>Worker Contact</h3>
                    <p>Your service provider will contact you before the scheduled appointment time.</p>
                </div>
            </div>
            <div class="step">
                <div class="step-icon">🛠️</div>
                <div class="step-content">
                    <h3>Service Delivery</h3>
                    <p>The worker will arrive at the scheduled time to perform the service.</p>
                </div>
            </div>
            <div class="step">
                <div class="step-icon">⭐</div>
                <div class="step-content">
                    <h3>Review</h3>
                    <p>After the service is completed, please leave a review for the worker.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="action-buttons">
        <a href="<?=ROOT?>/public/customerProfile/bookingHistory" class="btn btn-secondary">View My Bookings</a>
        <a href="<?=ROOT?>/public/home" class="btn btn-primary">Back to Home</a>
    </div>
</div>

<?php include(ROOT_PATH . '/app/views/components/footer.view.php'); ?>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Show confetti animation
        showConfetti();
    });

    function showConfetti() {
        document.body.classList.add('celebration');

        setTimeout(() => {
            document.body.classList.remove('celebration');
        }, 3000);
    }

    function printReceipt() {
        window.print();
    }
</script>
</body>
</html>
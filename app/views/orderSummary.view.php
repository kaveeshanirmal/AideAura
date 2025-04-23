<?php
if (!isset($_SESSION['booking'])) {
    header('Location: ' . ROOT . '/public/home');
    exit();
}
$booking = [
    'bookingDate' => $_SESSION['booking']['bookingDate'],
    'serviceType' => $_SESSION['booking']['serviceType'],
    'startTime' => $_SESSION['booking']['startTime'],
    'location' => $_SESSION['booking']['location'],
    'totalCost' => $_SESSION['booking']['totalCost'],
    'status' => $_SESSION['booking']['status'],
    'createdAt' => $_SESSION['booking']['createdAt'],
];
$statusClass = 'status-' . strtolower($booking['status']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order Summary</title>
    <link rel="stylesheet" href="<?=ROOT?>/public/assets/css/orderSummary.css">
</head>
<body>
<?php include(ROOT_PATH . '/app/views/components/navbar.view.php'); ?>

<div class="summary-container">
    <h1>Order Summary</h1>

    <div class="worker-card">
        <img src="<?=ROOT?>/<?=htmlspecialchars($worker['profileImage'])?>" alt="Worker Image">
        <div class="worker-details">
            <h2><?=htmlspecialchars($worker['full_name']) ?></h2>
            <p>Category: <?=htmlspecialchars($booking['serviceType']) ?></p>
            <p>Rating: ⭐ <strong><?=htmlspecialchars($worker['rating']) ?></strong> (<?=htmlspecialchars($worker['reviews']) ?>)</p>
        </div>
    </div>

    <div class="booking-info">
        <p><strong>Booked Date:</strong> <?=htmlspecialchars($booking['bookingDate']) ?></p>
        <p><strong>Worker Arrival Time:</strong> <?=htmlspecialchars($booking['startTime']) ?></p>
        <p><strong>Location:</strong> <?=htmlspecialchars($booking['location']) ?></p>
        <p><strong>Status:</strong> <span class="status-badge <?php echo $statusClass; ?>"><?=htmlspecialchars($booking['status']) ?></span></p>
    </div>

    <div class="total">
        <!-- Payment Timer -->
        <div class="timer-container" id="payment-timer">
            <span class="timer-icon">⏱️</span>
            <span class="timer-text" id="countdown-text">Expires in 5:00</span>
        </div>
        <h3>Total Amount</h3>
        <p>LKR <?php echo number_format($booking['totalCost'], 2); ?></p>
        <div class="text">TAX & VAT Inclusive*</div>
    </div>

    <!-- Action Buttons - Separate from total section -->
    <div class="action-buttons">
        <button id="cancel-booking" class="btn btn-cancel">Cancel</button>
        <button id="pay-now" class="btn btn-pay">Pay Now</button>
    </div>
</div>

<?php include(ROOT_PATH . '/app/views/components/footer.view.php'); ?>

<script>
    const workerData = <?= json_encode($worker) ?>; // Worker data from PHP
    const bookingID = "<?= $_SESSION['booking']['bookingID'] ?>"; // Get booking ID from session
    const createdAt = "<?= $_SESSION['booking']['createdAt'] ?>"; // Get booking creation timestamp
    const totalCountdownSeconds = 3000; // 5 minutes in seconds for payment
    let countdown; // Will be calculated based on createdAt
    let countdownTimer;

    function calculateRemainingTime() {
        // Parse the createdAt timestamp to a Date object
        const createdAtDate = new Date(createdAt);
        const currentDate = new Date();

        // Calculate elapsed seconds since creation
        const elapsedSeconds = Math.floor((currentDate - createdAtDate) / 1000);

        // Calculate remaining seconds (total - elapsed)
        let remainingSeconds = totalCountdownSeconds - elapsedSeconds;

        // Ensure remaining time is not negative
        return Math.max(0, remainingSeconds);
    }

    function updateCountdown() {
        let minutes = Math.floor(countdown / 60);
        let seconds = countdown % 60;

        const countdownElement = document.getElementById("countdown-text");
        countdownElement.innerText = `Expires in ${minutes}:${seconds < 10 ? "0" : ""}${seconds}`;

        // Add visual indicator when time is running low (less than 1 minute)
        if (countdown < 60) {
            countdownElement.classList.add("timer-expiring");
        }

        if (countdown > 0) {
            countdown--;
            countdownTimer = setTimeout(updateCountdown, 1000);
        } else {
            // Redirect after countdown expires
            window.location.href = `${ROOT}/public/booking/orderTimeout`;
        }
    }

    // Start countdown when page loads
    window.onload = function() {
        // Calculate remaining time based on createdAt
        countdown = calculateRemainingTime();

        // If countdown is already expired, redirect immediately
        if (countdown <= 0) {
            window.location.href = `${ROOT}/public/booking/orderTimeout`;
            return;
        }

        updateCountdown();

        // Button event listeners
        document.getElementById('pay-now').addEventListener('click', function() {
            // Redirect to payment page
            window.location.href = `${ROOT}/public/payment/authorize`;
        });

        document.getElementById('cancel-booking').addEventListener('click', function() {
            if (confirm('Are you sure you want to cancel this booking?')) {
                // Send cancellation request
                fetch(`${ROOT}/public/booking/cancelBooking`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ bookingID: bookingID })
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            window.location.href = `${ROOT}/public/home`;
                        } else {
                            alert("Error cancelling booking: " + data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error cancelling booking:', error);
                    });
            }
        });
    };

    // Clean up timer if page is unloaded
    window.addEventListener('beforeunload', function() {
        clearTimeout(countdownTimer);
    });
</script>
</body>
</html>
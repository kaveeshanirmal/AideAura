<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Countdown Timer</title>
    <link rel="stylesheet" href="<?=ROOT?>/public/assets/css/countdownScreen.css">
    <script>
        // Configuration
        const ROOT = "<?=ROOT?>";
        const bookingID = "<?= $_SESSION['bookingID'] ?>"; // Make sure you have this variable available from PHP
        let currentStatus = 'pending'; // Initial status
        let countdown = 240; // 4 minutes in seconds
        let pollingInterval = 5000; // 5 seconds
        let pollingTimer;
        let countdownTimer;

        function updateCountdown() {
            let minutes = Math.floor(countdown / 60);
            let seconds = countdown % 60;
            document.getElementById("countdown-text").innerText = `Waiting for worker's confirmation ${minutes}:${seconds < 10 ? "0" : ""}${seconds}`;

            if (countdown > 0) {
                countdown--;
                countdownTimer = setTimeout(updateCountdown, 1000);
            } else {
                // Redirect after countdown if no status change detected
                if (currentStatus === 'pending') {
                    window.location.href = `${ROOT}/public/searchForWorker/noResponse`;
                }
            }
        }

        function checkBookingStatus() {
            fetch(`${ROOT}/public/booking/getBookingState`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ bookingID: bookingID })
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.status === 'success') {
                        const newStatus = data.state;

                        // If status changed from pending to accepted/rejected
                        if (currentStatus === 'pending' && (newStatus === 'accepted' || newStatus === 'rejected')) {
                            currentStatus = newStatus;

                            // Clear timers and redirect immediately
                            clearTimeout(countdownTimer);
                            clearInterval(pollingTimer);

                            window.location.href = newStatus === 'rejected'
                                ? `${ROOT}/public/searchForWorker/noResponse`
                                : `${ROOT}/public/searchForWorker/orderSummary`;
                        }
                        // Update current status if it changed (but not from pending)
                        else if (currentStatus !== newStatus) {
                            currentStatus = newStatus;
                        }
                    }
                })
                .catch(error => {
                    console.error('Error checking booking status:', error);
                });
        }

        // Start polling and countdown when page loads
        window.onload = function() {
            updateCountdown();
            pollingTimer = setInterval(checkBookingStatus, pollingInterval);
        };

        // Clean up timers if page is unloaded
        window.addEventListener('beforeunload', function() {
            clearTimeout(countdownTimer);
            clearInterval(pollingTimer);
        });
    </script>
</head>
<body>
<!-- Main Center Section -->
<h2 class="main-heading">Awaiting Job Confirmation</h2>
<div class="center-wrapper">

    <!-- Heading Above Flower Spinner -->


    <div class="flower-spinner">
        <div class="dot"></div>
        <div class="dot"></div>
        <div class="dot"></div>
        <div class="dot"></div>
        <div class="dot"></div>
        <div class="dot"></div>
        <div class="dot"></div>
        <div class="dot"></div>
    </div>

    <div class="center-logo"></div>
</div>

<p class="status-text">You will be notified when this is done : ) until then,</p>
<button class="cta-button">Take a ROADTRIP!</button>

<div class="action-container">
    <button class="cancel-btn">Cancel</button>
    <p id="countdown-text" class="countdown"></p>
</div>

</body>
</html>
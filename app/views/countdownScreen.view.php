<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Countdown Timer</title>
    <link rel="stylesheet" href="<?=ROOT?>/public/assets/css/countdownScreen.css">
    <script>
        // Configuration
        const ROOT = "<?=ROOT?>";
        const bookingID = "<?= $_SESSION['booking']['bookingID'] ?>"; // Get booking ID from session
        const createdAt = "<?= $_SESSION['booking']['createdAt'] ?>"; // Get booking creation timestamp
        const totalCountdownSeconds = 240; // 4 minutes in seconds
        let currentStatus = 'pending'; // Initial status
        let countdown; // Will be calculated based on createdAt
        let pollingInterval = 10000; // 10 seconds
        let pollingTimer;
        let countdownTimer;
        let cancelButton;

        //immediate poll for status
        checkBookingStatus();

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
            document.getElementById("countdown-text").innerText = `Waiting for worker's confirmation ${minutes}:${seconds < 10 ? "0" : ""}${seconds}`;

            if (countdown > 0) {
                countdown--;
                countdownTimer = setTimeout(updateCountdown, 1000);
            } else {
                // Redirect after countdown if no status change detected
                if (currentStatus === 'pending') {
                    window.location.href = `${ROOT}/public/booking/noResponse`;
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
                        if (currentStatus === 'pending' && (newStatus === 'accepted' || newStatus === 'cancelled')) {
                            currentStatus = newStatus;

                            // Clear timers and redirect immediately
                            clearTimeout(countdownTimer);
                            clearInterval(pollingTimer);

                            window.location.href = newStatus === 'cancelled'
                                ? `${ROOT}/public/booking/workerRejected`
                                : `${ROOT}/public/booking/orderSummary`;
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
            cancelButton = document.getElementById("cancel-btn");
            // Calculate remaining time based on createdAt
            countdown = calculateRemainingTime();

            // If countdown is already expired, redirect immediately
            if (countdown <= 0) {
                window.location.href = `${ROOT}/public/booking/acceptanceTimeout`;
                return;
            }

            cancelButton.addEventListener("click", function() {
                if (confirm("Are you sure you want to cancel the booking?")) {
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
                                window.location.href = `${ROOT}/public/searchForWorker/browseWorkers`;
                            } else {
                                alert("Error cancelling booking: " + data.message);
                            }
                        })
                        .catch(error => {
                            console.error('Error cancelling booking:', error);
                        });
                }
            });

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
<button class="cta-button" id="roadtrip-button" onclick="window.location.href='<?=ROOT?>/public/home'">Take a ROADTRIP!</button>

<div class="action-container">
    <button class="cancel-btn" id="cancel-btn">Cancel</button>
    <p id="countdown-text" class="countdown"></p>
</div>

</body>
</html>
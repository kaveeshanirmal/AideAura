<?php
define('ROOT', 'http://localhost/aideaura'); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Countdown Timer</title>
  <link rel="stylesheet" href="<?=ROOT?>/public/assets/css/CountdownScreen.css">
  <script>
    // Timer Script (4 minutes)
    let countdown = 240; // 4 minutes in seconds
    function updateCountdown() {
      let minutes = Math.floor(countdown / 60);
      let seconds = countdown % 60;
      document.getElementById("countdown-text").innerText = `Will be redirecting in ${minutes}:${seconds < 10 ? "0" : ""}${seconds}`;
      if (countdown > 0) {
        countdown--;
        setTimeout(updateCountdown, 1000);
      }
    }
    window.onload = updateCountdown; // Start countdown on page load
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

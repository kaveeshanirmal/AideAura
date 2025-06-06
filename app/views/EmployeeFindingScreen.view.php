<?php
//EmployeeFindingScreen
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Please Wait</title>
    <link rel="stylesheet" href="../../public/assets/css/EmployeeFindingScreen.css">
</head>
<body>
    
    <div class="container">
        <div class="main_heading">
            <h1>Your Help is Minutes Away!</h1>
        </div>
        <div class="water_wave"></div>
        <span class="water_text">Almost There</span>
        <button class="cancel_button">Cancel</button>
        </div>
    </div>
<script>
    // ajax call to the worker found screen after an delay
    setTimeout(function() {
        // Redirect to the worker found screen
        window.location.href = "<?php echo ROOT; ?>/public/searchForWorker/find";
    }, 5000); // 5 seconds delay
    // Cancel button functionality
    document.querySelector('.cancel_button').addEventListener('click', function() {
        // Redirect to the home page or any other page
        window.history.back();
    });
</script>
</body>
</html>
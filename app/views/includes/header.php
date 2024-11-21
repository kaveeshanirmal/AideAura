<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Service Selection</title>
    <link rel="stylesheet" href="/public/css/styles.css">

    <!-- Import Google Font (Inter) -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

    <!-- Import Font Awesome for arrow icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            margin: 0;
            padding: 0;
        }

        .header {
            position: fixed;
            width: 100%;
            top: 0;
            display: flex;
            flex-direction: column; /* Stack the progress bar and header vertically */
            align-items: flex-start; /* Align to the left */
            padding: 15px;
            background-color: white;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            z-index: 1000; /* Ensure the header stays on top */
        }

        /* Ensure the arrow and heading are in a row */
        .header-content {
            display: flex;
            align-items: center;
            width: 100%;
            justify-content: flex-start; /* Align to the left */
        }

        .header .back-arrow {
            font-size: 22px;
            margin-right: 7px; /* Add space between the arrow and the heading */
            cursor: pointer;
            color: #5A5A5A; /* Set arrow color to black */
            transition: color 0.3s;
        }

        .header .back-arrow:hover {
            color: #333; /* Slightly darker black on hover */
        }

        .header h2 {
            font-size: 18px;
            margin: 20px;
            color: #333;
        }

        /* Progress bar */
        .progress-bar-container {
            width: 100%;
            height: 5px;
            background-color: #e0e0e0;
            margin-top: 10px;
            position: absolute;
            bottom: 0;
            left: 0;
            border-radius: 3px;
        }

        .progress-bar {
            height: 100%;
            width: 0%; /* Starts at 0% and will be updated dynamically */
            background: linear-gradient(to right, #00bcd4, #4c8bf5); /* Gradient from sky blue to blue */
            border-radius: 3px;
            transition: width 0.3s, background 0.3s; /* Smooth transition for both width and color */
        }
  
    </style>
</head>
<body>
    <div class="header">
        <div class="header-content">
            <div class="back-arrow" id="back-arrow">
                <i class="fas fa-arrow-left"></i> <!-- Using Font Awesome arrow icon -->
            </div>
            <h2 id="header-title">Select a Service</h2>
        </div>
        <div class="progress-bar-container">
            <div class="progress-bar" id="progress-bar"></div> <!-- Progress bar element -->
        </div>
    </div>


    <script>
        let step = 1;
        const headerTitle = document.getElementById('header-title');
        const backArrow = document.getElementById('back-arrow');
        const progressBar = document.getElementById('progress-bar');

        // Update the header based on the step
        function updateHeader() {
            if (step === 1) {
                headerTitle.textContent = 'Select a Service';
                backArrow.style.visibility = 'hidden'; // Hide the arrow on the first step
                progressBar.style.width = '33%'; // Update the progress bar to 1/3
            } else if (step === 2) {
                headerTitle.textContent = 'Select the Working Hours & Starting Date';
                backArrow.style.visibility = 'visible'; // Show the arrow
                progressBar.style.width = '66%'; // Update the progress bar to 2/3
            } else if (step === 3) {
                headerTitle.textContent = 'Booking Summary';
                backArrow.style.visibility = 'visible';
                progressBar.style.width = '100%'; // Complete the progress bar
            }
        }

        // Go to the previous step when the arrow is clicked
        backArrow.addEventListener('click', function() {
            if (step > 1) {
                step--;
                updateHeader();
            }
        });

        // Initialize the header state
        updateHeader();
    </script>
</body>
</html>

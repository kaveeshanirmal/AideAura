<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cooking Service</title>
    <!-- Font Awesome for the checkmark icon -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        /* Main container styles */
        .service-container {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            align-items: center;
            width: 75%;
            height: 75%;
            margin: 0 auto;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #f5f5f5;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            border-radius: 10px;
            padding: 20px;
        }

        /* Flex container to align label and button on the same line */
        .gender-container {
            display: flex;
            align-items: center; /* Align items vertically in the center */
        }

        /* Label styling */
        .label-gender {
            font-size: 15px;
            margin-right: 20px; /* Space between the label and the button */
            color: #000;
        }

        /* Gender button styling */
        .gender-button {
            padding: 10px 20px;
            font-size: 15px;
            color: #fff;
            background-color: #4C9EBE; /* Default blue color */
            border: 0;
            border-radius: 7px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: box-shadow 0.3s ease, background-color 0.3s ease;
        }

        .gender-button i {
            margin-left: 10px; /* Space between the button text and icon */
        }

        /* Header and footer styling */
        .footer, .header {
            width: 100%;
            height: 85px;
            display: flex;
            box-sizing: border-box;
        }

        .footer {
            background-color: #f1f1f1;
            margin-top: 20px;
        }

        .header {
            background-color: #8cbed6;
            margin-bottom: 20px;
        }

        /* Middle section styling */
        .content {
            display: flex;
            justify-content: flex-start;
            align-items: center;
            flex-direction: row;
            width: 100%;
            padding-top: 100px;
        }
    </style>
</head>
<body>
    <div class="service-container">
        
        <!-- Include the header inside the div box -->
        <?php include('../includes/header.php'); ?>
        
        <div class="content">
            <div class="gender-container">
                <label class="label-gender" for="gender-button">Gender</label>
                <button class="gender-button">
                    Female
                    <!-- Add checkmark icon for selected option -->
                    <i class="fas fa-check-circle"></i>
                </button>
            </div>
        </div>

        <!-- Include the footer inside the div box -->
        <?php include('../includes/footer.php'); ?>
        
    </div>
</body>
</html>

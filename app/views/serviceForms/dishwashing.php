<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <title>Dishwashing Service</title>
    <style>
       
        .service-form-modal {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            height: 100%;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .service-form-header {
            padding-left: 10px;
            top: 0;
            left: 0;
            width: 100%;
            height: 75px;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: white;
            color: black;
            font-size: 18px;
            font-weight: bold;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            box-sizing: border-box;
        }

        .service-form-content {
            flex-grow: 1;
            overflow-y: auto;
            margin-left: auto;
            margin-right: auto;
            padding: 20px;
            box-sizing: border-box;
        }

        .question {
            display: block;
            margin-bottom: 4px;
            font-size: 18px;
            font-weight: bold;
            color: #1e1e1e;
        }

        .message {
            display: block;
            margin-bottom: 8px;
            font-size: 13px;
            font-weight: bold;
            color: #454646;
        }

        .options-container {
            display: flex;
            flex-wrap: wrap;
            gap: 1px;
            margin: 20px 0;
        }

        .option:hover {
            border-color: #4C9EBE;
        }

        .option input {
            position: absolute;
            opacity: 0;
            pointer-events: none;
        }

        .option {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 40px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 500;
            padding: 0 10px;
            white-space: nowrap;
            min-width: 100px;
        }

        .option span {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            height: 100%;
            padding: 0 10px;
            border: 1px solid #ccc;
            border-radius: 8px;
            background-color: white;
            color: #333;
            cursor: pointer;
            font-size: 15px;
            transition: background-color 0.3s, border-color 0.3s, color 0.3s;
            position: relative;
            text-align: center;
        }

        .option:hover span {
            background-color: #E6F7FC;
            border-color: #4C9EBE;
            color: #333;
        }

        .option input:checked+span {
            background-color: #E6F7FC;
            border-color: #4C9EBE;
            border-width: 3px;
            color: #333;
            font-weight: bold;
            padding-right: 40px;
            height: 40px;
        }

        .option input:checked+span::after {
            content: "\f058";
            font-family: "Font Awesome 5 Free";
            font-weight: 900;
            width: 20px;
            height: 20px;
            background-color: #4C9EBE;
            border-radius: 50%;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%) scale(1.2);
        }

        .done-btn{
            background-color: #4C9EBE;
            color: white;
            padding: 10px 30px; /* Adjusted padding to ensure it fits */
            border: none;
            border-radius: 7px;
            cursor: pointer;
            font-size: 14px;
            width: 100%; /* Ensure button width adjusts properly */
            margin-top: 20px;
            float: right; /* Aligns the button to the right */
        }

       /* .footerForms {
            width: 100%;
            height: 90px;
            padding: 16px;
            display: flex;
            box-sizing: border-box;
            bottom: 0;
            left: 0;
        }*/
    </style>
</head>

<body>
    <div class="service-form-modal">
        <!-- Modal Header -->
        <div class="service-form-header">
            Dishwashing
        </div>

        <!-- Form Container -->
        <div class="service-form-content">
            <form action="SubmitHome_style_food.php" method="post">

                <!-- people -->
                <label class="question">How many people are there at home?</label>
                <label class="message">Select 1 out of 6 options</label>

                <div class="options-container">
                    <label class="option">
                        <input type="radio" name="people" value="1" required>
                        <span>1 person</span>
                    </label>
                    <label class="option">
                        <input type="radio" name="people" value="2">
                        <span>2 people</span>
                    </label>
                    <label class="option">
                        <input type="radio" name="people" value="3">
                        <span>3 people</span>
                    </label>
                    <label class="option">
                        <input type="radio" name="people" value="4">
                        <span>4 people</span>
                    </label>
                    <label class="option">
                        <input type="radio" name="people" value="5-6">
                        <span>5-6 people</span>
                    </label>
                    <label class="option">
                        <input type="radio" name="people" value="7-8">
                        <span>7-8 people</span>
                    </label>
                </div>

               
                
            </form>
        </div>
        <button class="done-btn" onclick="closeModal()">Done</button>
    </div>
    
    <script src="../services/modal.js"></script>

</body>

</html>

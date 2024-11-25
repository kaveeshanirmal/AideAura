<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <title>Home-Style Food Service</title>
    <link rel="stylesheet" type="text/css" href="<?=ROOT?>/public/assets/css/forms/home_style_food.css">
    <script src="<?=ROOT?>/public/assets/js/modal.js" defer></script>
</head>

<body>
    <div class="service-form-modal">
        <!-- Modal Header -->
        <div class="service-form-header">
            Home-Style Food 
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

                <!-- meals -->
                <label class="question">How many meals per day?</label>
                <label class="message">Select 1 out of 3 options</label>

                <div class="options-container">
                    <label class="option">
                        <input type="radio" name="meals" value="1" required>
                        <span>Breakfast & Lunch</span>
                    </label>
                    <label class="option">
                        <input type="radio" name="meals" value="2">
                        <span>Dinner</span>
                    </label>
                    <label class="option">
                        <input type="radio" name="meals" value="3">
                        <span>All 3 meals(breakfast + Lunch + Dinner)</span>
                    </label>
                </div>

                <!-- veg/non veg - preference -->
                <label class="question">Veg / Non-Veg?</label>
                <label class="message">Select 1 out of 2 options</label>

                <div class="options-container">
                    <label class="option">
                        <input type="radio" name="preference" value="1" required>
                        <span>Veg food only</span>
                    </label>
                    <label class="option">
                        <input type="radio" name="preference" value="2">
                        <span>Veg + Non-Veg</span>
                    </label>

                </div>

                <!-- dogs -->
                <label class="question">Do you have dog(s) ?</label>
                <label class="message">Select 1 out of 2 options</label>

                <div class="options-container">
                    <label class="option">
                        <input type="radio" name="dogs" value="1" required>
                        <span>Yes</span>
                    </label>
                    <label class="option">
                        <input type="radio" name="dogs" value="2" >
                        <span>No</span>
                    </label>

                </div>
                
            </form>
        </div>
        
    </div>
    <?php include(ROOT_PATH . '/app/views/forms/footerForms.view.php'); ?>
    <button class="done-btn" onclick="closeModal()">Done</button>

</body>

</html>
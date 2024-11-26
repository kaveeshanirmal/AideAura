<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <title>Dishwashing Service</title>
    <link rel="stylesheet" type="text/css" href="<?=ROOT?>/public/assets/css/forms/serviceForms.css">
    <script src="<?=ROOT?>/public/assets/js/modal.js" defer></script>
</head>

<body>
    <?php
    // Debug output at top of file
    echo "<!-- Debug Output Start -->\n";
    echo "<!-- Pricing Data in View: " . print_r($pricingData ?? 'NOT SET', true) . " -->\n";
    echo "<!-- Debug Output End -->\n";
    ?>

    <div class="service-form-modal">
        <!-- Modal Header -->
        <div class="service-form-header">
            Dishwashing
        </div>

        <!-- Form Container -->
        <div class="service-form-content">
            <form id="dishwashingForm" class="service-form">
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
        
        <?php $isModal = true; ?>
        
        <!-- Make pricing data available globally -->
        <script>
            // Initialize global values if they don't exist
            if (!window.globalValues) {
                window.globalValues = {
                    totalPrice: <?php echo $services['home-style-food']['basePrice']; ?>,
                    totalHours: "<?php echo $services['home-style-food']['baseHours']; ?>:00"
                };
            }
            
            window.previousSelections = window.previousSelections || {
                homeStyleForm: {},
                dishwashingForm: {}
            };
        </script>
        
        <?php include(ROOT_PATH . '/app/views/forms/footer.view.php'); ?>
    </div>
</body>

</html>

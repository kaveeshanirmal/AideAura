<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Requirement Gathering Form</title>
    <link rel="stylesheet" href="<?php echo ROOT; ?>/public/assets/css/serviceForms/cook.css">
</head>
<body>
<?php include(ROOT_PATH . '/app/views/components/navbar.view.php');?>
<div class="container">
    <h2>Service Preferences</h2>
    <div class="progress-bar">
        <div class="progress"></div>
    </div>

    <form id="requirementForm">
        <label for="gender">Worker's Gender preference</label>
        <div class="radio-group">
            <input type="radio" id="female-btn" name="gender" value="female" required checked>
            <label for="female-btn">Female</label>
            <input type="radio" id="male-btn" name="gender" value="male" required>
            <label for="male-btn">Male</label>
        </div>

        <label for="property-size">Property Size</label>
        <div class="radio-group">
            <input type="radio" id="size-small" name="property-size" value="small" required>
            <label for="size-small">Small (1-2 rooms)</label>
            <input type="radio" id="size-medium" name="property-size" value="medium">
            <label for="size-medium">Medium (3-4 rooms)</label>
            <input type="radio" id="size-large" name="property-size" value="large">
            <label for="size-large">Large (5+ rooms)</label>
        </div>
        <div class="error-message" id="size-error"></div>

        <label>Cleaning Services</label>
        <div class="checkbox-group">
            <input type="checkbox" id="floor-cleaning" name="services[]" value="floor-cleaning" class="service-checkbox">
            <label for="floor-cleaning">Floor Cleaning</label>
            <input type="checkbox" id="bathroom-cleaning" name="services[]" value="bathroom-cleaning" class="service-checkbox">
            <label for="bathroom-cleaning">Bathroom Cleaning</label>
            <input type="checkbox" id="dusting" name="services[]" value="dusting" class="service-checkbox">
            <label for="dusting">Dusting</label>
        </div>
        <div class="error-message" id="services-error"></div>

        <label>Cleaning Intensity</label>
        <div class="radio-group">
            <input type="radio" id="intensity-light" name="intensity" value="light" required>
            <label for="intensity-light">Light Cleaning</label>
            <input type="radio" id="intensity-standard" name="intensity" value="standard">
            <label for="intensity-standard">Standard Cleaning</label>
            <input type="radio" id="intensity-deep" name="intensity" value="deep">
            <label for="intensity-deep">Deep Cleaning</label>
        </div>
        <div class="error-message" id="intensity-error"></div>

        <label>Add-ons</label>
        <div class="checkbox-group">
            <input type="checkbox" id="window-cleaning" name="addons[]" value="window-cleaning">
            <label for="window-cleaning">Window Cleaning</label>
            <input type="checkbox" id="laundry" name="addons[]" value="laundry">
            <label for="laundry">Laundry</label>
            <input type="checkbox" id="ironing" name="addons[]" value="ironing">
            <label for="ironing">Ironing</label>
            <input type="checkbox" id="organizing" name="addons[]" value="organizing">
            <label for="organizing">Organizing</label>
        </div>

        <div class="total-container">
            <span class="total-label">Total Cost:</span>
            <span class="total-amount">
                Rs. <?php echo isset($_SESSION['booking_info']['total_cost']) ? number_format($_SESSION['booking_info']['total_cost'], 2) : '0.00'; ?>
            </span>
        </div>

        <div class="button-group">
            <button type="button" class="btn-previous" id="back-btn">Previous</button>
            <button type="button" class="btn-next" id="nxt-btn">Next</button>
        </div>
    </form>
</div>
<?php include(ROOT_PATH . '/app/views/components/footer.view.php');?>

<script>

    const backButton = document.getElementById("back-btn");
    backButton.addEventListener("click", (event) => {
        event.preventDefault();
        window.history.back(); // Navigate to the previous page
    });

    const nxtButton = document.getElementById("nxt-btn");
    nxtButton.addEventListener("click", (event) => {
        event.preventDefault();

        // Validate the form
        if (validateForm()) {
            window.location.href = `${ROOT}/public/selectService/bookingInfo`;
        }
    });

    function validateForm() {
        let isValid = true;

        // Clear previous error messages
        const errorMessages = document.querySelectorAll('.error-message');
        errorMessages.forEach(element => {
            element.textContent = '';
        });

        // Validate property size selection
        const sizeSelected = document.querySelector('input[name="property-size"]:checked');
        if (!sizeSelected) {
            document.getElementById('size-error').textContent = 'Please select property size';
            isValid = false;
        }

        // Validate services selection (at least one must be checked)
        const serviceCheckboxes = document.querySelectorAll('.service-checkbox:checked');
        if (serviceCheckboxes.length === 0) {
            document.getElementById('services-error').textContent = 'Please select at least one cleaning service';
            isValid = false;
        }

        // Validate intensity selection
        const intensitySelected = document.querySelector('input[name="intensity"]:checked');
        if (!intensitySelected) {
            document.getElementById('intensity-error').textContent = 'Please select service cleaning intensity';
            isValid = false;
        }

        return isValid;
    }

    document.addEventListener("DOMContentLoaded", function () {
        const form = document.querySelector("form");
        const totalAmount = document.querySelector(".total-amount");

        form.addEventListener("change", function () {
            const formData = new FormData(form);

            fetch(`${ROOT}/public/selectService/maidPricing`, {
                method: "POST",
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.total) {
                        totalAmount.textContent = `Rs. ${data.total}.00`;
                    }
                })
                .catch(error => console.error("Error:", error));
        });
    });
</script>
</body>
</html>
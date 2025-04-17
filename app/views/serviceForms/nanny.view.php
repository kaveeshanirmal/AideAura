<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Nanny Service Request</title>
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
        <label for="gender">Nanny's Gender Preference</label>
        <div class="radio-group">
            <input type="radio" id="female-btn" name="gender" value="female" required checked>
            <label for="female-btn">Female</label>
            <input type="radio" id="male-btn" name="gender" value="male">
            <label for="male-btn">Male</label>
        </div>

        <label for="children-count">Number of Children</label>
        <div class="radio-group">
            <input type="radio" id="child-1" name="children-count" value="1" required>
            <label for="child-1">1</label>
            <input type="radio" id="child-2" name="children-count" value="2">
            <label for="child-2">2</label>
            <input type="radio" id="child-3" name="children-count" value="3">
            <label for="child-3">3</label>
            <input type="radio" id="child-4plus" name="children-count" value="4+">
            <label for="child-4plus">4+</label>
        </div>
        <div class="error-message" id="children-error"></div>

        <label for="children-ages">Children's Ages</label>
        <div class="checkbox-group">
            <input type="checkbox" id="age-infant" name="children-ages[]" value="infant" class="age-checkbox">
            <label for="age-infant">Infant (0-1)</label>
            <input type="checkbox" id="age-toddler" name="children-ages[]" value="toddler" class="age-checkbox">
            <label for="age-toddler">Toddler (1-3)</label>
            <input type="checkbox" id="age-preschool" name="children-ages[]" value="preschool" class="age-checkbox">
            <label for="age-preschool">Preschool (3-5)</label>
            <input type="checkbox" id="age-school" name="children-ages[]" value="school" class="age-checkbox">
            <label for="age-school">School Age (5+)</label>
        </div>
        <div class="error-message" id="ages-error"></div>

        <label for="service-duration">Service Duration</label>
        <div class="radio-group">
            <input type="radio" id="duration-4" name="service-duration" value="4" required>
            <label for="duration-4">4 hours</label>
            <input type="radio" id="duration-8" name="service-duration" value="8">
            <label for="duration-8">8 hours</label>
            <input type="radio" id="duration-12" name="service-duration" value="12">
            <label for="duration-12">12 hours</label>
            <input type="radio" id="duration-overnight" name="service-duration" value="overnight">
            <label for="duration-overnight">Overnight</label>
        </div>

        <label>Childcare Requirements</label>
        <div class="radio-group">
            <input type="radio" id="standard-care" name="care-level" value="standard" required checked>
            <label for="standard-care">Standard childcare</label>
            <input type="radio" id="specialized-care" name="care-level" value="specialized">
            <label for="specialized-care">Specialized care (for children with unique needs)</label>
        </div>
        <div class="error-message" id="care-level-error"></div>

        <label>Add-ons</label>
        <div class="checkbox-group">
            <input type="checkbox" id="homework-help" name="addons[]" value="homework-help">
            <label for="homework-help">Homework Help</label>
            <input type="checkbox" id="cooking-meals" name="addons[]" value="cooking-meals">
            <label for="cooking-meals">Prepare Meals</label>
            <input type="checkbox" id="transport" name="addons[]" value="transport">
            <label for="transport">Transport</label>
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
    const bookingInfo = <?php echo json_encode($_SESSION['booking_info']); ?>;
    console.log(bookingInfo);
    const ROOT = "<?php echo ROOT; ?>";

    const backButton = document.getElementById("back-btn");
    backButton.addEventListener("click", (event) => {
        event.preventDefault();
        window.history.back();
    });

    const nxtButton = document.getElementById("nxt-btn");
    nxtButton.addEventListener("click", (event) => {
        event.preventDefault();
        if (validateForm()) {
            window.location.href = `${ROOT}/public/selectService/bookingInfo`;
        }
    });

    function validateForm() {
        let isValid = true;
        const errorMessages = document.querySelectorAll('.error-message');
        errorMessages.forEach(element => element.textContent = '');

        // Validate children count
        if (!document.querySelector('input[name="children-count"]:checked')) {
            document.getElementById('children-error').textContent = 'Please select number of children';
            isValid = false;
        }

        // Validate children ages
        if (document.querySelectorAll('.age-checkbox:checked').length === 0) {
            document.getElementById('ages-error').textContent = 'Please select age range(s)';
            isValid = false;
        }

        // Validate date
        if (!document.getElementById('care-date').value) {
            document.getElementById('date-error').textContent = 'Please select a date';
            isValid = false;
        }

        return isValid;
    }

    document.addEventListener("DOMContentLoaded", function () {
        const form = document.querySelector("form");
        const totalAmount = document.querySelector(".total-amount");

        // Set minimum date to today
        const today = new Date().toISOString().split('T')[0];
        document.getElementById("care-date").min = today;

        form.addEventListener("change", function () {
            const formData = new FormData(form);

            fetch(`${ROOT}/public/selectService/nannyService`, {
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
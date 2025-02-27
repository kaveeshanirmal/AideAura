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
    <h2>Service Selection</h2>
    <div class="progress-bar">
        <div class="progress"></div>
    </div>

    <form>
        <label for="people">For how many people?</label>
        <div class="radio-group people-count">
            <input type="radio" id="people-1-2" name="people" value="1-2" required>
            <label for="people-1-2">1-2</label>

            <input type="radio" id="people-3-5" name="people" value="3-5">
            <label for="people-3-5">3-5</label>

            <input type="radio" id="people-5-7" name="people" value="5-7">
            <label for="people-5-7">5-7</label>

            <input type="radio" id="people-8-10" name="people" value="8-10">
            <label for="people-8-10">8-10</label>
        </div>

        <label>Meal Selection</label>
        <div class="checkbox-group">
            <input type="checkbox" id="breakfast" name="meals[]" value="breakfast">
            <label for="breakfast">Breakfast</label>
            <input type="checkbox" id="lunch" name="meals[]" value="lunch">
            <label for="lunch">Lunch</label>
            <input type="checkbox" id="dinner" name="meals[]" value="dinner">
            <label for="dinner">Dinner</label>
        </div>

        <label>Dietary Preference</label>
        <div class="radio-group">
            <input type="radio" id="veg" name="diet" value="veg" required>
            <label for="veg">Veg</label>
            <input type="radio" id="nonveg" name="diet" value="nonveg">
            <label for="nonveg">Non-Veg</label>
        </div>

        <label>Add-ons</label>
        <div class="checkbox-group">
            <input type="checkbox" id="dishwashing" name="addons[]" value="dishwashing">
            <label for="dishwashing">Dishwashing</label>
            <input type="checkbox" id="desserts" name="addons[]" value="desserts">
            <label for="desserts">Desserts</label>
            <input type="checkbox" id="shopping" name="addons[]" value="shopping">
            <label for="shopping">Shopping for Ingredients</label>
        </div>

        <div class="total-container">
            <span class="total-label">Total Cost:</span>
            <span class="total-amount">Rs. 00</span>
        </div>

        <div class="button-group">
            <button type="button" class="btn-previous" id="back-btn">Previous</button>
            <button type="button" class="btn-next" id="nxt-btn">Next</button>
        </div>
    </form>
</div>
<?php include(ROOT_PATH . '/app/views/components/footer.view.php');?>

<script>
    const ROOT = "<?php echo ROOT; ?>";

    const backButton = document.getElementById("back-btn");
    backButton.addEventListener("click", (event) => {
        event.preventDefault();
        window.history.back(); // Navigate to the previous page
    });

    document.addEventListener("DOMContentLoaded", function () {
        const form = document.querySelector("form");
        const totalAmount = document.querySelector(".total-amount");

        form.addEventListener("change", function () {
            const formData = new FormData(form);

            fetch(`${ROOT}/public/selectService/cookingService`, {
                method: "POST",
                body: formData,
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

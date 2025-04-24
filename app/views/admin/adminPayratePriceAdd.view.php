<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Add Employee</title>
    <link rel="stylesheet" href="<?=ROOT?>/public/assets/css/adminEmployeeAdd.css">
</head>
<body>
    <div id="notification" class="notification hidden"></div>
    <div class="dashboard-container">
        <!-- Include your existing navbar component -->
        <?php include(ROOT_PATH . '/app/views/components/employeeNavbar.view.php'); ?>

        <div class="main-content">
            <div class="content-wrapper">
                <div class="employee-form-container">
                    <form action="<?=ROOT?>/public/Admin/storePriceDetails" method="POST" class="employee-form" id="priceDetailForm">
                        <div id="formMessage" class="form-message"></div> <!-- Message container -->

                        <div class="form-group">
                            <label for="detailName">Detail Name : </label>
                            <input type="text" id="detailName" name="detailName" placeholder="Kamal" class="form-input" autocomplete="given-name" required>
                        </div>

                        <div class="form-group">
                            <label for="price">Price : </label>
                            <input type="text" id="price" name="price" placeholder="0.00" class="form-input" autocomplete="off" required pattern="^\d+(\.\d{1,2})?$">
                        </div>

                        <div class="form-group">
                            <label for="description">Description : </label>
                            <textarea id="description" name="description" placeholder="Enter description here..." class="form-input" rows="4" required></textarea>
                        </div>

                        <div class="form-group">
                            <label for="categoryID">Category ID : </label>
                            <input type="number" id="categoryID" name="categoryID" placeholder="123" class="form-input" autocomplete="username" required pattern="^\d+$">
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="add-btn">Add Price Detail</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        const form = document.getElementById('priceDetailForm');
        const notification = document.getElementById('notification');

        const showNotification = (message, type) => {
            notification.textContent = message;
            notification.className = `notification ${type} show`;

            setTimeout(() => {
                notification.className = 'notification hidden';
            }, 3000);
        };

        const validateForm = () => {
            const detailName = document.getElementById('detailName').value.trim();
            const price = document.getElementById('price').value.trim();
            const description = document.getElementById('description').value.trim();
            const categoryID = document.getElementById('categoryID').value.trim();

            if (!/^[a-zA-Z0-9\s]+$/.test(detailName)) {
            showNotification('Detail Name must contain only letters, digits, and spaces.', 'error');
            return false;
            }

            if (!/^\d+(\.\d{1,2})?$/.test(price)) {
            showNotification('Price must be a decimal number with up to two decimal points.', 'error');
            return false;
            }

            if (description.length === 0) {
            showNotification('Description cannot be empty.', 'error');
            return false;
            }

            if (!/^\d+$/.test(categoryID)) {
            showNotification('Category ID must be an integer.', 'error');
            return false;
            }

            return true;
        };

        form.addEventListener('submit', async (e) => {
            e.preventDefault();

            if (!validateForm()) return;

    const formData = new FormData(form);
    try {
        const response = await fetch(form.action, {
            method: 'POST',
            body: formData,
        });

        if (!response.ok) {
            throw new Error('Network response was not ok');
        }

        const result = await response.json(); // Parse the response as JSON

        if (result.status === 'success') {
            showNotification(result.message, 'success');
            setTimeout(() => {
                window.location.href = '<?=ROOT?>/public/Admin/priceCategoryDetails';
            }, 2000);
        } else {
            showNotification(result.message, 'error');
        }
    } catch (error) {
        showNotification(`Error: ${error.message}`, 'error');
        console.error('Fetch error:', error); // Log the error for debugging
    }
});
    </script>
</body>
</html>
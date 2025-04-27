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
                    <form action="<?=ROOT?>/public/AdminEmployeeAdd/store" method="POST" class="employee-form" id="employeeForm">
                        <div id="formMessage" class="form-message"></div> <!-- Message container -->

                        <div class="form-group">
                            <label for="firstName">First Name:</label>
                            <input type="text" id="firstName" name="firstName" placeholder="Kamal" class="form-input" autocomplete="given-name" required>
                        </div>

                        <div class="form-group">
                            <label for="lastName">Last Name:</label>
                            <input type="text" id="lastName" name="lastName" placeholder="Gunarathne" class="form-input" autocomplete="family-name" required>
                        </div>

                        <div class="form-group">
                            <label for="username">Username:</label>
                            <input type="text" id="username" name="username" placeholder="kamal.g" class="form-input" autocomplete="username" required>
                        </div>

                        <div class="form-group">
                            <label for="email">Email:</label>
                            <input type="email" id="email" name="email" placeholder="kmgnth123@gamil.com" class="form-input" autocomplete="email" required>
                        </div>

                        <div class="form-group">
                            <label for="phone">Contact:</label>
                            <input type="tel" id="phone" name="phone" placeholder="078 956 4738" class="form-input" autocomplete="tel" required>
                        </div>

                        <div class="form-group">
                            <label for="role">Role:</label>
                            <select id="role" name="role" class="form-select" autocomplete="off" required>
                                <option value="financeManager">financeManager</option>
                                <option value="hrManager">hrManager</option>
                                <option value="opManager">opManager</option>
                                <option value="admin">admin</option>                            </select>
                        </div>

                        <div class="form-group">
                            <label for="password">Password:</label>
                            <div class="password-container">
                                <input type="password" id="password" name="password" placeholder="AdminKamal738" class="form-input" autocomplete="new-password" required>
                                <span class="toggle-password" id="togglePassword">&#128065;</span>
                            </div>
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="add-btn">Add</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        const form = document.getElementById('employeeForm');
        const notification = document.getElementById('notification');
        const togglePassword = document.getElementById('togglePassword');
        const passwordField = document.getElementById('password');

        const showNotification = (message, type) => {
            notification.textContent = message;
            notification.className = `notification ${type} show`;

            setTimeout(() => {
                notification.className = 'notification hidden';
            }, 3000);
        };

        const validateForm = () => {
            const firstName = document.getElementById('firstName').value.trim();
            const lastName = document.getElementById('lastName').value.trim();
            const username = document.getElementById('username').value.trim();
            // const email = document.getElementById('email').value.trim();
            const phone = document.getElementById('phone').value.trim();
            const password = document.getElementById('password').value.trim();
            const role = document.getElementById('role').value.trim();

            if (!/^[a-zA-Z\s]+$/.test(firstName)) {
                showNotification('First name must contain only letters.', 'error');
                return false;
            }

            if (!/^[a-zA-Z\s]+$/.test(lastName)) {
                showNotification('Last name must contain only letters.', 'error');
                return false;
            }

            if (!username) {
                showNotification('Username is required.', 'error');
                return false;
            }

            // if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
            //     showNotification('Please enter a valid email address including @', 'error');
            //     return false;
            // }

            if (!/^[0-9]{10}$/.test(phone)) {
                showNotification('Contact number must be exactly 10 digits.', 'error');
                return false;
            }

            if (!/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!#%*?&]{8,}$/.test(password)) {
                showNotification(
                    'Password must be at least 8 characters long and include an uppercase letter, lowercase letter, number, and special character.',
                    'error'
                );
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
                window.location.href = '<?=ROOT?>/public/AdminEmployees';
            }, 3000);
        } else {
            showNotification(result.message, 'error');
        }
    } catch (error) {
        showNotification(`Error: ${error.message}`, 'error');
        console.error('Fetch error:', error); // Log the error for debugging
    }
});

        togglePassword.addEventListener('click', () => {
            const type = passwordField.type === 'password' ? 'text' : 'password';
            passwordField.type = type;
            togglePassword.textContent = type === 'password' ? '\uD83D\uDC41' : '\uD83D\uDC41\u200D\uD83D\uDDE8';
        });
    </script>
</body>
</html>
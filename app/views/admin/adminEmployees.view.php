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
        <?php include(ROOT_PATH . '/app/views/components/admin_navbar.view.php'); ?>
        
        <div class="main-content">
            <div class="content-wrapper">
                <div class="employee-form-container">
                    <form action="<?=ROOT?>/public/AdminEmployeeAdd/store" method="POST" class="employee-form" id="employeeForm">
                        <div id="formMessage" class="form-message"></div> <!-- Message container -->

                        <div class="form-group">
                            <label for="name">Name :</label>
                            <input type="text" id="name" name="name" placeholder="Mr. Kamal Gunarathne" class="form-input" required>
                        </div>

                        <div class="form-group">
                            <label for="email">Email :</label>
                            <input type="email" id="email" name="email" placeholder="kmgnth123@gamil.com" class="form-input" required>
                        </div>

                        <div class="form-group">
                            <label for="contact">Contact :</label>
                            <input type="tel" id="contact" name="contact" placeholder="078 956 4738" class="form-input" required>
                        </div>

                        <div class="form-group">
                            <label for="role">Role :</label>
                            <select id="role" name="role" class="form-select" required>
                                <option value="Accountant">Accountant</option>
                                <option value="HR Manager">HR Manager</option>
                                <option value="Operational Manager">Operational Manager</option>
                            </select>
                        </div>

                        <div class="form-group">
                        <label for="password">Password :</label>
                        <div class="password-container">
                            <input 
                                type="password" 
                                id="password" 
                                name="password" 
                                placeholder="AdminKamal738" 
                                class="form-input" 
                                required>
                            <span class="toggle-password" id="togglePassword">&#128065;</span>
                        </div>
                        </div>

                        <div class="form-group">
                            <label for="date">Date :</label>
                            <input type="date" id="date" name="date" class="form-input" required>
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="add-btn">Add Employee</button>
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
        // Set message and type
        notification.textContent = message;
        notification.className = `notification ${type} show`;

        // Hide the notification after 3 seconds
        setTimeout(() => {
            notification.className = 'notification hidden';
        }, 3000);
    };

    // Form validation function
    const validateForm = () => {
        const name = document.getElementById('name').value.trim();
        const email = document.getElementById('email').value.trim();
        const contact = document.getElementById('contact').value.trim();
        const password = document.getElementById('password').value.trim();
        const date = document.getElementById('date').value;

        // Name validation
        const nameRegex = /^[a-zA-Z\s]+$/;
        if (!name || !nameRegex.test(name)) {
            showNotification('Name must contain only letters and spaces.', 'error');
            return false;
        }

        // Email validation
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!email || !emailRegex.test(email)) {
            showNotification('Please enter a valid email address.', 'error');
            return false;
        }

        // Contact validation
        const contactRegex = /^[0-9]{10}$/;
        if (!contact || !contactRegex.test(contact)) {
            showNotification('Contact number must be exactly 10 digits.', 'error');
            return false;
        }

        // Password validation
        const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
        if (!password || !passwordRegex.test(password)) {
            showNotification(
                'Password must be at least 8 characters long and include an uppercase letter, lowercase letter, number, and special character.',
                'error'
            );
            return false;
        }

        // Date validation
        const currentDate = new Date().toISOString().split('T')[0];
        if (!date || date > currentDate) {
            showNotification('Date cannot be in the future.', 'error');
            return false;
        }

        return true; // All validations passed
    };

    form.addEventListener('submit', async (e) => {
        e.preventDefault();

        if (!validateForm()) {
            return; // Stop form submission if validation fails
        }

        const formData = new FormData(form);
        try {
            const response = await fetch(form.action, {
                method: 'POST',
                body: formData,
            });

            const result = await response.json();

            if (result.status === 'success') {
                showNotification(result.message, 'success'); // Success notification
                setTimeout(() => {
                    window.location.href = '<?=ROOT?>/public/AdminEmployees';
                }, 2000);
            } else {
                showNotification(result.message, 'error'); // Error notification
            }
        } catch (error) {
            showNotification('An error occurred. Please try again.', 'error');
            console.error('Error:', error);
        }
    });


    togglePassword.addEventListener('click', () => {
        // Toggle the password field type
        const type = passwordField.type === 'password' ? 'text' : 'password';
        passwordField.type = type;

        // Change the icon based on the visibility
        togglePassword.textContent = type === 'password' ? '\uD83D\uDC41' : '\uD83D\uDC41\u200D\uD83D\uDDE8'; // Plain vs. strikethrough eye
    });
</script>



</body>
</html>
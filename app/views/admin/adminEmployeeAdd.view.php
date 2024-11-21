<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Add Employee</title>
    <link rel="stylesheet" href="<?=ROOT?>/public/assets/css/adminEmployeeAdd.css">
</head>
<body>
    <div class="dashboard-container">
        <!-- Include your existing sidebar component -->
        <?php include(ROOT_PATH . '/app/views/components/admin_sidebar.view.php'); ?>

        <div class="main-content">
            <!-- Include your existing navbar component -->
            <?php include(ROOT_PATH . '/app/views/components/admin_navbar.view.php'); ?>

            <div class="content-wrapper">
                <div class="employee-form-container">
                    <form action="process_employee.php" method="POST" class="employee-form">
                        <div class="form-group">
                            <label for="name">Name :</label>
                            <input type="text" id="name" name="name" placeholder="Mr.Kamal Gunarathne" class="form-input" required>
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
                            <input type="password" id="password" name="password" placeholder="AdminKamal738" class="form-input" required>
                        </div>

                        <div class="form-group">
                            <label for="date">Date :</label>
                            <input type="date" id="date" name="date" class="form-input" required>
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="add-btn">
                            <a href="<?=ROOT?>/public/adminEmployees">Add Employee</a>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Include your JavaScript files -->
    <script src="assets/js/dashboard.js"></script>
</body>
</html>
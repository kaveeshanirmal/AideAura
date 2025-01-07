<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Roles</title>
    <link rel="stylesheet" href="<?=ROOT?>/public/assets/css/adminRoles.css">
    <!-- Include any other CSS files you need -->
</head>
<body>
    <div class="dashboard-container">
        <!-- Include your existing sidebar component -->
        <?php include(ROOT_PATH . '/app/views/components/employeeNavbar.view.php'); ?>
        <div class="main-content">
            <!-- Include your existing navbar component -->
            <div class="content-wrapper">
                <div class="roles-section">
                    <div class="roles-header">
                        <button class="add-role-btn">
                        <a href="workerRoles1">Add Role</a>
                        </button>
                    </div>

                    <div class="roles-list">
                        <!-- Cleaner Role Card -->
                        <div class="role-card">
                            <div class="role-header">
                                <h2>Cleaner</h2>
                                <div class="role-actions">
                                    <button class="refresh-btn"><i class="refresh-icon"></i></button>
                                    <button class="delete-btn"><i class="delete-icon"></i></button>
                                </div>
                            </div>
                            <div class="role-description">
                                <p>This is the special role of cleaning service Employees including indoor cleaning, outdoor cleaning and bathroom/kitchen cleaning.</p>
                            </div>
                        </div>
                        <div class="role-card">
                            <div class="role-header">
                                <h2>Cook</h2>
                                <div class="role-actions">
                                    <button class="refresh-btn"><i class="refresh-icon"></i></button>
                                    <button class="delete-btn"><i class="delete-icon"></i></button>
                                </div>
                            </div>
                            <div class="role-description">
                                <p>This is the special role of cooking. Cooking can be for childern, youngers or adults. Number of meals they want to cook can be changed. Normally three meals per day with two tea times.</p>
                            </div>
                        </div>
                        <div class="role-card">
                            <div class="role-header">
                                <h2>24h-Cook</h2>
                                <div class="role-actions">
                                    <button class="refresh-btn"><i class="refresh-icon"></i></button>
                                    <button class="delete-btn"><i class="delete-icon"></i></button>
                                </div>
                            </div>
                            <div class="role-description">
                                <p>This is the special role of cooking service Employees for 24h time. Specially it can be function of customer or special day. Cook want to cook at most 24h only.</p>
                            </div>
                        </div>

                        <!-- Nanny Role Card -->
                        <div class="role-card">
                            <div class="role-header">
                                <h2>Nanny</h2>
                                <div class="role-actions">
                                    <button class="refresh-btn"><i class="refresh-icon"></i></button>
                                    <button class="delete-btn"><i class="delete-icon"></i></button>
                                </div>
                            </div>
                            <div class="role-description">
                                <p>This is the special role of Nanny including nannies for kids, old people and sick people.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Include your JavaScript files -->
    <script src="assets/js/dashboard.js"></script>
</body>
</html>
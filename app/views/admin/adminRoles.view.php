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
                         <?php foreach($roles as $role): ?>
                        <div class="role-card">
                            <div class="role-header">
                                <h2><?= htmlspecialchars($role->name) ?></h2>
                                <span class="close-btn" onclick="closeUpdateModal()">&times;</span>
                                <div class="role-actions">
                                    <button class="refresh-btn"><i class="refresh-icon"></i></button>
                                    <button class="delete-btn"><i class="delete-icon"></i></button>
                                </div>
                            </div>
                            <div class="role-description">
                                <p><?= htmlspecialchars($role->description) ?></p>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Include your JavaScript files -->
    <script src="assets/js/dashboard.js"></script>
</body>
</html>
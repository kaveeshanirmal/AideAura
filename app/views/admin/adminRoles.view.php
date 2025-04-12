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
     <!-- Notification container -->
     <div id="notification" class="notification hidden"></div>
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
                        <?php if (!empty($roles)): ?>
                         <?php foreach($roles as $role): ?>
                        <div class="role-card">
                            <div class="role-header">
                                <h2><?= htmlspecialchars($role->name) ?></h2>
                                <span class="close-btn" onclick="deleteRole('<?= $role->roleID ?>')">&times;</span>
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
                        <?php else: ?>
                            <p>No roles available.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Include your JavaScript files -->
    <script src="assets/js/dashboard.js"></script>
    <script>

     // Notification Functionality
     const notification = document.getElementById('notification');
        const showNotification = (message, type) => {
            notification.textContent = message;
            notification.className = `notification ${type} show`;
            setTimeout(() => notification.className = 'notification hidden', 2000);
        };


    function deleteRole(roleID) {
        if (!confirm('Are you sure you want to delete this role?')) return;

        fetch('<?=ROOT?>/public/Admin/deleteRoles', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ roleID })
        })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                showNotification('Role deleted successfully','success'); // Temporary notification
                setTimeout(() => location.reload(), 2000); // Reload after 2 seconds
            } else {
                showNotification('Delete failed', 'error');
            }
        })
        .catch(error => {
            showNotification('An unexpected error occurred','error');
        });
    }
</script>

</body>
</html>
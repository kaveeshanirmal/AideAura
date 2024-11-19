<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Add Role</title>
    <link rel="stylesheet" href="<?=ROOT?>/public/assets/css/adminRoles1.css">
</head>
<body>
    <div class="dashboard-container">
        <!-- Include your existing sidebar component -->
        <?php include(ROOT_PATH . '/app/views/components/admin_sidebar.view.php'); ?>

        <div class="main-content">
            <!-- Include your existing navbar component -->
            <?php include(ROOT_PATH . '/app/views/components/admin_navbar.view.php');  ?>

            <div class="content-wrapper">
                <div class="role-form-container">
                    <form action="process_role.php" method="POST" class="role-form">
                        <div class="form-group">
                            <label for="roleName">Role Name :</label>
                            <input type="text" 
                                   id="roleName" 
                                   name="roleName" 
                                   placeholder="Cleaner"
                                   class="form-input"
                                   required>
                        </div>

                        <div class="form-group">
                            <label for="roleDescription">Description of the Role :</label>
                            <textarea id="roleDescription" 
                                      name="roleDescription" 
                                      class="form-textarea"
                                      required>This is the special role of cleaning service Employees including indoor cleaning, outdoor cleaning and bathroom/kitchen cleaning.</textarea>
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="add-btn">
                            <a href="<?=ROOT?>/public/adminRoles">Add</a>
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
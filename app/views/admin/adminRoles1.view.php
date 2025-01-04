<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Add Role</title>
    <link rel="stylesheet" href="<?=ROOT?>/public/assets/css/adminRoles1.css">
</head>
<body>
    <div class="dashboard-container">
        <!-- Include your existing sidebar component -->
        <?php include(ROOT_PATH . '/app/views/components/admin_navbar.view.php');  ?>
        <div class="main-content">
            <!-- Include your existing navbar component -->
            <div class="content-wrapper">
                <div class="role-form-container">
                    <form action="<?=ROOT?>/public/admin/addRole" method="POST" class="role-form" enctype="multipart/form-data">
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
                                      placeholder="This is the special role of cleaning service employees, including indoor cleaning, outdoor cleaning, and bathroom/kitchen cleaning."
                                      class="form-textarea"
                                      required>
                            </textarea>
                        </div>

                        <div class="form-group">
                        <label for="roleImage">Upload Role Image :</label>
                        <input type="file" 
                               id="roleImage" 
                               name="roleImage" 
                               class="form-input file-input"
                               accept="image/*"
                               required>
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="add-btn">
                                Add
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Include your JavaScript files -->
</body>
</html>
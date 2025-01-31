<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Add Role</title>
    <link rel="stylesheet" href="<?=ROOT?>/public/assets/css/adminRoles1.css">
</head>
<body>
    <div id="notification" class="notification hidden"></div>
    <div class="dashboard-container">
        <?php include(ROOT_PATH . '/app/views/components/employeeNavbar.view.php'); ?>
        <div class="main-content">
            <div class="content-wrapper">
                <div class="role-form-container">
                    <form id="roleForm" action="<?=ROOT?>/public/admin/addRole" method="POST" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="roleName">Role Name :</label>
                            <input type="text" id="roleName" name="roleName" placeholder="Cleaner" class="form-input" required>
                        </div>

                        <div class="form-group">
                            <label for="roleDescription">Description of the Role :</label>
                            <textarea id="roleDescription" name="roleDescription" placeholder="Description of role..." class="form-textarea" required></textarea>
                        </div>

                        <div class="form-group">
                            <label for="roleImage">Upload Role Image :</label>
                            <input type="file" id="roleImage" name="roleImage" class="form-input file-input" accept="image/*" required>
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
        const form = document.getElementById('roleForm');
        const notification = document.getElementById('notification');

        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(form);

            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    body: formData,
                });
                const result = await response.json();

                if (result.status === 'success') {
            showNotification(result.message, 'success');
            setTimeout(() => {
                window.location.href = '<?=ROOT?>/public/Admin/workerRoles1';
            }, 2000);
        } else {
            showNotification(result.message, 'error');
        }
                showNotification(result.message, result.status === 'success' ? 'success' : 'error');
            } catch (error) {
                showNotification('An error occurred while processing the form.', 'error');
            }
        });

        function showNotification(message, type) {
            notification.textContent = message;
            notification.className = `notification ${type} show`;
            setTimeout(() => {
                notification.className = 'notification hidden';
            });
        }
    </script>
</body>
</html>

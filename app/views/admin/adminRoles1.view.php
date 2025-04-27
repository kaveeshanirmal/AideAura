<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Add Role</title>
    <link rel="stylesheet" href="<?=ROOT?>/public/assets/css/adminRoles1.css">
</head>
<body>
    <div id="notification" class="notification hidden"></div>
    <div class="dashboard-container">
        <?php include(ROOT_PATH . '/app/views/components/employeeNavbar.view.php'); ?>
        <div class="main-content">
            <div class="content-wrapper">
                <div class="role-form-container">
                    <form id="roleForm" action="<?=ROOT?>/public/Admin/addRole" class="role-form" method="POST" enctype="multipart/form-data">
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
const roleNameInput = document.getElementById('roleName')


form.addEventListener('submit', async (e) => {
    e.preventDefault();
        // Validate role name
        const roleName = roleNameInput.value.trim();
if (!/^[a-zA-Z0-9]+$/.test(roleName)) {
    showNotification('Role name must consist of only letters and digits.', 'error');
    return;
}


    const formData = new FormData(form);
    
    try {
        // Show loading notification
        showNotification('Adding role...', 'info');
        
        const response = await fetch(form.action, {
            method: 'POST',
            body: formData
        });
        
        // Log raw response for debugging
        const responseText = await response.text();
        console.log('Raw server response:', responseText);
        
        // // Try to parse the response as JSON
        let result;
        try {
            result = JSON.parse(responseText);
        } catch (parseError) {
            console.error('JSON parsing error:', parseError);
            showNotification('Server returned invalid response', 'error');
            return;
        }
        
        if (result.status === 'success') {
            showNotification(result.message, 'success');
            setTimeout(() => {
                window.location.href = '<?=ROOT?>/public/Admin/workerRoles';
            }, 2000);
        } else {
            showNotification(result.message || 'Failed to add role', 'error');
        }
    } catch (error) {
        console.error('Fetch error:', error);
        showNotification('Network error occurred. Please try again.', 'error');
    }
});

function showNotification(message, type) {
    notification.textContent = message;
    notification.className = `notification ${type} show`;
    setTimeout(() => {
        notification.className = 'notification hidden';
    }, 3000); // Added specific timeout
}
    </script>
</body>
</html>

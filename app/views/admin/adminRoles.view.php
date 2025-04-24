<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Roles</title>
    <link rel="stylesheet" href="<?=ROOT?>/public/assets/css/adminRoles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

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
                        <div class="role-card" data-role-id="<?= $role->roleID ?>" data-role-name="<?= htmlspecialchars($role->name) ?>" data-role-description="<?= htmlspecialchars($role->description) ?>">
                            <div class="role-header">
                                <h2><?= htmlspecialchars($role->name) ?></h2>
                                <div class="role-buttons">
                                    <button class="update-btn" onclick="showUpdateModal(this)">
                                        <i class="fas fa-sync-alt"></i>
                                    </button>
                                    <span class="delete-btn" data-role-id="<?= $role->roleID ?>" onclick="deleteRole('<?= $role->roleID ?>')">&times;</span>
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

    <!-- update Model -->
    <div id="updateModel" class="modal">
        <div class="modal-content">
            <span class="close-btn" onclick="closeUpdateModal()">&times;</span>
            <h2 class="topic">Update Role Details</h2>
            <form id="updateForm">
            <div class="form-group">
                <input type="hidden" id="roleID" name="roleID" value="<?= isset($role->roleID) ? htmlspecialchars($role->roleID) : '' ?>">
                                <label for="roleName">Role Name :</label>
                                <input type="text" id="roleName" name="roleName" class="form-input" required>
                            </div>

                            <div class="form-group">
                                <label for="roleDescription">Description of the Role :</label>
                                <textarea id="roleDescription" name="roleDescription" placeholder="Description of role..." class="form-textarea" required></textarea>
                            </div>
                            <button type="button" class="submit-btn" onclick="updateRole()">Update</button>
            </form>
        </div>
    </div>

    <script>
     // Use a different approach instead of relying on the roles JSON data
     
     // Notification Functionality
     const notification = document.getElementById('notification');
        const showNotification = (message, type) => {
            notification.textContent = message;
            notification.className = `notification ${type} show`;
            setTimeout(() => notification.className = 'notification hidden', 2000);
        };

    // function to update the role
    function showUpdateModal(button) {
        const roleCard = button.closest('.role-card');
        const roleID = roleCard.getAttribute('data-role-id');
        const roleName = roleCard.getAttribute('data-role-name');
        const roleDescription = roleCard.getAttribute('data-role-description');

        // Debugging: Log variable values to the console
        console.log('Role ID:', roleID);
        console.log('Role Name:', roleName);
        console.log('Role Description:', roleDescription);

        document.getElementById('roleID').value = roleID;
        document.getElementById('roleName').value = roleName;
        document.getElementById('roleDescription').value = roleDescription;
        document.getElementById('updateModel').style.display = 'block';

        // Debugging: Confirm modal visibility
        console.log('Update modal displayed');
    }

    function closeUpdateModal(){
        document.getElementById('updateModel').style.display = "none";
    }


    function updateRole() {
       const roleID = document.getElementById('roleID').value;
       const roleName = document.getElementById('roleName').value;
       const roleDescription = document.getElementById('roleDescription').value;
    
       if(!roleName || !roleDescription) {
          showNotification('Please fill all required fields', 'error');
          console.error('Validation Error: Missing required fields');
          return;
       }

       console.log('Preparing to send update request with data:', {
          roleID: roleID,
          name: roleName,
          description: roleDescription
       });

       fetch(`<?=ROOT?>/public/Admin/updateRole`,{
          method: 'POST',
          headers: { 'Content-Type': 'application/json'},
          body: JSON.stringify ({
             roleID: roleID,
             name: roleName,
             description: roleDescription 
          })
       })
       .then(response => {
          console.log('Response status:', response.status);
          return response.json();
       })
       .then(result =>{
          console.log('Server response:', result);
          if(result.success){
             showNotification('Role updated successfully', 'success');
             setTimeout(() => location.reload(), 2000);
          } else {
             showNotification(result.message || 'Update failed','error');
             console.error('Update failed:', result.message || 'Unknown error');
          }
       })
       .catch(error => {
          showNotification('An unexpected error occurred', 'error');
          console.error('Fetch Error:', error);
       });

       closeUpdateModal();
       console.log('Update modal closed');
    }


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
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Roles</title>
    <link rel="stylesheet" href="<?=ROOT?>/public/assets/css/adminRoles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <!-- Notification container -->
    <div id="notification" class="notification hidden"></div>
    <div class="dashboard-container">
        <!-- Include your existing sidebar component -->
        <?php include(ROOT_PATH . '/app/views/components/employeeNavbar.view.php'); ?>
        <div class="main-content">
            <div class="role-details">
                <button class="add-role-btn">
                    <a href="workerRoles1">Add Role</a>
                </button>
            </div>
            
            <div class="table-container">
                <table class="role-table">
                    <thead>
                        <tr>
                            <th>Role ID</th>
                            <th>Role Name</th>
                            <th>Description</th>
                            <th>Update</th>
                            <th>Delete</th>
                        </tr>
                    </thead>
                    <tbody id="roleTableBody">
                        <!-- Role data will be populated here -->
                    </tbody>
                </table>
                
                <!-- Add pagination div -->
                <div id="pagination" class="pagination"></div>
            </div>
        </div>
    </div>

    <!-- Update Modal -->
    <div id="updateModel" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeUpdateModal()">&times;</span>
            <h2 class="topic">Update Role Details</h2>
            <form id="updateForm">
                <div class="form-group">
                    <input type="hidden" id="roleID" name="roleID" value="<?= isset($role->roleID) ? htmlspecialchars($role->roleID) : '' ?>">
                    <label for="roleName">Role Name:</label>
                    <input type="text" id="roleName" name="roleName" class="form-input" required>
                </div>

                <div class="form-group">
                    <label for="roleDescription">Description of the Role:</label>
                    <textarea id="roleDescription" name="roleDescription" placeholder="Description of role..." class="form-textarea" required></textarea>
                </div>
                <button type="button" class="submit-btn" onclick="updateRole()">Update</button>
            </form>
        </div>
    </div>

    <script>
        // Notification Functionality
        const notification = document.getElementById('notification');
        const showNotification = (message, type) => {
            notification.textContent = message;
            notification.className = `notification ${type} show`;
            setTimeout(() => notification.className = 'notification hidden', 2000);
        };

        const roles = <?= isset($roles) ? json_encode($roles) : '[]' ?>;
        const tableBody = document.getElementById('roleTableBody');
        const pagination = document.getElementById('pagination');

        // Make sure roles is an array
        let rolesArray = Array.isArray(roles) ? roles : 
                        (typeof roles === 'object' ? Object.values(roles) : []);

        // Pagination settings
        const rowsPerPage = 3;
        let currentPage = 1;

        // Render roles table with pagination
        function renderTable(data) {
            tableBody.innerHTML = '';
            
            // Ensure data is an array
            const dataArray = Array.isArray(data) ? data : [];
            
            if (dataArray.length === 0) {
                tableBody.innerHTML = `<tr><td colspan="5" style="text-align: center; font-style: italic;">No roles found.</td></tr>`;
                pagination.innerHTML = ''; // Clear pagination if no roles
                return;
            }

            const start = (currentPage - 1) * rowsPerPage;
            const paginatedItems = dataArray.slice(start, start + rowsPerPage);

            paginatedItems.forEach(role => {
                tableBody.innerHTML += `
                    <tr data-role-id="${role.roleID}" data-role-name="${role.name}" data-role-description="${role.description}">
                        <td>${role.roleID}</td>
                        <td>${role.name}</td>
                        <td>${role.description}</td>
                        <td>
                            <button class="update-btn" onclick="showUpdateModal(this)">
                                <i class="fas fa-sync-alt"></i>
                            </button>
                        </td>
                        <td>
                            <button class="delete-btn" onclick="deleteRole('${role.roleID}')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>`;
            });

            renderPagination(dataArray.length);
        }

        function changePage(page) {
            currentPage = page;
            renderTable(rolesArray);
        }

        function renderPagination(totalItems) {
    pagination.innerHTML = '';

    const pageCount = Math.ceil(totalItems / rowsPerPage);

    if (pageCount <= 1) return;

    // Previous button
    if (currentPage > 1) {
        const prevButton = document.createElement('button');
        prevButton.textContent = 'Previous';
        prevButton.onclick = () => changePage(currentPage - 1);
        pagination.appendChild(prevButton);
    }

    // Numbered buttons
    for (let i = 1; i <= pageCount; i++) {
        const btn = document.createElement('button');
        btn.textContent = i;
        btn.classList.toggle('active', i === currentPage);
        btn.onclick = () => changePage(i);
        pagination.appendChild(btn);
    }

    // Next button
    if (currentPage < pageCount) {
        const nextButton = document.createElement('button');
        nextButton.textContent = 'Next';
        nextButton.onclick = () => changePage(currentPage + 1);
        pagination.appendChild(nextButton);
    }
}


        // Function to update the role
        function showUpdateModal(button) {
            const row = button.closest('tr');
            const roleID = row.getAttribute('data-role-id');
            const roleName = row.getAttribute('data-role-name');
            const roleDescription = row.getAttribute('data-role-description');

            document.getElementById('roleID').value = roleID;
            document.getElementById('roleName').value = roleName;
            document.getElementById('roleDescription').value = roleDescription;
            document.getElementById('updateModel').style.display = 'block';
        }

        function closeUpdateModal() {
            document.getElementById('updateModel').style.display = "none";
        }

        function updateRole() {
            const roleID = document.getElementById('roleID').value;
            const roleName = document.getElementById('roleName').value;
            const roleDescription = document.getElementById('roleDescription').value;
            
            // validate details befor update 
            if(!roleName || !roleDescription) {
                showNotification('Please fill all required fields', 'error');
                return;
            }

            if (!/^[a-zA-Z0-9]+$/.test(roleName)) {
    showNotification('Role name must consist of only letters and digits.', 'error');
    return;
}

            fetch(`<?=ROOT?>/public/Admin/updateRole`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json'},
                body: JSON.stringify({
                    roleID: roleID,
                    name: roleName,
                    description: roleDescription 
                })
            })
            .then(response => response.json())
            .then(result => {
                if(result.success) {
                    showNotification('Role updated successfully', 'success');
                    setTimeout(() => location.reload(), 3000);
                } else {
                    showNotification(result.message || 'Update failed', 'error');
                }
            })
            .catch(error => {
                showNotification('An unexpected error occurred', 'error');
            });

            closeUpdateModal();
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
                    showNotification('Role deleted successfully', 'success');
                    setTimeout(() => location.reload(), 2000);
                } else {
                    showNotification('Delete failed', 'error');
                }
            })
            .catch(error => {
                showNotification('An unexpected error occurred', 'error');
            });
        }

        renderTable(rolesArray);
    </script>
</body>
</html>

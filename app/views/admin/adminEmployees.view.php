<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Employee Management</title>
    <link rel="stylesheet" href="<?=ROOT?>/public/assets/css/adminEmployees.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <!-- Notification container -->
    <div id="notification" class="notification hidden"></div>
    <div class="dashboard-container">
        <?php include(ROOT_PATH . '/app/views/components/admin_navbar.view.php'); ?>
        <div class="main-content">
            <div class="employee-details">
                <button class="add-employee-btn">
                    <a href="<?=ROOT?>/public/adminEmployeeAdd">Add User</a>
                </button>
            </div>
            <div class="employee-controls">
                <div class="search-filters">
                    <div class="input-group">
                        <label for="employeeRole">Role:</label>
                        <select id="employeeRole" class="role-select">
                            <option value="hrManager">hrManager</option>
                            <option value="financeManager">financeManager</option>
                            <option value="opManager">opManager</option>
                            <option value="admin">admin</option>
                        </select>
                    </div>
                    <div class="input-group">
                        <label for="employeeId">User ID:</label>
                        <input type="text" id="employeeId" class="id-input">
                    </div>
                    <div class="search-btn-container">
                        <button class="search-btn" onclick="searchEmployees()">Search</button>
                    </div>
                </div>
            </div>
            <div class="table-container">
                <table class="employee-table">
                    <thead>
                        <tr>
                            <th>User ID</th>
                            <th>Username</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Role</th>
                            <th>Phone</th>
                            <th>Email</th>
                            <th>Date of Hire</th>
                            <th>Update</th>
                            <th>Delete</th>
                        </tr>
                    </thead>
                    <tbody id="employeeTableBody">
                        <?php foreach ($employees as $employee): ?>
                        <tr data-id="<?= htmlspecialchars($employee->userID) ?>" data-username="<?= htmlspecialchars($employee->username) ?>" data-firstname="<?= htmlspecialchars($employee->firstName) ?>" data-lastname="<?= htmlspecialchars($employee->lastName) ?>" data-role="<?= htmlspecialchars($employee->role) ?>" data-phone="<?= htmlspecialchars($employee->phone) ?>" data-email="<?= htmlspecialchars($employee->email) ?>" data-createdAt="<?= htmlspecialchars($employee->createdAt) ?>">
                            <td><?= htmlspecialchars($employee->userID) ?></td>
                            <td><?= htmlspecialchars($employee->username) ?></td>
                            <td><?= htmlspecialchars($employee->firstName) ?></td>
                            <td><?= htmlspecialchars($employee->lastName) ?></td>
                            <td><?= htmlspecialchars($employee->role) ?></td>
                            <td><?= htmlspecialchars($employee->phone) ?></td>
                            <td><?= htmlspecialchars($employee->email) ?></td>
                            <td><?= htmlspecialchars($employee->createdAt) ?></td>
                            <td>
                                <button class="update-btn" onclick="showUpdateModal(this)">
                                    <i class="fas fa-sync-alt"></i>
                                </button>
                            </td>
                            <td>
                                <button class="delete-btn" onclick="deleteEmployee('<?= $employee->userID ?>')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Update Modal -->
    <div id="updateModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeUpdateModal()">&times;</span>
            <h2 class="topic">Update User Details</h2>
            <form id="updateEmployeeForm" onsubmit="event.preventDefault(); updateEmployee();">
                <input type="hidden" id="updateEmployeeId">
                <div class="form-item">
                    <label for="updateUserName">Username:</label>
                    <input class="inputc1" type="text" id="updateUserName">
                </div>
                <div class="form-item">
                    <label for="updatefirstName">First Name:</label>
                    <input class="inputc2" type="text" id="updatefirstName">
                </div>
                <div class="form-item">
                    <label for="updatelastName">Last Name:</label>
                    <input class="inputc3" type="text" id="updatelastName">
                </div>
                <div class="form-item">
                    <label for="updateRole">Role:</label>
                    <select class="inputc4" id="updateRole">
                        <option value="hrManager">hrManager</option>
                        <option value="financeManager">financeManager</option>
                        <option value="opManager">opManager</option>
                        <option value="admin">admin</option>
                    </select>
                </div>
                <div class="form-item">
                    <label for="updatePhone">Phone:</label>
                    <input class="inputc5" type="text" id="updatePhone">
                </div>
                <div class="form-item">
                    <label for="updateEmail">Email:</label>
                    <input class="inputc6" type="email" id="updateEmail">
                </div>
                <button type="submit">Update</button>
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

        // Show Update Modal
        function showUpdateModal(button) {
            const row = button.closest('tr');
            const userID = row.getAttribute('data-id');
            const username = row.getAttribute('data-username');
            const firstName = row.getAttribute('data-firstname');
            const lastName = row.getAttribute('data-lastname');
            const role = row.getAttribute('data-role');
            const phone = row.getAttribute('data-phone');
            const email = row.getAttribute('data-email');

            document.getElementById('updateEmployeeId').value = userID;
            document.getElementById('updateUserName').value = username;
            document.getElementById('updatefirstName').value = firstName;
            document.getElementById('updatelastName').value = lastName;
            document.getElementById('updateRole').value = role;
            document.getElementById('updatePhone').value = phone;
            document.getElementById('updateEmail').value = email;

            document.getElementById('updateModal').style.display = 'block';
        }

        function closeUpdateModal() {
            document.getElementById('updateModal').style.display = 'none';
        }

        function updateEmployee() {
            const userID = document.getElementById('updateEmployeeId').value;
            const data = {
                userID,
                username: document.getElementById('updateUserName').value,
                firstName: document.getElementById('updatefirstName').value,
                lastName: document.getElementById('updatelastName').value,
                role: document.getElementById('updateRole').value,
                phone: document.getElementById('updatePhone').value,
                email: document.getElementById('updateEmail').value,
            };

            fetch('<?=ROOT?>/public/adminEmployees/update', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data),
            })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    closeUpdateModal();
                    showNotification('Employee updated successfully', 'success');
                    location.reload();
                } else {
                    showNotification('Update failed', 'error');
                }
            })
            .catch(error => showNotification('An unexpected error occurred', 'error'));
        }

        function deleteEmployee(userID) {
            if (!confirm('Are you sure you want to delete this employee?')) return;

            fetch('<?=ROOT?>/public/adminEmployees/delete', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ userID }),
            })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    showNotification('Employee deleted successfully', 'success');
                    location.reload();
                } else {
                    showNotification('Delete failed', 'error');
                }
            })
            .catch(error => showNotification('An unexpected error occurred', 'error'));
        }

        function searchEmployees() {
    const role = document.getElementById('employeeRole').value;
    const userID = document.getElementById('employeeId').value;

    console.log(role, userID); // Check what is being sent

    fetch('<?=ROOT?>/public/adminEmployees/search', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ role, userID }),
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(result => {
        console.log(result); // Add this to see the response from the server
        if (result.success) {
            const tableBody = document.getElementById('employeeTableBody');
            tableBody.innerHTML = result.employees.map(employee => `
                <tr data-id="${employee.userID}" data-username="${employee.username}" data-firstname="${employee.firstName}" data-lastname="${employee.lastName}" data-role="${employee.role}" data-phone="${employee.phone}" data-email="${employee.email}" data-createdAt="${employee.createdAt}">
                    <td>${employee.userID}</td>
                    <td>${employee.username}</td>
                    <td>${employee.firstName}</td>
                    <td>${employee.lastName}</td>
                    <td>${employee.role}</td>
                    <td>${employee.phone}</td>
                    <td>${employee.email}</td>
                    <td>${employee.createdAt}</td>
                </tr>
            `).join('');
        } else {
            showNotification(result.message, 'error');
        }
    })
    .catch(error => {
        console.error('There was a problem with the fetch operation:', error);
        showNotification('An unexpected error occurred', 'error');
    });
}

    </script>
</body>
</html>

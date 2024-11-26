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
                    <a href="<?=ROOT?>/public/adminEmployeeAdd">Add Employee</a>
                </button>
            </div>
            <div class="employee-controls">
                <div class="search-filters">
                    <div class="input-group">
                        <label>Role:</label>
                        <select id="employeeRole" class="role-select">
                            <option value="HR Manager">HR Manager</option>
                            <option value="Finance Manager">Finance Manager</option>
                            <option value="Operational Manager">Operational Manager</option>
                        </select>
                    </div>
                    <div class="input-group">
                        <label>Status:</label>
                        <select id="employeeStatus" class="status-select">
                            <option value="Active">Active</option>
                            <option value="Inactive">Inactive</option>
                        </select>
                    </div>
                    <div class="input-group">
                        <label>Employee ID:</label>
                        <input type="text" id="employeeId" class="id-input">
                    </div>
                    <div class="input-group">
                        <label>Email:</label>
                        <input type="email" id="employeeEmail" class="email-input">
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
                            <th>ID</th>
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
                        <tr data-id="<?= htmlspecialchars($employee->userID) ?>">
                            <td><?= htmlspecialchars($employee->userID) ?></td>
                            <td><?= htmlspecialchars($employee->username) ?></td>
                            <td><?= htmlspecialchars($employee->firstName) ?></td>
                            <td><?= htmlspecialchars($employee->lastName) ?></td>
                            <td><?= htmlspecialchars($employee->role) ?></td>
                            <td><?= htmlspecialchars($employee->phone) ?></td>
                            <td><?= htmlspecialchars($employee->email) ?></td>
                            <td><?= htmlspecialchars($employee->createdAt) ?></td>
                            <td>
                                <button class="update-btn" onclick="showUpdateModal('<?= $employee->userID ?>')">
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

    <script>
        // Notification system
        const notification = document.getElementById('notification');
        const showNotification = (message, type) => {
            notification.textContent = message;
            notification.className = `notification ${type} show`;

            setTimeout(() => {
                notification.className = 'notification hidden';
            }, 3000);
        };

        // Example integration with deleteEmployee
        function deleteEmployee(userID) {
            if (!userID) {
                showNotification('Invalid Employee ID', 'error');
                return;
            }

            if (confirm('Are you sure you want to delete this employee?')) {
                fetch('<?=ROOT?>/public/adminEmployees/delete', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ userID: userID }),
                })
                    .then(response => response.json())
                    .then(result => {
                        if (result.success) {
                            document.querySelector(`tr[data-id="${userID}"]`).remove();
                            showNotification('Employee deleted successfully','success');
                        } else {
                            showNotification(result.message || 'Error deleting employee','error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showNotification('An unexpected error occurred', 'error');
                    });
            }
        }

        // Integrate notification in other functions (e.g., updateEmployee)
        function updateEmployee() {
            const id = document.getElementById('updateEmployeeId').value;
            const data = {
                id: id,
                name: document.getElementById('updateName').value,
                role: document.getElementById('updateRole').value,
                email: document.getElementById('updateEmail').value,
                contact: document.getElementById('updateContact').value,
                status: document.getElementById('updateStatus').value
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
                        searchEmployees();
                        showNotification('Employee updated successfully');
                    } else {
                        showNotification('Update failed', true);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('An unexpected error occurred', true);
                });
        }
    </script>
</body>

</html>

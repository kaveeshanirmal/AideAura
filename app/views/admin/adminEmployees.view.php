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
                                <button class="update-btn" onclick="showUpdateModal('<?= $employee->userID?>')">
                                    <i class="fas fa-sync-alt"></i>
                                </button>
                            </td>
                            <td>
                                <button class="delete-btn" onclick="deleteEmployee('<?= $employee->userID?>')">
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

      // Print the employees array from the backend
      const employees = <?php echo json_encode($employees, JSON_HEX_TAG | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_HEX_APOS); ?>;
      console.log('Employees:', employees);
      
        // Search employees
        function searchEmployees() {
            const params = new URLSearchParams({
                name: document.getElementById('employeeName').value,
                role: document.getElementById('employeeRole').value,
                email: document.getElementById('employeeEmail').value,
                status: document.getElementById('employeeStatus').value,
                id: document.getElementById('employeeId').value
            });

            fetch(`<?=ROOT?>/public/adminEmployees/search?${params}`)
                .then(response => response.json())
                .then(data => {
                    const tbody = document.getElementById('employeeTableBody');
                    tbody.innerHTML = '';
                    
                    data.forEach(employee => {
                        const row = `
                            <tr data-id="${employee.id}">
                                <td>${employee.id}</td>
                                <td>${employee.name}</td>
                                <td>${employee.role}</td>
                                <td>${employee.email}</td>
                                <td>${employee.contact}</td>
                                <td>${employee.password}</td>
                                <td>${employee.date_of_hire}</td>
                                <td>
                                    <button class="update-btn" onclick="showUpdateModal('${employee.id}')">
                                        <i class="fas fa-sync-alt"></i>
                                    </button>
                                </td>
                                <td>
                                    <button class="delete-btn" onclick="deleteEmployee('${employee.id}')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        `;
                        tbody.innerHTML += row;
                    });
                })
                .catch(error => console.error('Error:', error));
        }

        // Show update modal
        function showUpdateModal(id) {
            const row = document.querySelector(`tr[data-id="${id}"]`);
            const cells = row.getElementsByTagName('td');

            document.getElementById('updateEmployeeId').value = id;
            document.getElementById('updateName').value = cells[1].textContent;
            document.getElementById('updateRole').value = cells[2].textContent;
            document.getElementById('updateEmail').value = cells[3].textContent;
            document.getElementById('updateContact').value = cells[4].textContent;
            document.getElementById('updateStatus').value = cells[7].textContent;

            document.getElementById('updateModal').style.display = 'block';
        }

        // Close update modal
        function closeUpdateModal() {
            document.getElementById('updateModal').style.display = 'none';
        }

        // Update employee
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
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    closeUpdateModal();
                    searchEmployees(); // Refresh the table
                } else {
                    alert('Update failed');
                }
            })
            .catch(error => console.error('Error:', error));
        }

        // Delete employee
        function deleteEmployee(userID) {
            console.log(userID);
      if (confirm('Are you sure you want to delete this employee?')) {
        fetch('<?=ROOT?>/public/adminEmployees/delete', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ userID: userID }),
        })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    // Remove the deleted row from the table
                    const row = document.querySelector(`tr[data-id="${userID}"]`);
                    if (row) {
                        row.remove();
                    }
                    alert('Employee deleted successfully');
                } else {
                    alert('Failed to delete employee');
                }
            })
            .catch(error => console.error('Error:', error));
    }
}


        // Add event listeners for real-time search
        document.querySelectorAll('.search-filters input, .search-filters select, .employee-details input')
            .forEach(element => {
                element.addEventListener('input', debounce(searchEmployees, 500));
            });

        // Debounce function to prevent too many requests
        function debounce(func, wait) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func(...args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        }
    </script>
</body>
</html>

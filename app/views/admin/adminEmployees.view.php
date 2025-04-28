<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Employees</title>
    <link rel="stylesheet" href="<?= ROOT ?>/public/assets/css/adminEmployees.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>
    <!-- Notification container -->
    <div id="notification" class="notification hidden"></div>
    <div class="dashboard-container">
        <?php include(ROOT_PATH . '/app/views/components/employeeNavbar.view.php'); ?>
        <div class="main-content">
            <div class="employee-details">
                <button class="add-employee-btn">
                    <a href="<?= ROOT ?>/public/adminEmployeeAdd">Add Employee</a>
                </button>
            </div>
            <div class="employee-controls">
                <div class="search-filters">
                    <div class="input-group">
                        <label for="employeeRole">Role:</label>
                        <select id="employeeRole" class="role-select">
                            <option value="" selected disabled>-- Select Role --</option>
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
                    <div class="search-btn-container">
                        <button class="search-btn" onclick="resetEmployees()">Reset</button>
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
                        <?php if (empty($employees)): ?>
                            <tr>
                                <td colspan="10" style="text-align: center; font-style: italic;">
                                    No employee found.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
                <!-- Add pagination container -->
                <div id="pagination" class="pagination"></div>
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
                    <input class="inputc1" type="text" id="updateUserName" required>
                </div>
                <div class="form-item">
                    <label for="updatefirstName">First Name:</label>
                    <input class="inputc2" type="text" id="updatefirstName" required>
                </div>
                <div class="form-item">
                    <label for="updatelastName">Last Name:</label>
                    <input class="inputc3" type="text" id="updatelastName" required>
                </div>
                <div class="form-item">
                    <label for="updateRole">Role:</label>
                    <select class="inputc4" id="updateRole" required>
                        <option value="hrManager">hrManager</option>
                        <option value="financeManager">financeManager</option>
                        <option value="opManager">opManager</option>
                        <option value="admin">admin</option>
                    </select>
                </div>
                <div class="form-item">
                    <label for="updatePhone">Phone:</label>
                    <input class="inputc5" type="text" id="updatePhone" required>
                </div>
                <div class="form-item">
                    <label for="updateEmail">Email:</label>
                    <input class="inputc6" type="email" id="updateEmail" required>
                </div>
                <button type="submit">Update</button>
            </form>
        </div>
    </div>

    <script>
        // Get initial data from PHP
        const initialEmployees = <?= json_encode($employees ?? []) ?>;  //converts the $employees array (passed from the controller) into a JSON object
        console.log(initialEmployees);

        // Pagination variables
        const rowsPerPage = 3;
        let currentPage = 1;
        let currentData = [];

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

            // Get all data attributes from the row
            const userID = row.getAttribute('data-id');
            const username = row.getAttribute('data-username');
            const firstName = row.getAttribute('data-firstname');
            const lastName = row.getAttribute('data-lastname');
            const role = row.getAttribute('data-role');
            const phone = row.getAttribute('data-phone');
            const email = row.getAttribute('data-email');

            // Log the values to console for debugging
            console.log("Modal data:", {
                userID,
                username,
                firstName,
                lastName,
                role,
                phone,
                email
            });

            // Set the values in the form fields
            document.getElementById('updateEmployeeId').value = userID;
            document.getElementById('updateUserName').value = username;
            document.getElementById('updatefirstName').value = firstName;
            document.getElementById('updatelastName').value = lastName;
            document.getElementById('updateRole').value = role;
            document.getElementById('updatePhone').value = phone;
            document.getElementById('updateEmail').value = email;

            // Show the modal
            document.getElementById('updateModal').style.display = 'block';
        }


        const validateUpdateForm = () => {
            const username = document.getElementById('updateUserName').value.trim();
            const firstName = document.getElementById('updatefirstName').value.trim();
            const lastName = document.getElementById('updatelastName').value.trim();
            const role = document.getElementById('updateRole').value.trim();
            const phone = document.getElementById('updatePhone').value.trim();
            const email = document.getElementById('updateEmail').value.trim();

            if (!/^[a-zA-Z\s]+$/.test(firstName)) {
                showNotification('First name must contain only letters.', 'error');
                return false;
            }

            if (!/^[a-zA-Z\s]+$/.test(lastName)) {
                showNotification('Last name must contain only letters.', 'error');
                return false;
            }

            if (!username) {
                showNotification('Username is required.', 'error');
                return false;
            }

            if (!/^[0-9]{10}$/.test(phone)) {
                showNotification('Contact number must be exactly 10 digits.', 'error');
                return false;
            }

            if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                showNotification('Please enter a valid email address.', 'error');
                return false;
            }

            return true;
        };


        function closeUpdateModal() {
            document.getElementById('updateModal').style.display = 'none';
        }

        function updateEmployee() {
            try {
                if (!validateUpdateForm()) {
                    return; // Stop the function if validation fails
                }
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

                fetch('<?= ROOT ?>/public/adminEmployees/update', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(data),  //serializes a JavaScript object or array into a JSON string
                    })
                    .then(response => response.json())
                    .then(result => {
                        if (result.success) {
                            showNotification('Employee updated successfully', 'success');
                            setTimeout(() => location.reload(), 3000);
                            closeUpdateModal();
                        } else {
                            showNotification('Update failed', 'error');
                        }
                    })
            } catch {
                (error => showNotification('An unexpected error occurred', 'error'));
            }
        }

        function deleteEmployee(userID) {
            try {
                if (!confirm('Are you sure you want to delete this employee?')) return;

                fetch('<?= ROOT ?>/public/adminEmployees/delete', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            userID
                        }),
                    })
                    .then(response => response.json())
                    .then(result => {
                        if (result.success) {
                            showNotification('Employee deleted successfully', 'success');
                            setTimeout(() => location.reload(), 2000);
                        } else {
                            showNotification('Delete failed', 'error');
                        }
                    })
            } catch {
                (error => showNotification('An unexpected error occurred', 'error'));
            }
        }

        // Global array to store all employees
        let allEmployees = [];

        // Function to load all employees initially
        function loadEmployees() {
            try {
                fetch('<?= ROOT ?>/public/adminEmployees/index')  //initiates a GET request to the index function of the adminEmployees controller.
                    .then(response => response.json())   //once the server responds, the then method processes the response. The response.json() method parses the response body as JSON,
                    .then(result => {   // parsed JSON data (result)
                        if (result.success) {
                            allEmployees = result.employees;
                            currentData = allEmployees;
                            renderTable(allEmployees);
                        } else {
                            showNotification('Failed to load employees', 'error');
                        }
                    })
            } catch {
                (error => showNotification('An unexpected error occurred', 'error'));
            }
        }

        function renderTable(employees) {
            const tableBody = document.getElementById('employeeTableBody');  //reference to an HTML element with the id attribute set to employeeTableBody and assigns it to the constant tableBody.

            if (!employees || employees.length === 0) { // check NULL and empty array
                tableBody.innerHTML = `
                <tr>
                    <td colspan="10" style="text-align: center; color: #888;">
                        No employees found matching your search.
                    </td>
                </tr>
            `;
                // Clear pagination if no data
                renderPagination(0);  //clears or hides the pagination controls, as there is no data to paginate
                return;
            }

            // Apply pagination
            const start = (currentPage - 1) * rowsPerPage;
            const paginatedEmployees = employees.slice(start, start + rowsPerPage);

            tableBody.innerHTML = paginatedEmployees.map(employee => `
            <tr 
                data-id="${employee.userID}" 
                data-username="${employee.username}" 
                data-firstname="${employee.firstName}" 
                data-lastname="${employee.lastName}" 
                data-role="${employee.role}" 
                data-phone="${employee.phone}" 
                data-email="${employee.email}"
                data-createdAt="${employee.createdAt}"
            >
                <td>${employee.userID}</td>
                <td>${employee.username}</td>
                <td>${employee.firstName}</td>
                <td>${employee.lastName}</td>
                <td>${employee.role}</td>
                <td>${employee.phone}</td>
                <td>${employee.email}</td>
                <td>${employee.createdAt}</td>
                <td>
                    <button class="update-btn" onclick="showUpdateModal(this)">
                        <i class="fas fa-sync-alt"></i>
                    </button>
                </td>
                <td>
                    <button class="delete-btn" onclick="deleteEmployee('${employee.userID}')">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
        `).join('');

            // Update pagination
            renderPagination(employees.length);
        }


        // function to render pagination controles
        function renderPagination(totalItems) {
            const pagination = document.getElementById('pagination');
            pagination.innerHTML = '';

            // Calculate total pages
            const pageCount = Math.ceil(totalItems / rowsPerPage);

            // Don't show pagination if only one page or no items
            if (pageCount <= 1) return;

            // Add Previous button if not on first page
            if (currentPage > 1) {
                pagination.innerHTML += `<button onclick="changePage(${currentPage - 1})">Previous</button>`;
            }

            // Add numbered page buttons
            for (let i = 1; i <= pageCount; i++) {
                pagination.innerHTML += `<button onclick="changePage(${i})" class="${currentPage === i ? 'active' : ''}">${i}</button>`;
            }

            // Add Next button if not on last page
            if (currentPage < pageCount) {
                pagination.innerHTML += `<button onclick="changePage(${currentPage + 1})">Next</button>`;
            }
        }

        function changePage(page) {
            currentPage = page;
            renderTable(currentData);
        }

        function searchEmployees() {
            const role = document.getElementById('employeeRole').value;
            const userID = document.getElementById('employeeId').value.trim();

            // Create request body
            const filters = {
                role: role,
                userID: userID
            };

            // Send request to search endpoint
            fetch('<?= ROOT ?>/public/adminEmployees/search', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(filters)
                })
                .then(response => response.json())
                .then(result => {
                    if (result.success) {
                        currentPage = 1; // Reset to first page when searching
                        currentData = result.employees;
                        renderTable(result.employees);
                    } else {
                        currentData = [];
                        renderTable([]); // Show "no employees found" row
                        showNotification(result.message || 'Search failed', 'error');
                    }
                })
                .catch(error => {
                    currentData = [];
                    renderTable([]); // Show "no employees found" row on error
                    showNotification('An unexpected error occurred', 'error');
                });
        }

        // Function to reset the search filters and reload all employees
        function resetEmployees() {
            // Simply reload the page to show all employees again
            location.reload();
        }

        // Load employees on page load
        window.onload = () => {
            // Use the data from PHP first if available
            if (initialEmployees && initialEmployees.length > 0) {
                currentData = initialEmployees;
                renderTable(initialEmployees);
            } else {
                loadEmployees();
            }
        };
    </script>
</body>

</html>
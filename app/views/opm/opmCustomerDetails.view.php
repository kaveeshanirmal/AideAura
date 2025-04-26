<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OPM - Customer Details</title>
    <link rel="stylesheet" href="<?=ROOT?>/public/assets/css/opmCustomerDetails.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <!-- Notification container -->
    <div id="notification" class="notification hidden"></div>
    <div class="dashboard-container">
        <?php include(ROOT_PATH . '/app/views/components/employeeNavbar.view.php'); ?>
        <div class="main-content">
            <div class="employee-controls">
                <div class="search-filters">
                    <div class="input-group">
                        <label for="employeeId">Customer ID:</label>
                        <input type="text" id="customerId" class="id-input">
                    </div>
                    <div class="search-btn-container">
                        <button class="search-btn" onclick="searchCustomer()">Search</button>
                    </div>
                    <div class="search-btn-container">
                        <button class="search-btn" onclick="resetCustomer()">Reset</button>
                    </div>
                </div>
            </div>
            <div class="table-container">
                <table class="employee-table">
                    <thead>
                        <tr>
                            <th>Customer ID</th>
                            <th>User ID</th>
                            <th>Username</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Role</th>
                            <th>Phone</th>
                            <th>Email</th>
                            <th>Address</th>
                            <th>Created At</th>
                        </tr>
                    </thead>
                    <tbody id="employeeTableBody">
                    <?php if (empty($customers)): ?>
                        <tr>
                            <td colspan="10" style="text-align: center; font-style: italic;">
                                No customer found.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($customers as $customer): ?>
                        <tr>
                            <td><?= htmlspecialchars($customer->customerID) ?></td>
                            <td><?= htmlspecialchars($customer->userID) ?></td>
                            <td><?= htmlspecialchars($customer->username) ?></td>
                            <td><?= htmlspecialchars($customer->firstName) ?></td>
                            <td><?= htmlspecialchars($customer->lastName) ?></td>
                            <td><?= htmlspecialchars($customer->role) ?></td>
                            <td><?= htmlspecialchars($customer->phone) ?></td>
                            <td><?= htmlspecialchars($customer->email) ?></td>
                            <td><?= htmlspecialchars($customer->address) ?></td>
                            <td><?= htmlspecialchars($customer->createdAt) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
            </div>
        </div>
    </div>

    <script>

     console.log(<?= json_encode($customers) ?>);
        // Notification Functionality
        const notification = document.getElementById('notification');
        const showNotification = (message, type) => {
            notification.textContent = message;
            notification.className = `notification ${type} show`;
            setTimeout(() => notification.className = 'notification hidden', 2000);
        };


    // Global array to store all employees
    let allEmployees = [];

    // Function to load all employees initially
    function loadEmployees() {
        try {
        fetch('<?=ROOT?>/public/opManager/customers')
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    allEmployees = result.employees;
                    renderTable(allEmployees);
                } else {
                    showNotification('Failed to load employees', 'error');
                }
            })
        } catch {
            (error => showNotification('An unexpected error occurred', 'error'));
    }
}

function renderTable(customers) {
    const tableBody = document.getElementById('employeeTableBody');
   
    if (customers.length === 0) {
        tableBody.innerHTML = `
            <tr>
                <td colspan="10" style="text-align: center; color: #888;">
                    No customer found for the given customerID. 
                </td>
            </tr>
        `;
        return;
    }
   
    tableBody.innerHTML = customers.map(customer => `
        <tr>
            <td>${customer.customerID}</td>
            <td>${customer.userID}</td>
            <td>${customer.username}</td>
            <td>${customer.firstName}</td>
            <td>${customer.lastName}</td>
            <td>${customer.role}</td>
            <td>${customer.phone}</td>
            <td>${customer.email}</td>
            <td>${customer.address}</td>
            <td>${customer.createdAt}</td>
        </tr>
    `).join('');
}
function searchCustomer() {
    const customerID = document.getElementById('customerId').value.trim();
    console.log(customerID); // Debugging line to check the value of customerID
    // Create request body
    const filters = {
        customerID: customerID
    };
    
    // Send reque+st to search endpoint
    fetch('<?=ROOT?>/public/opManager/searchCustomers', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(filters)
    })
    .then(response => response.json())
    .then(result => {
        if (result.success) {
            renderTable(result.customers);
        } else {
            renderTable([]); // Show "no employees found" row
            showNotification(result.message || 'Search failed', 'error');
        }
    })
    .catch(error => showNotification('An unexpected error occurred', 'error'));
    renderTable([]); // Show "no employees found" row on error
}

// Function to reset the search filters and reload all employees
// Simplified function to reset the search filters
function resetCustomer() {
    // Simply reload the page to show all employees again
    location.reload();
}


    // Load employees on page load
    window.onload = () => {
    loadEmployees();
};
    </script>
</body>
</html>
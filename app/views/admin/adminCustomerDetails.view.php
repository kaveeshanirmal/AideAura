<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Customer Details</title>
    <link rel="stylesheet" href="<?=ROOT?>/public/assets/css/adminCustomerDetails.css">
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
                <!-- Added pagination container -->
                <div id="pagination" class="pagination"></div>
            </div>
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

        // Global array to store all customers
        let allCustomers = [];
        // Pagination variables
        const rowsPerPage = 1; // Set to 1 as requested
        let currentPage = 1;

        function loadEmployees() {
            try {
                // For testing, we can use the raw PHP data if available
                if (Array.isArray(<?= json_encode($customers ?? []) ?>) && <?= json_encode($customers ?? []) ?>.length) {
                    allCustomers = <?= json_encode($customers ?? []) ?>;
                    console.log("Loaded customers from PHP:", allCustomers.length);
                    renderTable(allCustomers);
                    return;
                }
                
                // Otherwise fetch from API
                fetch('<?=ROOT?>/public/admin/customers')
                    .then(response => response.json())
                    .then(result => {
                        if (result.success) {
                            allCustomers = result.employees || [];
                            console.log("Fetched customers:", allCustomers.length);
                            renderTable(allCustomers);
                        } else {
                            showNotification('Failed to load customers', 'error');
                            renderTable([]);
                        }
                    })
                    .catch(error => {
                        console.error("Error fetching customers:", error);
                        showNotification('An unexpected error occurred', 'error');
                        renderTable([]);
                    });
            } catch (error) {
                console.error("Exception in loadEmployees:", error);
                showNotification('An unexpected error occurred', 'error');
                renderTable([]);
            }
        }

        function renderTable(customers) {
            const tableBody = document.getElementById('employeeTableBody');
            const paginationDiv = document.getElementById('pagination');
            
            console.log("Rendering table with", customers.length, "customers, page", currentPage);
            
            if (!customers || customers.length === 0) {
                tableBody.innerHTML = `
                    <tr>
                        <td colspan="10" style="text-align: center; color: #888;">
                            No customer found for the given criteria. 
                        </td>
                    </tr>
                `;
                paginationDiv.innerHTML = ''; // Clear pagination if no results
                return;
            }
            
            // Calculate page information
            const totalPages = Math.max(1, Math.ceil(customers.length / rowsPerPage));
            
            // Make sure current page is valid
            if (currentPage < 1) currentPage = 1;
            if (currentPage > totalPages) currentPage = totalPages;
            
            // Apply pagination
            const start = (currentPage - 1) * rowsPerPage;
            const end = Math.min(start + rowsPerPage, customers.length);
            const paginatedCustomers = customers.slice(start, end);
            
            console.log(`Showing customers ${start+1}-${end} of ${customers.length}`);
            
            // Generate table rows
            tableBody.innerHTML = '';
            paginatedCustomers.forEach(customer => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${customer.customerID || ''}</td>
                    <td>${customer.userID || ''}</td>
                    <td>${customer.username || ''}</td>
                    <td>${customer.firstName || ''}</td>
                    <td>${customer.lastName || ''}</td>
                    <td>${customer.role || ''}</td>
                    <td>${customer.phone || ''}</td>
                    <td>${customer.email || ''}</td>
                    <td>${customer.address || ''}</td>
                    <td>${customer.createdAt || ''}</td>
                `;
                tableBody.appendChild(row);
            });
            
            // Render pagination controls
            renderPagination(customers.length, totalPages);
        }
        
        function renderPagination(totalItems, totalPages) {
            const paginationDiv = document.getElementById('pagination');
            paginationDiv.innerHTML = '';
            
            if (totalPages <= 1) {
                // Still show page info even if only one page
                // paginationDiv.innerHTML = `<span>Page 1 of 1 (${totalItems} item${totalItems !== 1 ? 's' : ''})</span>`;
                return;
            }
            
            // Add Previous button if not on first page
            if (currentPage > 1) {
                const prevButton = document.createElement('button');
                prevButton.innerText = 'Previous';
                prevButton.onclick = () => changePage(currentPage - 1);
                paginationDiv.appendChild(prevButton);
            }
            
            // Determine which page buttons to show (show up to 5 numbered buttons)
            let startPage = Math.max(1, currentPage - 2);
            let endPage = Math.min(totalPages, startPage + 4);
            
            // Adjust if we're near the end
            if (endPage - startPage < 4 && startPage > 1) {
                startPage = Math.max(1, endPage - 4);
            }
            
            // Add first page button if not included in range
            if (startPage > 1) {
                const firstButton = document.createElement('button');
                firstButton.innerText = '1';
                firstButton.onclick = () => changePage(1);
                paginationDiv.appendChild(firstButton);
                
                // Add ellipsis if needed
                if (startPage > 2) {
                    const ellipsis = document.createElement('span');
                    ellipsis.innerText = '...';
                    ellipsis.style.margin = '0 5px';
                    paginationDiv.appendChild(ellipsis);
                }
            }
            
            // Add numbered page buttons
            for (let i = startPage; i <= endPage; i++) {
                const pageButton = document.createElement('button');
                pageButton.innerText = i;
                pageButton.className = currentPage === i ? 'active' : '';
                pageButton.onclick = () => changePage(i);
                paginationDiv.appendChild(pageButton);
            }
            
            // Add ellipsis and last page button if needed
            if (endPage < totalPages) {
                if (endPage < totalPages - 1) {
                    const ellipsis = document.createElement('span');
                    ellipsis.innerText = '...';
                    ellipsis.style.margin = '0 5px';
                    paginationDiv.appendChild(ellipsis);
                }
                
                const lastButton = document.createElement('button');
                lastButton.innerText = totalPages;
                lastButton.onclick = () => changePage(totalPages);
                paginationDiv.appendChild(lastButton);
            }
            
            // Add Next button if not on last page
            if (currentPage < totalPages) {
                const nextButton = document.createElement('button');
                nextButton.innerText = 'Next';
                nextButton.onclick = () => changePage(currentPage + 1);
                paginationDiv.appendChild(nextButton);
            }
            
            // Add page info text
            const start = ((currentPage - 1) * rowsPerPage) + 1;
            const end = Math.min(start + rowsPerPage - 1, totalItems);
            const pageInfo = document.createElement('div');
            // pageInfo.innerHTML = `<span style="display:block;margin-top:10px;">Showing ${start}-${end} of ${totalItems} item${totalItems !== 1 ? 's' : ''}</span>`;
            paginationDiv.appendChild(pageInfo);
        }
        
        function changePage(page) {
            console.log("Changing to page", page);
            if (page < 1) page = 1;
            
            const totalPages = Math.ceil(allCustomers.length / rowsPerPage);
            if (page > totalPages) page = totalPages;
            
            currentPage = page;
            renderTable(allCustomers);
        }

        function searchCustomer() {
            const customerID = document.getElementById('customerId').value.trim();
            console.log("Searching for customer ID:", customerID);
            
            if (!customerID) {
                showNotification('Please enter a customer ID', 'error');
                return;
            }
            
            // Create request body
            const filters = {
                customerID: customerID
            };
            
            // Send request to search endpoint
            fetch('<?=ROOT?>/public/admin/searchCustomers', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(filters)
            })
            .then(response => response.json())
            .then(result => {
                console.log("Search result:", result);
                currentPage = 1; // Reset to first page when searching
                
                if (result.success && result.customers && result.customers.length > 0) {
                    allCustomers = result.customers;
                    renderTable(allCustomers);
                    showNotification(`Found ${result.customers.length} customer(s)`, 'success');
                } else {
                    allCustomers = [];
                    renderTable([]);
                    showNotification(result.message || 'No customers found', 'error');
                }
            })
            .catch(error => {
                console.error("Search error:", error);
                showNotification('An unexpected error occurred', 'error');
                renderTable([]);
            });
        }

        function resetCustomer() {
            document.getElementById('customerId').value = '';
            currentPage = 1; // Reset to first page
            loadEmployees(); // Reload all customers
            showNotification('Search reset', 'success');
        }

        // Initialize on page load
        window.onload = () => {
            console.log("Page loaded, initializing...");
            loadEmployees();
        };
    </script>
</body>
</html>
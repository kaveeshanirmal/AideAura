<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - payment rates</title>
    <link rel="stylesheet" href="<?=ROOT?>/public/assets/css/adminPayrate.css">
    <script src="<?=ROOT?>/public/assets/js/admin/adminPayrate.js"></script>
</head>
<body>
<input type="hidden" id="rootUrl" value="<?=ROOT?>">

<!-- Notification container -->
<div id="notification" class="notification hidden"></div>
<div class="dashboard-container">
    <!-- Sidebar -->
    <?php include(ROOT_PATH . '/app/views/components/employeeNavbar.view.php'); ?>
    <!-- Main Content -->
    <main class="main-content">
        <!-- Table Section -->
        <div class="table-container">
            <table class="rates-table">
                <thead>
                    <tr>
                        <th>ServiceID</th>
                        <th>Service Type</th>
                        <th>Base Price</th>
                        <th>Base Hours</th>
                        <th>Created At</th>
                        <th>Update</th>
                    </tr>
                </thead>
                <tbody id="ratesTableBody">
                    <!-- Table body will be populated by JavaScript -->
                </tbody>
            </table>
            
            <!-- Pagination -->
            <div class="pagination">
                <div class="per-page">
                    <select id="perPageSelect" onchange="changeItemsPerPage()">
                        <option value="5">5</option>
                        <option value="10" selected>10</option>
                        <option value="20">20</option>
                        <option value="50">50</option>
                    </select>
                    <span>per page</span>
                </div>
                <div class="page-info">
                    <span>Page</span>
                    <select id="pageSelect" onchange="changePage()">
                        <!-- Will be populated with JavaScript -->
                    </select>
                    <span>of <span id="totalPages">1</span> pages</span>
                    <button id="prevBtn" class="prev-btn" onclick="previousPage()">&lt;</button>
                    <button id="nextBtn" class="next-btn" onclick="nextPage()">&gt;</button>
                </div>
            </div>
        </div>
    </main>
</div>

<!-- Update Modal -->
<div id="updateModal" class="modal">
    <div class="modal-content">
        <span class="close-btn" onclick="closeUpdateModal()">&times;</span>
        <h2>Update Service Rate</h2>
        <form id="updateRateForm" onsubmit="event.preventDefault(); updateEmployee();">
            <input type="hidden" id="serviceIdInput" name="ServiceID">
            <div class="form-group">
                <label for="basePriceInput">Base Price</label>
                <input type="number" id="basePriceInput" name="BasePrice" step="0.01" required>
            </div>
            <div class="form-group">
                <label for="baseHoursInput">Base Hours</label>
                <input type="number" id="baseHoursInput" name="BaseHours" step="0.25" required>
            </div>
            <button type="submit" class="submit-btn">Update</button>
        </form>
    </div>
</div>

<script>
// Convert object with numeric keys to proper array
let rawRates = <?= isset($rates) ? json_encode($rates) : '{}' ?>;
// Convert object to array
let allRates = Object.values(rawRates);

console.log("Raw rates:", rawRates);
console.log("Converted rates array:", allRates);

let currentPage = 1;
let itemsPerPage = 10;
let totalPages = Math.ceil(allRates.length / itemsPerPage);

// Initialize pagination on page load
document.addEventListener('DOMContentLoaded', function() {
    console.log("Total rates:", allRates.length);
    initializePagination();
});

function initializePagination() {
    updateTotalPages();
    populatePageSelector();
    renderCurrentPage();
    updatePaginationControls();
}

function updateTotalPages() {
    totalPages = Math.max(1, Math.ceil(allRates.length / itemsPerPage));
    document.getElementById('totalPages').textContent = totalPages;
}

function populatePageSelector() {
    const pageSelect = document.getElementById('pageSelect');
    pageSelect.innerHTML = '';
    
    for (let i = 1; i <= totalPages; i++) {
        const option = document.createElement('option');
        option.value = i;
        option.textContent = i;
        if (i === currentPage) {
            option.selected = true;
        }
        pageSelect.appendChild(option);
    }
}

function renderCurrentPage() {
    const tableBody = document.getElementById('ratesTableBody');
    tableBody.innerHTML = '';
    
    if (allRates.length === 0) {
        const row = document.createElement('tr');
        row.innerHTML = '<td colspan="6" class="no-data">No payment rates found</td>';
        tableBody.appendChild(row);
        return;
    }
    
    const startIndex = (currentPage - 1) * itemsPerPage;
    const endIndex = Math.min(startIndex + itemsPerPage, allRates.length);
    
    for (let i = startIndex; i < endIndex; i++) {
        const rate = allRates[i];
        if (!rate) continue; // Skip undefined items
        
        const row = document.createElement('tr');
        
        row.innerHTML = `
            <td>${escapeHtml(rate.ServiceID)}</td>
            <td>${escapeHtml(rate.ServiceType)}</td>
            <td>${escapeHtml(rate.BasePrice)}</td>
            <td>${escapeHtml(rate.BaseHours)}</td>
            <td>${escapeHtml(rate.CreatedDate)}</td>
            <td>
                <button class="update-btn" onclick="showUpdateModal(this)" 
                    data-service-id="${escapeHtml(rate.ServiceID)}"
                    data-base-price="${escapeHtml(rate.BasePrice)}"
                    data-base-hours="${escapeHtml(rate.BaseHours)}">update</button>
            </td>
        `;
        
        tableBody.appendChild(row);
    }
}

function updatePaginationControls() {
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    
    prevBtn.disabled = currentPage === 1;
    nextBtn.disabled = currentPage === totalPages;
}

function changePage() {
    const pageSelect = document.getElementById('pageSelect');
    currentPage = parseInt(pageSelect.value);
    renderCurrentPage();
    updatePaginationControls();
}

function previousPage() {
    if (currentPage > 1) {
        currentPage--;
        document.getElementById('pageSelect').value = currentPage;
        renderCurrentPage();
        updatePaginationControls();
    }
}

function nextPage() {
    if (currentPage < totalPages) {
        currentPage++;
        document.getElementById('pageSelect').value = currentPage;
        renderCurrentPage();
        updatePaginationControls();
    }
}

function changeItemsPerPage() {
    const perPageSelect = document.getElementById('perPageSelect');
    itemsPerPage = parseInt(perPageSelect.value);
    currentPage = 1; // Reset to first page when changing items per page
    
    initializePagination();
}

// Helper function to safely escape HTML
function escapeHtml(unsafe) {
    if (unsafe === null || unsafe === undefined) return '';
    return String(unsafe)
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;");
}

// Update modal functionality
function showUpdateModal(button) {
    const serviceId = button.getAttribute('data-service-id');
    const basePrice = button.getAttribute('data-base-price');
    const baseHours = button.getAttribute('data-base-hours');
    
    document.getElementById('serviceIdInput').value = serviceId;
    document.getElementById('basePriceInput').value = basePrice;
    document.getElementById('baseHoursInput').value = baseHours;
    
    document.getElementById('updateModal').style.display = 'block';
}

function closeUpdateModal() {
    document.getElementById('updateModal').style.display = 'none';
}

function updateEmployee() {
    const serviceId = document.getElementById('serviceIdInput').value;
    const basePrice = document.getElementById('basePriceInput').value;
    const baseHours = document.getElementById('baseHoursInput').value;
    
    // Updated to match your controller's expected property names
    const data = {
        ServiceID: serviceId,
        BasePrice: basePrice,
        BaseHours: baseHours
    };
    
    const rootUrl = document.getElementById('rootUrl').value;
    
    // Make sure this URL matches your controller method
    fetch(`${rootUrl}/public/Admin/updatePaymentRates`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(result => {
        if (result.success) {
            showNotification('Rate updated successfully', 'success');
            
            // Update the data in our local array
            for (let i = 0; i < allRates.length; i++) {
                if (allRates[i] && allRates[i].ServiceID == serviceId) {
                    allRates[i].BasePrice = basePrice;
                    allRates[i].BaseHours = baseHours;
                    break;
                }
            }
            
            // Re-render the current page
            renderCurrentPage();
            closeUpdateModal();
        } else {
            showNotification(result.message || 'Update failed', 'error');
        }
    })
    .catch(error => {
        showNotification('An unexpected error occurred', 'error');
        console.error('Error:', error);
    });
}

// Notification function
function showNotification(message, type) {
    const notification = document.getElementById('notification');
    notification.textContent = message;
    notification.className = `notification ${type} show`;
    setTimeout(() => notification.className = 'notification hidden', 3000);
}
</script>
</body>
</html>
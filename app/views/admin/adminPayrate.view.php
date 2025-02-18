<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - payment rates</title>
    <link rel="stylesheet" href="<?=ROOT?>/public/assets/css/adminPayrate.css">
</head>
<body>

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
                <tbody>
                    <?php foreach($rates as $rate): ?>
                        <tr>
                            <td><?= htmlspecialchars($rate->ServiceID) ?></td>
                            <td><?= htmlspecialchars($rate->ServiceType) ?></td>
                            <td><?= htmlspecialchars($rate->BasePrice) ?></td>
                            <td><?= htmlspecialchars($rate->BaseHours) ?></td>
                            <td><?= htmlspecialchars($rate->CreatedDate) ?></td>
                            <td>
                                <button class="update-btn" onclick="showUpdateModal(this)">update</button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                </tbody>
            </table>

            <!-- Pagination -->
            <div class="pagination">
                <div class="per-page">
                    <select>
                        <option value="10">10</option>
                        <option value="20">20</option>
                        <option value="30">30</option>
                    </select>
                    <span>per page</span>
                </div>
                <div class="page-info">
                    <select>
                        <option value="1">1</option>
                    </select>
                    <span>of 1 pages</span>
                    <button class="prev-btn" disabled>&lt;</button>
                    <button class="next-btn" disabled>&gt;</button>
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
                <input type="hidden" id="serviceIdInput" name="serviceId">
                <div class="form-group">
                    <label for="basePriceInput">Base Price</label>
                    <input type="number" id="basePriceInput" name="basePrice" step="0.01" required>
                </div>
                <div class="form-group">
                    <label for="baseHoursInput">Base Hours</label>
                    <input type="number" id="baseHoursInput" name="baseHours" step="0.25" required>
                </div>
                <button type="submit" class="submit-btn">Update</button>
            </form>
        </div>
    </div>
</div>

<script>

//Notification Functionality
const notification = document.getElementById('notification');
const showNotification = (message, type) => {
    notification.textContent = message;
    notification.className = `notification ${type} show`;
    setTimeout(() => notification.className = 'notification hidden',2000);
};

function showUpdateModal(button) {
    const row = button.closest('tr');
    const serviceID = row.cells[0].textContent;
    const basePrice = row.cells[2].textContent;
    const baseHours = row.cells[3].textContent;

    document.getElementById('serviceIdInput').value = serviceID;
    document.getElementById('basePriceInput').value = basePrice;
    document.getElementById('baseHoursInput').value = baseHours;

    document.getElementById('updateModal').style.display = 'block';
}

function closeUpdateModal() {
    document.getElementById('updateModal').style.display = 'none';
}

function updateEmployee() {
    // Get input elements
    const serviceIdInput = document.getElementById('serviceIdInput');
    const basePriceInput = document.getElementById('basePriceInput');
    const baseHoursInput = document.getElementById('baseHoursInput');
    
    // Validate inputs exist
    if (!serviceIdInput || !basePriceInput || !baseHoursInput) {
        showNotification("Error: One or more input fields are missing.", 'error');
        return;
    }
    
    // Validate input values
    const ServiceID = serviceIdInput.value.trim();
    const BasePrice = parseFloat(document.getElementById('basePriceInput').value).toFixed(2);
    const BaseHours = parseFloat(document.getElementById('baseHoursInput').value).toFixed(2);
    
    if (!ServiceID || isNaN(BasePrice) || isNaN(BaseHours)) {
        showNotification("Please fill in all fields with valid numbers.", 'error');
        return;
    }
    
    const data = { ServiceID, BasePrice, BaseHours };
    
    // Show loading state
    showNotification('Updating payment rates...', 'info');
    
    fetch('<?=ROOT?>/public/Admin/updatePaymentRates', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify(data)
    })
    .then(async response => {
        const result = await response.json();
        if (!response.ok) {
            throw new Error(result.message || 'Server error');
        }
        return result;
    })
    .then(result => {
        if (result.success) {
            showNotification('Payment rate updated successfully', 'success');
            setTimeout(() => location.reload(), 2000);
            closeUpdateModal();
        } else {
            showNotification('Payment Rates update failed', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('An unexpected error occurred', 'error');
    });
}



</script>
</body>
</html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - payment rates</title>
    <link rel="stylesheet" href="<?=ROOT?>/public/assets/css/adminPayrate.css">
</head>
<body>

<!-- Navbar -->
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
                        <tr>
                            <td>1</td>
                            <td>home-style-food</td>
                            <td>500.00</td>
                            <td>1.00</td>
                            <td>2024-11-05 09:13:08</td>
                            <td>
                                <button class="update-btn">update</button>
                            </td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>dishwashing</td>
                            <td>100.00</td>
                            <td>0.25</td>
                            <td>2024-11-02 07:15:05</td>
                            <td>
                                <button class="update-btn">update</button>
                            </td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>24h cook</td>
                            <td>20000.00</td>
                            <td>24.00</td>
                            <td>2024-11-03 08:10:00</td>
                            <td>
                                <button class="update-btn">update</button>
                            </td>
                        </tr>
                        <tr>
                            <td>4</td>
                            <td>indoor cleaner</td>
                            <td>250.00</td>
                            <td>1.00</td>
                            <td>2024-11-08 10:00:03</td>
                            <td>
                                <button class="update-btn">update</button>
                            </td>
                        </tr>
                        <tr>
                            <td>5</td>
                            <td>outdoor cleaner</td>
                            <td>200.00</td>
                            <td>1.00</td>
                            <td>2024-11-03 11:14:20</td>
                            <td>
                                <button class="update-btn">update</button>
                            </td>
                        </tr>
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
            <span class="close-btn">&times;</span>
            <h2>Update Service Rate</h2>
            <form id="updateRateForm">
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
document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('updateModal');
    const updateButtons = document.querySelectorAll('.update-btn');
    const closeBtn = document.querySelector('.close-btn');
    const updateForm = document.getElementById('updateRateForm');
    const serviceIdInput = document.getElementById('serviceIdInput');
    const basePriceInput = document.getElementById('basePriceInput');
    const baseHoursInput = document.getElementById('baseHoursInput');

    // Open modal when update button is clicked
    updateButtons.forEach(button => {
        button.addEventListener('click', (e) => {
            const serviceId = e.target.getAttribute('data-service-id');
            const row = e.target.closest('tr');
            
            // Populate current values
            const basePrice = row.querySelector('td:nth-child(3)').textContent;
            const baseHours = row.querySelector('td:nth-child(4)').textContent;

            serviceIdInput.value = serviceId;
            basePriceInput.value = basePrice;
            baseHoursInput.value = baseHours;

            modal.style.display = 'block';
        });
    });

    // Close modal when close button is clicked
    closeBtn.addEventListener('click', () => {
        modal.style.display = 'none';
    });

    // Close modal when clicking outside of it
    window.addEventListener('click', (e) => {
        if (e.target === modal) {
            modal.style.display = 'none';
        }
    });

    // Handle form submission
    updateForm.addEventListener('submit', (e) => {
        e.preventDefault();
        
        // Here you would typically send an AJAX request to update the service rate
        // For now, we'll just update the table and close the modal
        const row = document.querySelector(`tr[data-service-id="${serviceIdInput.value}"]`);
        row.querySelector('td:nth-child(3)').textContent = basePriceInput.value;
        row.querySelector('td:nth-child(4)').textContent = baseHoursInput.value;

        modal.style.display = 'none';
    });
});
</script>
</body>
</html>
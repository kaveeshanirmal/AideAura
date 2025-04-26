<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Payment Rates</title>
    <link rel="stylesheet" href="<?=ROOT?>/public/assets/css/adminPriceDetails.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

<div id="notification" class="notification hidden"></div>

<div class="dashboard-container">
    <?php include(ROOT_PATH . '/app/views/components/employeeNavbar.view.php'); ?>

    <div class="main-content">
        <div class="table-container">
            <table class="employee-table">
                <thead>
                    <tr>
                        <th>Detail Name</th>
                        <th>Description</th>
                        <th>Role</th>
                        <th>Category Name</th>
                        <th>Category Description</th>
                        <th>Category Display Name</th>
                        <th>Updated At</th>
                        <th>Price</th>
                        <th>Update</th>
                    </tr>
                </thead>
                <tbody id="employeeTableBody"></tbody>
            </table>

            <div id="pagination" class="pagination"></div>
        </div>
    </div>
</div>

<!-- Update Modal -->
<div id="updateModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeUpdateModal()">&times;</span>
        <h2>Update Price Details</h2>
        <form id="updatePriceDetailForm" onsubmit="event.preventDefault(); updatePriceDetail();">
            <input type="hidden" id="updatedetailID">
            <div class="form-item">
                <label>Detail Name:</label>
                <input type="text" id="updatedetailName">
            </div>
            <div class="form-item">
                <label>Price:</label>
                <input type="text" id="updateprice">
            </div>
            <div class="form-item">
                <label>Description:</label>
                <input type="text" id="updatedescription">
            </div>
            <button type="submit">Update</button>
        </form>
    </div>
</div>

<script>
    const priceData = <?= isset($priceData) ? json_encode($priceData) : '[]' ?>;
    const tableBody = document.getElementById('employeeTableBody');
    const pagination = document.getElementById('pagination');
    const notification = document.getElementById('notification');

    const rowsPerPage = 5;
    let currentPage = 1;

    function showNotification(message, type) {
        notification.textContent = message;
        notification.className = `notification ${type} show`;
        setTimeout(() => notification.className = 'notification hidden', 2000);
    }

    function renderTable(data) {
        tableBody.innerHTML = '';

        if (data.length === 0) {
            tableBody.innerHTML = `<tr><td colspan="9" style="text-align:center;">No prices data found.</td></tr>`;
            return;
        }

        const start = (currentPage - 1) * rowsPerPage;
        const paginatedItems = data.slice(start, start + rowsPerPage);

        paginatedItems.forEach(price => {
            tableBody.innerHTML += `
                <tr data-detailID="${price.detailID}" data-detailName="${price.detailName}" data-price="${price.price}" data-description="${price.description}">
                    <td>${price.detailName}</td>
                    <td>${price.description}</td>
                    <td>${price.roleName}</td>
                    <td>${price.categoryName}</td>
                    <td>${price.categoryDescription}</td>
                    <td>${price.categoryDisplayName}</td>
                    <td>${price.updatedAt}</td>
                    <td>${price.price}</td>
                    <td><button class="update-btn" onclick="showUpdateModal(this)"><i class="fas fa-sync-alt"></i></button></td>
                </tr>`;
        });

        renderPagination(data.length);
    }

    function renderPagination(totalItems) {
        pagination.innerHTML = '';

        const pageCount = Math.ceil(totalItems / rowsPerPage);

        if (pageCount <= 1) return;

        if (currentPage > 1) {
            pagination.innerHTML += `<button onclick="changePage(${currentPage - 1})">Previous</button>`;
        }

        for (let i = 1; i <= pageCount; i++) {
            pagination.innerHTML += `<button onclick="changePage(${i})" class="${currentPage === i ? 'active' : ''}">${i}</button>`;
        }

        if (currentPage < pageCount) {
            pagination.innerHTML += `<button onclick="changePage(${currentPage + 1})">Next</button>`;
        }
    }

    function changePage(page) {
        currentPage = page;
        renderTable(priceData);
    }

    function showUpdateModal(button) {
        const row = button.closest('tr');
        document.getElementById('updatedetailID').value = row.getAttribute('data-detailID');
        document.getElementById('updatedetailName').value = row.getAttribute('data-detailName');
        document.getElementById('updateprice').value = row.getAttribute('data-price');
        document.getElementById('updatedescription').value = row.getAttribute('data-description');
        document.getElementById('updateModal').style.display = 'block';
    }

    function closeUpdateModal() {
        document.getElementById('updateModal').style.display = 'none';
    }

    function updatePriceDetail() {
        const data = {
            detailID: document.getElementById('updatedetailID').value,
            detailName: document.getElementById('updatedetailName').value,
            price: document.getElementById('updateprice').value,
            description: document.getElementById('updatedescription').value,
        };

        fetch('<?=ROOT?>/public/Admin/updatePriceDetails', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data),
        })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                closeUpdateModal();
                showNotification('Price updated successfully', 'success');
                setTimeout(() => location.reload(), 2000);
            } else {
                showNotification('Update failed', 'error');
            }
        })
        .catch(() => {
            showNotification('An unexpected error occurred', 'error');
        });
    }

    // Initial table render
    renderTable(priceData);
</script>

</body>
</html>

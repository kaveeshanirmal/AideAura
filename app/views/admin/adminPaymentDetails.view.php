<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Admin - Payment Details</title>
  <link rel="stylesheet" href="<?=ROOT?>/public/assets/css/adminPaymentDetails.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

</head>
<body>
  <!-- Notification container -->
  <div id="notification" class="notification hidden"></div>

  <div class="dashboard-container">
    <?php include(ROOT_PATH . '/app/views/components/employeeNavbar.view.php'); ?>
    
    <div class="main-content">
      <div class="table-container">
        <table class="role-table">
          <thead>
            <tr>
              <th>Payment ID</th>
              <th>Booking ID</th>
              <th>Customer</th>
              <th>Worker</th>
              <th>Amount (LKR)</th>
              <th>Method</th>
              <th>Status</th>
              <th>Payment Date</th>
              <th>Transaction ID</th>
              <th>Reference</th>
            </tr>
          </thead>
          <tbody id="paymentTableBody">
            <!-- Rows filled by JavaScript -->
          </tbody>
        </table>

        <!-- Pagination Section -->
        <div class="pagination" id="pagination"></div>
        <!-- End Pagination Section -->
      </div>
    </div>
  </div>

  <script>
    const paymentDetails = <?= isset($paymentDetails) ? json_encode($paymentDetails) : '[]' ?>;
    const tableBody = document.getElementById('paymentTableBody');
    const pagination = document.getElementById('pagination');
    
    let currentPage = 1;
    const rowsPerPage = 5;  // Change this number to adjust rows per page

    function showNotification(message, type) {
      const notification = document.getElementById('notification');
      notification.textContent = message;
      notification.className = `notification ${type} show`;
      setTimeout(() => notification.className = 'notification hidden', 2000);
    }

    function renderTable(data) {
      tableBody.innerHTML = '';

      if (data.length === 0) {
        tableBody.innerHTML = `<tr><td colspan="10" style="text-align:center;">No Payment Details found.</td></tr>`;
        return;
      }

      const start = (currentPage - 1) * rowsPerPage;
      const paginatedItems = data.slice(start, start + rowsPerPage);

      paginatedItems.forEach(payment => {
        tableBody.innerHTML += `
          <tr>
            <td>${payment.paymentID}</td>
            <td>${payment.bookingID}</td>
            <td>${payment.customerName}</td>
            <td>${payment.workerName}</td>
            <td>${payment.amount}</td>
            <td>${payment.paymentMethod}</td>
            <td>${payment.paymentStatus}</td>
            <td>${payment.paymentDate}</td>
            <td>${payment.transactionID}</td>
            <td>${payment.merchantReference}</td>
          </tr>
        `;
      });

      // ➡️ Add empty rows if there are fewer rows
      const emptyRows = rowsPerPage - paginatedItems.length;
      for (let i = 0; i < emptyRows; i++) {
        tableBody.innerHTML += `
          <tr class="empty-row">
            <td colspan="10">&nbsp;</td>
          </tr>
        `;
      }

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
      renderTable(paymentDetails);
    }

    // Initial load
    renderTable(paymentDetails);
  </script>
</body>
</html>

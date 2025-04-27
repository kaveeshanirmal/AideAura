<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>fm - Cancelled Bookings</title>
  <link rel="stylesheet" href="<?=ROOT?>/public/assets/css/cancelledBookings.css">
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
            <label for="customerId">Booking ID:</label>
            <input type="text" id="bookingId" class="id-input">
          </div>
          <div class="input-group">
            <label for="customerId">Customer ID:</label>
            <input type="text" id="customerId" class="id-input">
            </div>
            <div class="input-group">
            <label for="customerId">Worker ID:</label>
            <input type="text" id="workerId" class="id-input">
            </div>
            <div class="search-btn-container">
            <button class="search-btn" onclick="searchBooking()">Search</button>
            <button class="search-btn" onclick="resetCustomer()">Reset</button>
          </div>
        </div>
        </div>
  

      <div class="table-container">
        <table class="employee-table">
          <thead>
            <tr>
              <th>Booking ID</th>
              <th>Customer ID</th>
              <th>Worker ID</th>
              <th>Booking Date</th>
              <th>Start Time</th>
              <th>End Time</th>
              <th>Status</th>
              <th>Total Cost</th>
              <th>Cancelled At</th>
              <th>Service Type</th>
            </tr>
          </thead>

          <tbody id="employeeTableBody">
            <?php if (empty($cancelledBookings)): ?>
              <tr>
                <td colspan="10" style="text-align: center; font-style: italic;">
                  No cancelled bookings found.
                </td>
              </tr>
            <?php else: ?>
              <?php foreach ($cancelledBookings as $booking): ?>
                <tr>
                  <td><?= htmlspecialchars($booking->bookingID) ?></td>
                  <td><?= htmlspecialchars($booking->customerID) ?></td>
                  <td><?= htmlspecialchars($booking->workerID) ?></td>
                  <td><?= htmlspecialchars($booking->bookingDate) ?></td>
                  <td><?= htmlspecialchars($booking->startTime) ?></td>
                  <td><?= htmlspecialchars($booking->endTime) ?></td>
                  <td><?= htmlspecialchars($booking->status) ?></td>
                  <td><?= htmlspecialchars($booking->totalCost) ?></td>
                  <td><?= htmlspecialchars($booking->cancelledAt) ?></td>
                  <td><?= htmlspecialchars($booking->serviceType) ?></td>
                </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>

        <!-- Pagination -->
        <div id="pagination" class="pagination"></div>
      </div>
    </div>
  </div>

  <script>
    // Notification functionality
    const notification = document.getElementById('notification');
    const showNotification = (message, type) => {
      notification.textContent = message;
      notification.className = `notification ${type} show`;
      setTimeout(() => notification.className = 'notification hidden', 2000);
    };

    // Global Variables
    let allCancelledBookings = <?= json_encode($cancelledBookings ?? []) ?>;
    const rowsPerPage = 5;
    let currentPage = 1;

    document.addEventListener("DOMContentLoaded", loadcancelledBookings);

    function loadcancelledBookings() {
      if (Array.isArray(allCancelledBookings) && allCancelledBookings.length > 0) {
        renderTable(allCancelledBookings);
      } else {
        fetch('<?=ROOT?>/public/FinanaceManager/cancelledBookings')
          .then(response => response.json())
          .then(result => {
            if (result.success) {
              allCancelledBookings = result.allCancelledBookings || [];
              renderTable(allCancelledBookings);
            } else {
              showNotification('Failed to load cancelled bookings', 'error');
              renderTable([]);
            }
          })
          .catch(error => {
            console.error("Fetch error:", error);
            showNotification('An unexpected error occurred', 'error');
            renderTable([]);
          });
      }
    }

    function renderTable(bookings) {
      const tableBody = document.getElementById('employeeTableBody');
      const paginationDiv = document.getElementById('pagination');

      if (!bookings.length) {
        tableBody.innerHTML = `
          <tr>
            <td colspan="10" style="text-align: center; font-style: italic;">No cancelled bookings found.</td>
          </tr>
        `;
        paginationDiv.innerHTML = '';
        return;
      }

      const totalPages = Math.ceil(bookings.length / rowsPerPage);
      if (currentPage < 1) currentPage = 1;
      if (currentPage > totalPages) currentPage = totalPages;

      const start = (currentPage - 1) * rowsPerPage;
      const paginatedData = bookings.slice(start, start + rowsPerPage);

      tableBody.innerHTML = '';
      paginatedData.forEach(booking => {
        const row = document.createElement('tr');
        row.innerHTML = `
          <td>${booking.bookingID || ''}</td>
          <td>${booking.customerID || ''}</td>
          <td>${booking.workerID || ''}</td>
          <td>${booking.bookingDate || ''}</td>
          <td>${booking.startTime || ''}</td>
          <td>${booking.endTime || ''}</td>
          <td>${booking.status || ''}</td>
          <td>${booking.totalCost || ''}</td>
          <td>${booking.cancelledAt || ''}</td>
          <td>${booking.serviceType || ''}</td>
        `;
        tableBody.appendChild(row);
      });

      renderPagination(bookings.length, totalPages);
    }

    function renderPagination(totalItems, totalPages) {
      const paginationDiv = document.getElementById('pagination');
      paginationDiv.innerHTML = '';

      if (totalPages <= 1) return;

      if (currentPage > 1) {
        const prev = document.createElement('button');
        prev.innerText = 'Previous';
        prev.onclick = () => changePage(currentPage - 1);
        paginationDiv.appendChild(prev);
      }

      for (let i = 1; i <= totalPages; i++) {
        const pageBtn = document.createElement('button');
        pageBtn.innerText = i;
        pageBtn.className = (i === currentPage) ? 'active' : '';
        pageBtn.onclick = () => changePage(i);
        paginationDiv.appendChild(pageBtn);
      }

      if (currentPage < totalPages) {
        const next = document.createElement('button');
        next.innerText = 'Next';
        next.onclick = () => changePage(currentPage + 1);
        paginationDiv.appendChild(next);
      }
    }

    function changePage(page) {
      currentPage = page;
      renderTable(allCancelledBookings);
    }

    function searchBooking() {
  const bookingIdInput = document.getElementById('bookingId').value.trim();
  const customerIdInput = document.getElementById('customerId').value.trim();
  const workerIdInput = document.getElementById('workerId').value.trim();

  if (!bookingIdInput && !customerIdInput && !workerIdInput) {
    showNotification('Please enter at least one field to search.', 'error');
    renderTable(allCancelledBookings);
    return;
  }

  const filtered = allCancelledBookings.filter(b => {
    const matchesBookingId = bookingIdInput ? (b.bookingID || '').toString().includes(bookingIdInput) : true;
    const matchesCustomerId = customerIdInput ? (b.customerID || '').toString().includes(customerIdInput) : true;
    const matchesWorkerId = workerIdInput ? (b.workerID || '').toString().includes(workerIdInput) : true;
    return matchesBookingId && matchesCustomerId && matchesWorkerId;
  });

  currentPage = 1;
  renderTable(filtered);

  if (filtered.length === 0) {
    showNotification('No matching cancelled bookings found.', 'error');
  }
}

    function resetCustomer() {
      document.getElementById('bookingId').value = '';
      document.getElementById('customerId').value = '';
      document.getElementById('workerId').value = '';
      currentPage = 1;
      renderTable(allCancelledBookings);
    }
  </script>
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OPM Worker Complaints</title>
    <link rel="stylesheet" href="<?=ROOT?>/public/assets/css/opmBookingDetails.css">
</head>
<body>
  <div id="notification" class="notification hidden"></div>

  <div class="dashboard-container">
    <?php include(ROOT_PATH . '/app/views/components/employeeNavbar.view.php'); ?>
    
    <div class="main-content">

      <div class="employee-controls">
        <div class="search-filters">
          <div class="input-group">
            <label for="employeeId">Booking ID:</label>
            <input type="text" id="bookingId" class="id-input">
          </div>
          <div class="input-group">
            <label for="employeeId">Customer ID:</label>
            <input type="text" id="customerId" class="id-input">
          </div>
          <div class="input-group">
            <label for="employeeId">Worker ID:</label>
            <input type="text" id="workerId" class="id-input">
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
        <table class="role-table">
          <thead>
            <tr>
              <th>Booking ID</th>
              <th>Booking Date</th>
              <th>Customer</th>
              <th>Worker</th>
              <th>Service Type</th>
              <th>Status</th>
              <th>Time</th>
              <th>Location</th>
              <th>Num People</th>
              <th>Num Meals</th>
              <th>Diet</th>
              <th>Addons</th>
              <th>Base Price</th>
              <th>Addon Price</th>
              <th>Total Cost</th>
            </tr>
          </thead>

          <tbody id="employeeTableBody">
            <?php if (empty($bookingDetails)): ?>
              <tr>
                <td colspan="15" style="text-align: center; font-style: italic;">
                  No Booking Details found.
                </td>
              </tr>
            <?php endif; ?>
            <!-- Table content will be loaded dynamically on page load -->
          </tbody>
        </table>
        
        <!-- Add pagination container -->
        <div id="pagination" class="pagination"></div>
      </div>
    </div>
  </div>

  <script>
    const notification = document.getElementById('notification');
    const showNotification = (message, type) => {
      notification.textContent = message;
      notification.className = `notification ${type} show`;
      setTimeout(() => notification.className = 'notification hidden', 2000);
    };

    const initialBookings = <?= json_encode($bookingDetails) ?>;
    
    // Pagination variables
    const rowsPerPage = 2;
    let currentPage = 1;
    let currentData = initialBookings || [];

    function searchCustomer() {
      const customerID = document.getElementById('customerId').value.trim();
      const workerID = document.getElementById('workerId').value.trim();
      const bookingID = document.getElementById('bookingId').value.trim();

      fetch('<?=ROOT?>/public/opManager/searchBookingDetails', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ customerID, workerID, bookingID })
      })
      .then(res => res.json())
      .then(result => {
        if (result.success) {
          currentPage = 1; // Reset to first page when searching
          currentData = result.bookingDetails;
          renderTable(result.bookingDetails);
        } else {
          currentData = [];
          renderTable([]);
          showNotification(result.message || 'Search failed', 'error');
        }
      })
      .catch(() => {
        currentData = [];
        renderTable([]);
        showNotification('An unexpected error occurred', 'error');
      });
    }

    function resetCustomer() {
      location.reload();
    }

    function renderTable(data) {
      const tbody = document.getElementById('employeeTableBody');
      tbody.innerHTML = '';

      if (!data || !data.length) {
        tbody.innerHTML = `<tr><td colspan="15" style="text-align: center; font-style: italic;">No Booking Details found.</td></tr>`;
        // Clear pagination if no data
        renderPagination(0);
        return;
      }

      // Group data by bookingID
      const groupedBookings = {};
      data.forEach(detail => {
        const bookingID = detail.bookingID;
        if (!groupedBookings[bookingID]) {
          groupedBookings[bookingID] = {
            info: detail,
            details: {}
          };
        }
        groupedBookings[bookingID].details[detail.detailType] = detail.detailValue;
      });

      // Convert to array for pagination
      const bookingsArray = Object.values(groupedBookings);
      
      // Apply pagination
      const start = (currentPage - 1) * rowsPerPage;
      const paginatedBookings = bookingsArray.slice(start, start + rowsPerPage);

      // Render each row
      paginatedBookings.forEach(({ info, details }) => {
        const row = document.createElement('tr');
        row.innerHTML = `
          <td>${info.bookingID}</td>
          <td>${info.bookingDate}</td>
          <td>${info.customerName}</td>
          <td>${info.workerName}</td>
          <td>${info.serviceType}</td>
          <td>${info.status}</td>
          <td>${info.startTime} - ${info.endTime}</td>
          <td>${info.location}</td>
          <td>${details['num_people'] ?? '-'}</td>
          <td>${parseDetailValue(details['num_meals'])}</td>
          <td>${details['diet'] ?? '-'}</td>
          <td>${parseDetailValue(details['addons'])}</td>
          <td>${details['base_price'] ?? '-'}</td>
          <td>${details['addon_price'] ?? '-'}</td>
          <td>${info.totalCost}</td>
        `;
        tbody.appendChild(row);
      });
      
      // Update pagination
      renderPagination(bookingsArray.length);
    }
    
    // Helper function to safely parse JSON values
    function parseDetailValue(value) {
      if (!value) return '-';
      try {
        const parsed = JSON.parse(value);
        return Array.isArray(parsed) ? parsed.join(', ') : value;
      } catch (e) {
        return value;
      }
    }
    
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

    window.onload = () => {
      // Use the data from PHP first, then call renderTable
      if (initialBookings && initialBookings.length) {
        renderTable(initialBookings);
      } else {
        loadBookings();
      }
    };
    
    function loadBookings() {
      try {
        fetch('<?=ROOT?>/public/opManager/bookingDetails')
          .then(response => response.json())
          .then(result => {
            if (result.success) {
              currentData = result.bookingDetails;
              renderTable(result.bookingDetails);
            } else {
              showNotification('Failed to load booking Details.', 'error');
            }
          });
      } catch (error) {
        showNotification('An unexpected error occurred', 'error');
      }
    }
  </script>
</body>
</html>
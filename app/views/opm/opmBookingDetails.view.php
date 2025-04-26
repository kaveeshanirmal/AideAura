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
                <td colspan="18" style="text-align: center; font-style: italic;">
                  No Booking Details found.
                </td>
              </tr>
            <?php else: ?>
              <?php
                $groupedBookings = [];
                foreach ($bookingDetails as $detail) {
                  $bookingID = $detail->bookingID;
                  if (!isset($groupedBookings[$bookingID])) {
                    $groupedBookings[$bookingID] = [
                      'info' => $detail,
                      'details' => []
                    ];
                  }
                  $groupedBookings[$bookingID]['details'][$detail->detailType] = $detail->detailValue;
                }
              ?>

              <?php foreach ($groupedBookings as $booking): ?>
                <?php
                  $info = $booking['info'];
                  $details = $booking['details'];
                ?>
                <tr>
                  <td><?= htmlspecialchars($info->bookingID) ?></td>
                  <td><?= htmlspecialchars($info->bookingDate) ?></td>
                  <td><?= htmlspecialchars($info->customerName) ?></td>
                  <td><?= htmlspecialchars($info->workerName) ?></td>
                  <td><?= htmlspecialchars($info->serviceType) ?></td>
                  <td><?= htmlspecialchars($info->status) ?></td>
                  <td><?= htmlspecialchars($info->startTime . ' - ' . $info->endTime) ?></td>
                  <td><?= htmlspecialchars($info->location) ?></td>
                  <td><?= htmlspecialchars($details['num_people'] ?? '-') ?></td>
                  <td><?= htmlspecialchars(is_array(json_decode($details['num_meals'] ?? '', true)) ? implode(', ', json_decode($details['num_meals'], true)) : ($details['num_meals'] ?? '-')) ?></td>
                  <td><?= htmlspecialchars($details['diet'] ?? '-') ?></td>
                  <td><?= htmlspecialchars(is_array(json_decode($details['addons'] ?? '', true)) ? implode(', ', json_decode($details['addons'], true)) : ($details['addons'] ?? '-')) ?></td>
                  <td><?= htmlspecialchars($details['base_price'] ?? '-') ?></td>
                  <td><?= htmlspecialchars($details['addon_price'] ?? '-') ?></td>
                  <td><?= htmlspecialchars($info->totalCost) ?></td>
                </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>

        </table>
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

    const bookings = <?= json_encode($bookingDetails) ?>;
    console.log(bookings);

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
          renderTable(result.bookingDetails);
        } else {
          renderTable([]);
          showNotification(result.message || 'Search failed', 'error');
        }
      })
      .catch(() => {
        renderTable([]);
        showNotification('An unexpected error occurred', 'error');
      });
    }

    function resetCustomer() {
      location.reload();
    }

    function loadBookings() {
    try {
        fetch('<?=ROOT?>/public/opManager/bookingDetails')
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    renderTable(result.bookingDetails);
                } else {
                    showNotification('Failed to load booking Details.', 'error');
                }
            });
    } catch (error) {
        showNotification('An unexpected error occurred', 'error');
    }
}


function renderTable(data) {
  const tbody = document.getElementById('employeeTableBody');
  tbody.innerHTML = '';

  if (!data.length) {
    tbody.innerHTML = `<tr><td colspan="15" style="text-align: center; font-style: italic;">No Booking Details found.</td></tr>`;
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

  // Render each row
  Object.values(groupedBookings).forEach(({ info, details }) => {
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
      <td>${Array.isArray(JSON.parse(details['num_meals'] || '[]')) ? JSON.parse(details['num_meals']).join(', ') : (details['num_meals'] ?? '-')}</td>
      <td>${details['diet'] ?? '-'}</td>
      <td>${Array.isArray(JSON.parse(details['addons'] || '[]')) ? JSON.parse(details['addons']).join(', ') : (details['addons'] ?? '-')}</td>
      <td>${details['base_price'] ?? '-'}</td>
      <td>${details['addon_price'] ?? '-'}</td>
      <td>${info.totalCost}</td>
    `;
    tbody.appendChild(row);
  });
}


    window.onload = () => {
      loadBookings();
    };
  </script>
</body>
</html>
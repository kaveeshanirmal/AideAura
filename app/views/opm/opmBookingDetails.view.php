<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OPM Worker Complaints</title>
    <link rel="stylesheet" href="<?=ROOT?>/public/assets/css/opmBookingDetails.css">
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
        // Notification Functionality
        const notification = document.getElementById('notification');
        const showNotification = (message, type) => {
            notification.textContent = message;
            notification.className = `notification ${type} show`;
            setTimeout(() => notification.className = 'notification hidden', 2000);
        };

        const bookings = <?= json_encode($bookingDetails) ?>;
        console.log(bookings);
  </script>
</body>
</html>
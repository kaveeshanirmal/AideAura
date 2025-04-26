<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>fm Payment History</title>
    <link rel="stylesheet" href="<?=ROOT?>/public/assets/css/accountantPaymentDetails.css">
</head>
<body>
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
          <tbody>
          <tbody id="employeeTableBody">
                    <?php if (empty($paymentDetails)): ?>
        <tr>
            <td colspan="10" style="text-align: center; font-style: italic;">
                No Payment Details found.
            </td>
        </tr>
    <?php else: ?>
            <?php foreach ($paymentDetails as $payment): ?>
              <tr>
                <td><?= htmlspecialchars($payment->paymentID) ?></td>
                <td><?= htmlspecialchars($payment->bookingID) ?></td>
                <td><?= htmlspecialchars($payment->customerName) ?></td>
                <td><?= htmlspecialchars($payment->workerName) ?></td>
                <td><?= htmlspecialchars($payment->amount) ?></td>
                <td><?= htmlspecialchars($payment->paymentMethod) ?></td>
                <td><?= htmlspecialchars($payment->paymentStatus) ?></td>
                <td><?= htmlspecialchars($payment->paymentDate) ?></td>
                <td><?= htmlspecialchars($payment->transactionID) ?></td>
                <td><?= htmlspecialchars($payment->merchantReference) ?></td>
              </tr>
            <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

     <!-- <script>
        // Notification Functionality
        const notification = document.getElementById('notification');
        const showNotification = (message, type) => {
            notification.textContent = message;
            notification.className = `notification ${type} show`;
            setTimeout(() => notification.className = 'notification hidden', 2000);
        };
  </script> -->
</body>
</html>

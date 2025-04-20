<!-- adminWorkerInquiries.view.php -->
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Customer Complaints</title>
  <link rel="stylesheet" href="<?=ROOT?>/public/assets/css/adminWorkerInquiries.css">
</head>
<body>
  <div class="complaints-container">
  <?php include(ROOT_PATH . '/app/views/components/employeeNavbar.view.php'); ?>
    <div class="complaint-list" id="complaint-list">
      <?php include 'complaintList.php'; ?>
    </div>
    <div class="complaint-chat" id="complaint-chat">
      <div class="placeholder">Select a complaint to view details</div>
    </div>
  </div>

  <script src="<?=ROOT?>/public/assets/js/adminWorkerInquiries.js" defer></script>
</body>
</html>

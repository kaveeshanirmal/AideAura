<?php
require_once 'models/CustomerComplaintModel.php';
require_once 'models/CustomerModel.php';
require_once 'models/UserModel.php';

if (!isset($_GET['id'])) {
    echo "<p>No complaint selected</p>";
    return;
}

$complaintID = $_GET['id'];
$model = new CustomerComplaintModel();
$complaint = $model->getComplaintById($complaintID);
$customer = (new CustomerModel())->find($complaint->customerID, 'customerID');
$user = (new UserModel())->find($customer->userID, 'userID');
$updates = $model->getSolutionByComplaintId($complaintID);
?>

<div class="chat-header">
  <h4><?= $user->username ?> (Customer ID: <?= $customer->customerID ?>)</h4>
  <p><strong>Issue:</strong> <?= $complaint->issue_type ?></p>
  <p><strong>Priority:</strong> <?= $complaint->priority ?></p>
  <p><strong>Submitted:</strong> <?= $complaint->submitted_at ?></p>
</div>

<div class="chat-body">
  <div class="complaint-desc">
    <p><strong>Description:</strong><br><?= $complaint->description ?></p>
  </div>
  
  <?php if ($updates): ?>
    <div class="update-msg">
      <strong>Admin Response:</strong>
      <p><?= $updates->comments ?></p>
      <p class="timestamp"><?= $updates->updated_at ?></p>
    </div>
  <?php endif; ?>
</div>

<div class="chat-footer">
  <form method="post" action="submitComplaintUpdate.php">
    <input type="hidden" name="complaintID" value="<?= $complaint->complaintID ?>">
    <textarea name="comments" required placeholder="Write a response..."></textarea>
    <select name="status">
      <option value="Pending">Pending</option>
      <option value="In Progress">In Progress</option>
      <option value="Resolved">Resolved</option>
    </select>
    <button type="submit">Send</button>
  </form>
</div>

<?php 
    // Check if there's a session message to display
    $displayMessage = isset($_SESSION['message']) && !empty($_SESSION['message']);
    $messageType = $_SESSION['message_type'] ?? 'info';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Verification Status</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="<?=ROOT?>/public/assets/css/verificationStatus.css">
</head>
<body>
    <?php include(ROOT_PATH . '/app/views/components/navbar.view.php');?>
    
    <div class="container">
        <?php if ($displayMessage): ?>
            <!-- Session Message Display -->
            <div class="no-verification-request">
                <div class="message-box <?= $messageType ?>">
                    <h2>
                        <?php 
                        switch($messageType) {
                            case 'success':
                                echo "Success!";
                                break;
                            case 'error':
                                echo "Error Occurred";
                                break;
                            case 'warning':
                                echo "Warning";
                                break;
                            default:
                                echo "Notification";
                        }
                        ?>
                    </h2>
                    <p><?= htmlspecialchars($_SESSION['message']) ?></p>
                    <button class="action-button okay-button" onclick="clearSessionMessage()">
                        Okay
                    </button>
                </div>
            </div>
        <?php elseif (isset($requestData) && $requestData): ?>
            <!-- Existing content for when request data exists -->
            <div class="status-row">
                <div class="status-details">
                    <div class="status-detail">
                        <strong>Application ID:</strong> 
                        <span><?= htmlspecialchars($requestData->requestID) ?></span>
                    </div>
                    <div class="status-detail">
                        <strong>Submitted on:</strong> 
                        <span><?= htmlspecialchars($requestData->created_at) ?></span>
                    </div>
                    <div class="status-detail">
                        <strong>Last Update:</strong> 
                        <span><?= htmlspecialchars($requestData->updated_at) ?></span>
                    </div>
                    <div class="status-detail">
                        <strong>Status:</strong> 
                        <span><?= htmlspecialchars($requestData->status) ?></span>
                    </div>
                </div>
                <div class="status-actions">
                    <a href="<?=ROOT?>/public/workerVerification/editVerificationRequest">
                        <button class="action-button edit-button">Edit Application</button>
                    </a>
                    <button class="action-button delete-button" onclick="showDeleteConfirmation()">Delete Application</button>
                </div>
            </div>

            <!-- Delete Confirmation Modal -->
            <div id="deleteOverlay" class="overlay">
                <div class="confirmation-modal">
                    <h2>Confirm Deletion</h2>
                    <p>Are you sure you want to delete this verification request?</p>
                    <div class="confirmation-actions">
                        <button class="action-button edit-button" onclick="closeDeleteConfirmation()">Cancel</button>
                        <button class="action-button delete-button" onclick="confirmDeletion()">Delete</button>
                    </div>
                </div>
            </div>

        <?php else: ?>
            <!-- No verification request found -->
            <div class="no-verification-request">
                <div class="message-box">
                    <h2>No Verification Request Found</h2>
                    <p>It seems you haven't submitted a verification request yet. Would you like to get verified?</p>
                    <a href="<?=ROOT?>/public/workerVerification" class="action-button verify-now-button">
                        Get Verified Now
                    </a>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <?php include(ROOT_PATH . '/app/views/components/footer.view.php');?>
    
    <script>
        <?php if ($displayMessage): ?>
        function clearSessionMessage() {
            fetch('<?=ROOT?>/public/workerVerification/clearSessionMessage', {
                method: 'POST'
            }).then(() => {
                location.reload();
            }).catch(error => {
                console.error('Error clearing session message:', error);
            });
        }
        <?php endif; ?>

        // Existing delete confirmation scripts
        function showDeleteConfirmation() {
            document.getElementById('deleteOverlay').style.display = 'flex';
        }

        function closeDeleteConfirmation() {
            document.getElementById('deleteOverlay').style.display = 'none';
        }

        function confirmDeletion() {
            window.location.href = '<?=ROOT?>/public/workerVerification/deleteVerificationRequest';
        }
    </script>
</body>
</html>

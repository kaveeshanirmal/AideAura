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
            <div id="initialPopup" class="popup-overlay">
                <div class="popup-content">
                    <h2>Disclaimer!</h2>
                    <p>
                        Prior to submitting your verification request, you are required to visit our Head Office branch for an in-person interview & have the <strong>In-Location Reference Code</strong> with you, which is issued after the interview!
                    </p>
                    <p>
                        <strong>Documents Required :</strong><br>
                        1.Documents to verify identity (NIC, Birth Certificates etc.)<br>
                        2.Documents to verify criminal record clearance (Police Report)<br>
                        3.Certificates you have acquired in your working field (If you have any)<br>
                        4.A complete medical report<br>
                        <strong>Head Office Address :</strong><br>
                        No. 20, Reid Avenue, Colombo 07, Sri Lanka.<br>
                        <strong>Open Hours :</strong><br>
                        09.00 AM to 6.00 PM<br>
                        <strong>Hotline 1 :</strong><br>
                        +94 11 2 223 223<br>
                        <strong>Hotline 2 :</strong><br>
                        +94 11 2 223 225<br>
                        <strong>For Inquiries : </strong><br>
                        hello@aideaura.lk<br>
                        <strong>Scan below code for location on Google Maps :</strong><br>
                        <img src="<?= ROOT ?>/public/assets/images/locationQR.png" alt="Google Maps Location QR" style="margin-top:10px; width:100px; height:auto;">
                    </p>
                    <button class="acknowledge-button" onclick="closeInitialPopup()">Acknowledged</button>
                </div>
            </div>
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

        function closeInitialPopup() {
            document.getElementById('initialPopup').style.display = 'none';
        }
    </script>
</body>
</html>

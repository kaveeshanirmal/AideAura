
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HR Worker Profile1</title>
    <link rel="stylesheet" href="<?=ROOT?>/public/assets/css/hrWorkerProfileManagement1.css">
</head>
<body>
     <!-- Notification container -->
     <div id="notification" class="notification hidden"></div>
    <div class="dashboard-container">
        <?php include(ROOT_PATH . '/app/views/components/employeeNavbar.view.php'); ?>
    <main class="main-content">
            <div class="back-header">
            <?php if (isset($source) && $source === 'verificationRequests'): ?>
    <input type="hidden" id="currentSource" value="verificationRequests">             <!-- to get current source -->
        <a href="verificationRequests" class="back-button">‹</a>
    <?php else: ?>
        <input type="hidden" id="currentSource" value="workerProfiles">
        <a href="index" class="back-button">‹</a>
    <?php endif; ?>
                <div class="worker-header">
                    <div class="worker-avatar">
                        <img src="<?=ROOT?>/public/assets/images/user_icon.png" alt="Worker Avatar">
                    </div>
                    <div class="worker-title">
                        <h2>MR. <?= htmlspecialchars($worker['fullName']) ?></h2>
                        <p><?= htmlspecialchars($worker['role']) ?></p>
                    </div>
                </div>
            </div>

            <div class="worker-details-container">
                <div class="details-grid">
                    <div class="detail-item">
                        <span class="label">Full Name :</span>
                        <span class="value"><?= htmlspecialchars($worker['fullName']) ?></span>
                    </div>
                    <div class="detail-item">
                        <span class="label">User Name :</span>
                        <span class="value"><?= htmlspecialchars($worker['username']) ?></span>
                    </div>
                    <div class="detail-item">
                        <span class="label">Nationality :</span>
                        <span class="value"><?= htmlspecialchars($worker['Nationality']) ?></span>
                    </div>
                    <div class="detail-item">
                        <span class="label">Gender :</span>
                        <span class="value"><?= htmlspecialchars($worker['Gender']) ?></span>
                    </div>
                    <div class="detail-item">
                        <span class="label">Contact :</span>
                        <span class="value"><?= htmlspecialchars($worker['Contact']) ?></span>
                    </div>
                    <div class="detail-item">
                        <span class="label">Email :</span>
                        <span class="value"><?= htmlspecialchars($worker['email']) ?></span>
                    </div>
                    <div class="detail-item">
                        <span class="label">NIC/Passport No :</span>
                        <span class="value"><?= htmlspecialchars($worker['NIC']) ?></span>
                    </div>
                    <div class="detail-item">
                        <span class="label">Age : </span>
                        <span class="value"><?= htmlspecialchars($worker['Age']) ?></span>
                    </div>
                    <div class="detail-item">
                        <span class="label">Skilled or Specialized Areas :</span>
                        <span class="value"><?= htmlspecialchars($worker['ServiceType']) ?></span>
                    </div>
                    <div class="detail-item">
                        <span class="label">Spoken Languages :</span>
                        <span class="value"><?= htmlspecialchars($worker['SpokenLanguages']) ?></span>
                    </div>
                    <div class="detail-item">
                        <span class="label">Work Locations :</span>
                        <span class="value"><?= htmlspecialchars($worker['WorkLocations']) ?></span>
                    </div>
                    <div class="detail-item">
                        <span class="label">Experience Level :</span>
                        <span class="value"><?= htmlspecialchars($worker['ExperienceLevel']) ?></span>
                    </div>

                    <div class="detail-item">
                        <span class="label">Allergies or Physical Limitations :</span>
                        <span class="value"><?= htmlspecialchars($worker['AllergiesOrPhysicalLimitations']) ?></span>
                    </div>

                    <div class="detail-item">
                        <span class="label">Description :</span>
                        <span class="value"><?= htmlspecialchars($worker['Description']) ?></span>
                    </div>

                    <div class="detail-item">
                        <span class="label">Home Town :</span>
                        <span class="value"><?= htmlspecialchars($worker['HomeTown']) ?></span>
                    </div>
                    <div class="detail-item">
                        <span class="label">Bank Name and Branch Code :</span>
                        <span class="value"><?= htmlspecialchars($worker['BankNameAndBranchCode']) ?></span>
                    </div>
                    <div class="detail-item">
                        <span class="label">Bank Account Number : </span>
                        <span class="value"><?= htmlspecialchars($worker['BankAccountNumber']) ?></span>
                    </div>
                    <div class="detail-item">
                        <span class="label">Working Week Days : </span>
                        <span class="value"><?= htmlspecialchars($worker['WorkingWeekDays']) ?></span>
                    </div>
                    <div class="detail-item">
                        <span class="label">Working Week Ends : </span>
                        <span class="value"><?= htmlspecialchars($worker['WorkingWeekEnds']) ?></span>
                    </div>
                    <div class="detail-item">
                        <span class="label">Notes : </span>
                        <span class="value"><?= htmlspecialchars($worker['Notes']) ?></span>
                    </div>
                    <div class="detail-item">
                        <span class="label">In-Location Reference Code:</span>
                        <span class="value">
                            <?= !empty($worker['locationVerificationCode']) 
                                ? htmlspecialchars($worker['locationVerificationCode']) 
                                : '<span style="color: brown;">Not Provided</span>'; ?>
                        </span>
                    </div>
                    <div class="detail-item">
                        <span class="label">Verification Status :</span>
                        <span class="value"><?= htmlspecialchars($worker['Status']) ?></span>
                    </div>
                    
                </div>
            </div>

            <div class="action-buttons">
    <!-- Hidden userID to use later -->
    <input type="hidden" id="userID" value="<?= htmlspecialchars($worker['userID']) ?>">
    <input type="hidden" id="requestID" value="<?= htmlspecialchars($worker['requestID']) ?>">

    <?php if (strtolower($worker['Status']) === (strtolower('approved'))): ?>
               <!-- View Availability Schedule -->
        <button class="btn schedule" onclick="viewAvailabilitySchedule(<?= (int)$worker['userID'] ?>)">Availability Schedule</button>

    <?php elseif (strtolower($worker['Status']) === (strtolower('rejected'))): ?>
                <!-- Approve -->
                <button class="btn verify" onclick="updateStatus('approved')">Approve Request</button>
        <!-- View Availability Schedule -->
        <button class="btn schedule" onclick="viewAvailabilitySchedule(<?= (int)$worker['userID'] ?>)">Availability Schedule</button>


    <?php elseif (strtolower($worker['Status']) === (strtolower('pending'))): ?>
        <!-- Approve -->
        <button class="btn verify" onclick="updateStatus('approved')">Approve Request</button>

        <!-- Reject -->
        <button class="btn verify" onclick="updateStatus('rejected')">Reject Request</button>
        <!-- View Availability Schedule -->
        <button class="btn schedule" onclick="viewAvailabilitySchedule(<?= (int)$worker['userID'] ?>)">Availability Schedule</button>
        <?php endif; ?>
        
</div>

        
        </main>
    </div>
</body> 
<script>const ROOT = "<?=ROOT?>";
console.log(<?= json_encode($worker, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>);</script>
<script src="<?=ROOT?>/public/assets/js/hr/workerInfo.js"></script>
</html>

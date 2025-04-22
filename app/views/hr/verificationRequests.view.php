<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HR Verification Requests</title>
    <link rel="stylesheet" href="<?=ROOT?>/public/assets/css/hrVerificationRequest.css">
</head>
<body>

<div class="dashboard-container">
    <?php include(ROOT_PATH . '/app/views/components/employeeNavbar.view.php'); ?>
    <!-- Main Content -->
    <main class="main-content">
<div class="content-wrapper">      
    <div class="verification-list">
        <?php
        // // Replace with your actual database query
        // $verificationRequests = [
        //     [
        //         'name' => 'MR. Kamal Rupasinghe',
        //         'date' => '24 September 2024',
        //         'time' => '20.34 pm',
        //         'status' => 'pending'
        //     ],
        //     // Add more requests as needed
        // ];

        foreach ($verificationRequests as $request) {
            ?>
            <div class="verification-card">
                <div class="request-info">
                    <div class="request-main">
                    <h3><?php echo htmlspecialchars($request->full_name); ?></h3>
            <!-- Apply the status class dynamically -->
            <span class="status-badge <?php echo htmlspecialchars(strtolower($request->status)); ?>">
                <?php echo htmlspecialchars($request->status); ?>
            </span>  
            </div>                  <div class="request-details">
                        <span class="date">Created <?php echo htmlspecialchars($request->created_at); ?></span>
                        <span class="date">Updated <?php echo htmlspecialchars($request->updated_at); ?></span>
                    </div>
                </div>
                <div class="action-buttons">
                    <form action="findWorkerUserID" method="POST" style="display:inline;">
                        <input type="hidden" name="workerDataID" value="<?php echo htmlspecialchars($request->workerID); ?>">
                        <button type="submit" class="details-btn approve-btn">Details</button>
                    </form>

                    <button class="message-btn">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <path d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z" fill="currentColor"/>
                        </svg>
                    </button>
                </div>
            </div>
            <?php
        }
        ?>

        <div class="pagination-controls">
            <div class="per-page-control">
                <select id="perPage">
                    <option value="7">7</option>
                    <option value="10">10</option>
                    <option value="20">20</option>
                </select>
                <span>per page</span>
            </div>
            <div class="page-navigation">
                <span>of 1 pages</span>
                <div class="navigation-buttons">
                    <button class="prev-btn" disabled>&lt;</button>
                    <button class="next-btn">&gt;</button>
                </div>
            </div>
        </div>
    </div>
</div>
</div>


</main>
</div>
</body>
</html>

<script>
const verificationRequests = <?php echo json_encode($verificationRequests); ?>;
console.log(verificationRequests);
</script>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Worker Profile Management2</title>
    <link rel="stylesheet" href="<?=ROOT?>/public/assets/css/adminWorkerProfile2.css">
</head>
<body>
    <div class="container">
        <!-- Sidebar Component -->
        <?php include(ROOT_PATH . '/app/views/components/employeeNavbar.view.php'); ?>
        <div class="main-content">
            <!-- Navbar Component -->
            <div class="dashboard-container">
           <div class="back-header">
           <form action="workerDetails" method="POST" style="display: inline;">
    <input type="hidden" name="userID" value="<?= htmlspecialchars($worker['userID']) ?>">
    <button type="submit" class="back-button">
        <span class="back-icon"><</span>
    </button>
</form>

                <div class="profile-header"><a href="workers">
                    <img src="<?=ROOT?>/public/assets/images/user_icon.png" alt="Profile Icon" class="profile-icon">
                    <div class="profile-info">
                        <h2>MR. Kamal Rupasinghe</h2>
                        <p>Certificates</p>
                        </a>
                    </div>
                </div>
                </div>
                <div class="profile-sectionnew">

                    <div class="documents-container">
                        <div class="document-card">
                            <div class="document-icon"></div>
                            <div class="document-info">
                                <h3>Master Chef Certificate Of Levenro Garden Hotel Schole.pdf</h3>
                                <p>4.32 Mb</p>
                            </div>
                        </div>

                        <div class="document-card">
                            <div class="document-icon"></div>
                            <div class="document-info">
                                <h3><?= htmlspecialchars($worker['certificates']) ?></h3>
                                <p>3.79 Mb</p>
                            </div>
                        </div>

                        <div class="document-card">
                            <div class="document-icon"></div>
                            <div class="document-info">
                                <h3>NIC.pdf</h3>
                                <p>3.79 Mb</p>
                            </div>
                        </div>

                        <div class="document-card">
                            <div class="document-icon"></div>
                            <div class="document-info">
                                <h3>Birth Certificate.pdf</h3>
                                <p>1.22 Mb</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
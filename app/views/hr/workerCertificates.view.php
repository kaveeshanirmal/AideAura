<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HR Worker Profile Management2</title>
    <link rel="stylesheet" href="<?=ROOT?>/public/assets/css/hrWorkerProfileManagement2.css">
</head>
<body>
    <div class="container">
        <!-- Navbar Component -->
        <?php include(ROOT_PATH . '/app/views/components/HR_navbar.view.php');  ?>
        <div class="main-content">
            <!-- Navbar Component -->
            <div class="dashboard-container">
           <div class="back-header">
                <button class="back-button">
                    <span class="back-icon"><a href="HrManager" class="back-button"> < </a>
                    </span>
                </button>
                <div class="profile-header"><a href="HrManager">
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
                                <h3>Colombo Central Hospital Medical Fitness Report.pdf</h3>
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
</body>
</html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HR Worker Profile1</title>
    <link rel="stylesheet" href="<?=ROOT?>/public/assets/css/hrWorkerProfileManagement1.css">
</head>
<body>
    <div class="dashboard-container">
    <?php include(ROOT_PATH . '/app/views/components/HR_side_bar.view.php'); ?>
       <main class="main-content">
        <?php include(ROOT_PATH . '/app/views/components/HR_navbar.view.php');  ?>
            <div class="back-header">
                <a href="hrWorkerProfileManagement" class="back-button">‹</a>
                <div class="worker-header">
                    <div class="worker-avatar">
                        <img src="assets/images/user_icon.png" alt="Worker Avatar">
                    </div>
                    <div class="worker-title">
                        <h2>MR. Kamal Rupasinghe</h2>
                        <p>Cleaner</p>
                    </div>
                </div>
            </div>

            <div class="worker-details-container">
                <div class="details-grid">
                    <div class="detail-item">
                        <span class="label">Name :</span>
                        <span class="value">R.N.D. Kamal Gunarathne</span>
                    </div>
                    <div class="detail-item">
                        <span class="label">Date Of Birth :</span>
                        <span class="value">1984.06.19</span>
                    </div>
                    <div class="detail-item">
                        <span class="label">Nationality :</span>
                        <span class="value">Sinhalese</span>
                    </div>
                    <div class="detail-item">
                        <span class="label">Gender :</span>
                        <span class="value">Male</span>
                    </div>
                    <div class="detail-item">
                        <span class="label">Contact :</span>
                        <span class="value">078 4968 720</span>
                    </div>
                    <div class="detail-item">
                        <span class="label">Email :</span>
                        <span class="value">kamal123@gamil.com</span>
                    </div>
                    <div class="detail-item">
                        <span class="label">ID/Passport No :</span>
                        <span class="value">1984 2543 6702 0149</span>
                    </div>
                    <div class="detail-item">
                        <span class="label">Employment History / Experience :</span>
                        <span class="value">5 years in Sri Lanka</span>
                    </div>
                    <div class="detail-item">
                        <span class="label">Skilled or Specialized Areas :</span>
                        <span class="value">Cooking</span>
                    </div>
                    <div class="detail-item">
                        <span class="label">Certificates :</span>
                        <span class="value">Master Chef Certificate Of Levenro Garden Hotel Schole</span>
                    </div>
                    <div class="detail-item">
                        <span class="label">Spoken Languages :</span>
                        <span class="value">Sinhala, Tamil</span>
                    </div>
                    <div class="detail-item">
                        <span class="label">Work Area/Location :</span>
                        <span class="value">Colombo Mid Town</span>
                    </div>
                    <div class="detail-item">
                        <span class="label">Job Preferences :</span>
                        <span class="value">Maximum an be cooked Three meals per day for adults</span>
                    </div>
                    <div class="detail-item">
                        <span class="label">Medical Fitness Certificate :</span>
                        <span class="value">Colombo Central Hospital Medical Fitness Report</span>
                    </div>
                    <div class="detail-item">
                        <span class="label">Allergies of Physical Limitations :</span>
                        <span class="value">No</span>
                    </div>
                    <div class="detail-item">
                        <span class="label">Address :</span>
                        <span class="value">78/1 Queen street Kollupitiya Colombo 07</span>
                    </div>
                    <div class="detail-item">
                        <span class="label">Bank Details :</span>
                        <span class="value">Peoples Bank Kollupitiya 1789-6325-4147-0502</span>
                    </div>
                </div>
            </div>


            <div class="action-buttons">
                    <button class="btn update">Update</button>
                    <button class="btn schedule"> <a href="hrWorkerSchedules">Availability schedule </a> </button>
                    <button class="btn certificates"> <a href="hrWorkerProfileManagement2"> Certificates </a> </button>
                </div>

        </main>
    </div>
</body>
</html>
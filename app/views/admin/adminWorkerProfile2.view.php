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
    <input type="hidden" name="workerData" value="<?= htmlspecialchars($worker['userID']) ?>">
    <button type="submit" class="back-button">
        <span class="back-icon"><</span>
    </button>
</form>

               <div class="document-card">
                   <div class="document-icon"></div>
                   <div class="document-info">
                       <h3><?= htmlspecialchars($worker['certificates']) ?></h3>
                       <p>
                           <?php
                           $certPath = ROOT_PATH . "/public/uploads/" . $worker['certificates'];
                           if (file_exists($certPath)) {
                               echo round(filesize($certPath) / 1048576, 2) . " Mb";
                           } else {
                               echo "File not found";
                           }
                           ?>
                       </p>
                   </div>
               </div>
               
                <div class="document-icon"></div>
                <div class="document-info">
                    <h3><?= htmlspecialchars($worker['medical']) ?></h3>
                    <p>
                        <?php
                            $medicalPath = ROOT_PATH . "/public/uploads/" . $worker['medical'];
                            if (file_exists($medicalPath)) {
                                echo round(filesize($medicalPath) / 1048576, 2) . " Mb";
                            } else {
                                echo "File not found";
                            }
                            ?>
                            <div class="document-card">
                        </p>
                    </div>
                </div>
                

                        <div class="document-card">
                            <div class="document-icon"></div>
                            <div class="document-info">
                                <h3><?= htmlspecialchars($worker['medical']) ?></h3>
                                <p>3.79 Mb</p>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
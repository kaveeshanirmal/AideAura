<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Worker Profiles</title>
    <link rel="stylesheet" href="<?=ROOT?>/public/assets/css/adminWorkerProfile.css">
</head>
<body>
    <div class="dashboard-container">
        <?php include(ROOT_PATH . '/app/views/components/admin_navbar.view.php'); ?>
        <main class="main-content">
            <div class="search-container">
                <label for="workerField">Select the Worker Field:</label>
                <select id="worker-field" class="search-input">
                    <option value="cook">Cooks</option>
                    <option value="cook24">Cook 24-hour</option>
                    <option value="nannies">Nannies</option>
                    <option value="all-rounder">All Rounders</option>
                    <option value="cleaners">Maids</option>
                </select>
            </div>

            <div class="workers-list" id="workers-list">
                <?php
                // Ensure workers array exists and is not empty
                if (!empty($workers)) {
                    foreach ($workers as $worker) {
                        $fullName = htmlspecialchars(($worker->firstName) . ' ' . ($worker->lastName));
                        $role = htmlspecialchars($worker->role);
                        echo '<div class="worker-card">';
                        echo '  <a href="worker1">'; // Update the href URL if needed
                        echo '      <div class="worker-info">';
                        echo '          <div class="worker-avatar">';
                        echo '              <img src="' . ROOT . '/public/assets/images/user_icon.png" alt="Worker Avatar">';
                        echo '          </div>';
                        echo '          <div class="worker-details">';
                        echo '              <h3>' . $fullName . '</h3>';
                        echo '              <p>' . $role . '</p>';
                        echo '          </div>';
                        echo '      </div>';
                        echo '  </a>';
                        echo '</div>';
                    }
                } else {
                    echo '<p>No workers found.</p>';
                }
                ?>
            </div>
        </main>
    </div>
</body>
</html>

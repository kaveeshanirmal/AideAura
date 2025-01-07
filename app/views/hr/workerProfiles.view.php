<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HR Worker Profils</title>
    <link rel="stylesheet" href="<?=ROOT?>/public/assets/css/hrWorkerProfileManagement.css">
</head>
<body>
    <div class="dashboard-container">
        <?php include(ROOT_PATH . '/app/views/components/HR_navbar.view.php');  ?>
        <main class="main-content">
            <div class="search-container">
                    <label for="workerField">Select the Worker Field:</label>
                    <select id="worker-field" class="search-input">
                        <option value="cook">Cooks</option>
                        <option value="cook">cook 24-hour</option>
                        <option value="nannies">Nannies</option>
                        <option value="all-rounder">All Rounders</option>
                        <option value="cleaners">Maid</option>
                    </select>
                </div>

            <div class="workers-list">
                <?php
                $workers = [
                    ['name' => 'MR. Kamal Rupasinghe', 'role' => 'Cleaner'],
                    ['name' => 'MR. Saman Athapaththu', 'role' => 'Cook'],
                    ['name' => 'MRS. Nadeeshani Gamage', 'role' => 'Nanny'],
                    ['name' => 'MR. Chathura Amarathunga', 'role' => 'All Rounder'],
                    ['name' => 'MR. Kamal Rupasinghe', 'role' => 'Cleaner'],
                    ['name' => 'MR. Saman Athapaththu', 'role' => 'Cook'],
                    ['name' => 'MRS. Nadeeshani Gamage', 'role' => 'Nanny'],
                    ['name' => 'MR. Chathura Amarathunga', 'role' => 'All Rounder']
                ];

                foreach ($workers as $worker) {
                    echo '<div class="worker-card"> <a href="' . ROOT . '/public/HrManager/workerInfo">';
                    echo '<div class="worker-info">';
                    echo '<div class="worker-avatar">';
                    echo '<img src="' . ROOT . '/public/assets/images/user_icon.png" alt="Worker Avatar">';                    echo '</div>';
                    echo '<div class="worker-details">';
                    echo '<h3>' . htmlspecialchars($worker['name']) . '</h3>';
                    echo '<p>' . htmlspecialchars($worker['role']) . '</p>';
                    echo '</div>';
                    echo '</div>';
                    echo '</a>  </div>';
                }
                ?>
            </div>
        </main>
    </div>
</body>
</html>
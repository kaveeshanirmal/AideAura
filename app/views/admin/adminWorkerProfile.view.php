<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Worker Profiles</title>
    <link rel="stylesheet" href="<?= htmlspecialchars(ROOT) ?>/public/assets/css/adminWorkerProfile.css">
    <script>
        const WORKERS_DATA = <?= isset($workers) ? json_encode($workers, JSON_HEX_TAG) : '[]'; ?>;
        const ROOT_PATH = "<?= htmlspecialchars(ROOT) ?>";
        console.log(WORKERS_DATA);
    </script>
    <script src="<?= htmlspecialchars(ROOT) ?>/public/assets/js/admin/adminWorkerProfile.js" defer></script>
</head>
<body>
    <div class="dashboard-container">
        <?php include(ROOT_PATH . '/app/views/components/employeeNavbar.view.php'); ?>
        <main class="main-content">
            <div class="search-container">
                <label for="worker-field">Select the Worker Field:</label>
                <select id="worker-field" class="search-input">
                    <option value="All">All</option>
                    <option value="Cook">Cook</option>
                    <option value="Cook 24-hour Live in">Cook 24-hour Live in</option>
                    <option value="Nanny">Nanny</option>
                    <option value="All rounder">All rounder</option>
                    <option value="Maid">Maid</option>
                </select>
            </div>
            
            <div class="workers-list" id="workers-list">
                <!-- Workers will be rendered dynamically -->
            </div>
            
            <!-- Added pagination container -->
            <div id="pagination" class="pagination"></div>
        </main>
    </div>
</body>
</html>
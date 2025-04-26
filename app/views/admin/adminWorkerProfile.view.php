<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Worker Profiles</title>
    <link rel="stylesheet" href="<?= htmlspecialchars(ROOT) ?>/public/assets/css/adminWorkerProfile.css">
    <style>
        /* Pagination styles */
        .pagination {
            margin-top: 15px;
            text-align: center;
        }
        .pagination button {
            margin: 0 5px;
            padding: 5px 12px;
            border: 1px solid #ccc;
            background: #f1f1f1;
            cursor: pointer;
            border-radius: 5px;
        }
        .pagination button.active {
            background-color: #7f5539;
            color: white;
        }
        .pagination button:hover {
            background-color: #7f5539;
            color: white;
        }
    </style>
    <script>
        const WORKERS_DATA = <?= isset($workers) ? json_encode($workers, JSON_HEX_TAG) : '[]'; ?>;
        const ROOT_PATH = "<?= htmlspecialchars(ROOT) ?>";
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
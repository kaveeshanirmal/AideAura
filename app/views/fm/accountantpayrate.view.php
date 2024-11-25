<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accountant payment rates</title>
    <link rel="stylesheet" href="<?=ROOT?>/public/assets/css/accountantpayrate.css">
</head>
<body>

<!-- Navbar -->
<div class="dashboard-container">
    <?php include(ROOT_PATH . '/app/views/components/accountant_navbar.view.php'); ?>
    <!-- Main Content -->
    <main class="main-content">
        <!-- Table Section -->
        <div class="table-container">
            <table class="rates-table">
                <thead>
                    <tr>
                        <th>Field of helper</th>
                        <th>Last update</th>
                        <th>Rate</th>
                        <th>Update</th>
                    </tr>
                </thead>
                <tbody>
                    <?php for($i = 0; $i < 6; $i++) { ?>
                        <tr>
                            <td>Cleaner</td>
                            <td>2024-11-05</td>
                            <td>3%</td>
                            <td>
                                <button class="update-btn">update</button>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>

            <!-- Pagination -->
            <div class="pagination">
                <div class="per-page">
                    <select>
                        <option value="10">10</option>
                        <option value="20">20</option>
                        <option value="30">30</option>
                    </select>
                    <span>per page</span>
                </div>
                <div class="page-info">
                    <select>
                        <option value="1">1</option>
                    </select>
                    <span>of 1 pages</span>
                    <button class="prev-btn" disabled>&lt;</button>
                    <button class="next-btn" disabled>&gt;</button>
                </div>
            </div>
        </div>
    </main>
</div>
</body>
</html>
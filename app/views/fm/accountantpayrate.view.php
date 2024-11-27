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
                        <th>ServiceID</th>
                        <th>Service Type</th>
                        <th>Base Price</th>
                        <th>Base Hours</th>
                        <th>Created At</th>
                        <th>Update</th>
                    </tr>
                </thead>
                <tbody>
                        <tr>
                            <td>1</td>
                            <td>home-style-food</td>
                            <td>500.00</td>
                            <td>1.00</td>
                            <td>2024-11-05 09:13:08</td>
                            <td>
                                <button class="update-btn">update</button>
                            </td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>dishwashing</td>
                            <td>100.00</td>
                            <td>0.25</td>
                            <td>2024-11-02 07:15:05</td>
                            <td>
                                <button class="update-btn">update</button>
                            </td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>24h cook</td>
                            <td>20000.00</td>
                            <td>24.00</td>
                            <td>2024-11-03 08:10:00</td>
                            <td>
                                <button class="update-btn">update</button>
                            </td>
                        </tr>
                        <tr>
                            <td>4</td>
                            <td>indoor cleaner</td>
                            <td>250.00</td>
                            <td>1.00</td>
                            <td>2024-11-08 10:00:03</td>
                            <td>
                                <button class="update-btn">update</button>
                            </td>
                        </tr>
                        <tr>
                            <td>5</td>
                            <td>outdoor cleaner</td>
                            <td>200.00</td>
                            <td>1.00</td>
                            <td>2024-11-03 11:14:20</td>
                            <td>
                                <button class="update-btn">update</button>
                            </td>
                        </tr>
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
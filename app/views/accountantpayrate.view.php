
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FAQs and Reach Us</title>
    <link rel="stylesheet" href="<?=ROOT?>/public/assets/css/accountantpayrate.css">
</head>
<body>

<!-- Navbar -->

<main>
<div class="dashboard-container">
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="profile-section">
            <img src="assets/images/user_icon.png" alt="Profile" class="profile-image">
            <div class="profile-info">
                <h3>Moda Tharindu</h3>
                <p>+94 77 8475154</p>
            </div>
        </div>

        <nav class="sidebar-nav">
            <a href="#" class="nav-item active">
                <i class="icon-reports"></i>
                Reports
            </a>
            <a href="#" class="nav-item">
                <i class="icon-payment-rates"></i>
                Payment Rates
            </a>
            <a href="#" class="nav-item">
                <i class="icon-payment-history"></i>
                Payment History
            </a>
            <a href="#" class="nav-item">
                <i class="icon-help"></i>
                Help
            </a>
            <a href="#" class="nav-item logout">
                <i class="icon-logout"></i>
                Logout
            </a>
        </nav>
    </div>

    <!-- Main Content -->
    <main class="main-content">
        <div class="header">
            <h1 class="dashboard-title">Dashboard</h1>
            <div class="header-right">
                <div class="role-badge">Accountant</div>
                <button class="notification-btn">
                    <i class="icon-notification"></i>
                </button>
            </div>
        </div>

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
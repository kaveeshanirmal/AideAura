<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Worker Dashboard</title>
    <link rel="stylesheet" href="<?=ROOT?>/public/assets/css/workerDashboard.css">
</head>
<body>
    <!-- Header -->
    <?php include(ROOT_PATH . '/app/views/components/navbar.view.php');?>

    <!-- Main Container -->
    <main class="dashboard-main">
        <!-- Sidebar (Optional) -->
        <aside class="dashboard-sidebar">
            <ul class="sidebar-menu">
                <li><a href="<?=ROOT?>/worker/dashboard" class="active">Dashboard</a></li>
                <li><a href="<?=ROOT?>/worker/job-offers">Job Offers</a></li>
                <li><a href="<?=ROOT?>/worker/bookings">Bookings</a></li>
                <li><a href="<?=ROOT?>/worker/profile">Profile</a></li>
                <li><a href="<?=ROOT?>/help">Help & Support</a></li>
            </ul>
        </aside>

        <!-- Dashboard Content -->
        <section class="dashboard-content">
            <!-- Dashboard Overview -->
            <section class="dashboard-overview">
                <div class="card total-earnings">
                    <h3>Total Earnings</h3>
                    <p>$750 this month</p>
                </div>
                <div class="card job-status">
                    <h3>Current Job Status</h3>
                    <button id="toggle-status-btn" class="btn btn-status">Available</button>
                </div>
                <div class="card upcoming-jobs">
                    <h3>Upcoming Jobs</h3>
                    <ul>
                        <li>
                            <strong>Babysitting</strong> on <em>2024-11-20, 2 PM</em>
                        </li>
                        <li>
                            <strong>Cleaning</strong> on <em>2024-11-21, 10 AM</em>
                        </li>
                    </ul>
                </div>
                <div class="card pending-offers">
                    <h3>Pending Job Offers</h3>
                    <p>You have <strong>3 pending offers</strong>.</p>
                </div>
                <div class="card profile-completeness">
                    <h3>Profile Completeness</h3>
                    <div class="progress-bar">
                        <div class="progress" style="width: 80%;"></div>
                    </div>
                    <p>80% Complete</p>
                </div>
            </section>

            <!-- Job Offers -->
            <section class="job-offers">
                <h2>Job Offers</h2>
                <div class="offer-card">
                    <h3>Babysitting</h3>
                    <p><strong>Location:</strong> 123 Main Street</p>
                    <p><strong>Date:</strong> 2024-11-22</p>
                    <p><strong>Time:</strong> 10 AM - 1 PM</p>
                    <p><strong>Payment:</strong> $50/hr</p>
                    <div class="offer-actions">
                        <button class="btn btn-accept">Accept</button>
                        <button class="btn btn-reject">Reject</button>
                    </div>
                </div>
                <!-- Repeat similar cards for other offers -->
            </section>

            <!-- Bookings -->
            <section class="bookings">
                <h2>Bookings</h2>
                <div class="booking-card upcoming">
                    <h3>Babysitting</h3>
                    <p><strong>Date:</strong> 2024-11-22</p>
                    <p><strong>Time:</strong> 10 AM - 1 PM</p>
                    <p><strong>Client:</strong> John Doe</p>
                    <p><strong>Location:</strong> 123 Main Street</p>
                </div>
                <div class="booking-card completed">
                    <h3>Cleaning</h3>
                    <p><strong>Date:</strong> 2024-11-18</p>
                    <p><strong>Client:</strong> Jane Smith</p>
                    <p><strong>Status:</strong> Completed</p>
                </div>
                <!-- Repeat similar cards for other bookings -->
            </section>
        </section>
    </main>

    <!-- Footer -->
    <?php include(ROOT_PATH . '/app/views/components/footer.view.php');?>

    <script src="<?=ROOT?>/public/assets/js/workerDashboard.js"></script>
</body>
</html>

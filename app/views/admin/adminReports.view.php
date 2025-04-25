<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Booking Reports</title>
    <link rel="stylesheet" href="<?=ROOT?>/public/assets/css/adminReports.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.0/chart.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.25/jspdf.plugin.autotable.min.js"></script>
    
</head>
<body>
    <div class="dashboard-container">
        <?php include(ROOT_PATH . '/app/views/components/employeeNavbar.view.php'); ?>
        <div class="main-content">
            <div class="content-area">
                <!-- Worker Performance Reports Section -->
                <div class="report-section">
                    <h1 class="section-title">Worker Performance Reports</h1>
                    <div class="filter-section">
                        <div class="input-group">
                            <label>Worker ID:</label>
                            <input type="text" placeholder="#345784" id="worker-id-input" class="worker-input">
                        </div>
                        <div class="input-group">
                            <label>Start Date:</label>
                            <div class="date-input-wrapper">
                                <input type="date" id="worker-start-date" class="date-input">
                            </div>
                        </div>
                        <div class="input-group">
                            <label>End Date:</label>
                            <div class="date-input-wrapper">
                                <input type="date" id="worker-end-date" class="date-input">
                            </div>
                        </div>
                        <button class="generate-btn" id="generate-worker-report">Generate Report</button>
                        <button class="export-btn" id="export-worker-report">Export as PDF</button>
                    </div>

                    <div class="charts-grid">
                        <!-- Booking Status Chart -->
                        <div class="chart-card">
                            <h2>Booking Status Distribution</h2>
                            <div class="chart-container">
                                <canvas id="bookingStatusChart"></canvas>
                            </div>
                        </div>

                        <!-- Weekly Bookings Chart -->
                        <div class="chart-card">
                            <h2>Bookings Per Day</h2>
                            <canvas id="weeklyBookingsChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Service Category Reports Section -->
                <div class="report-section">
                    <h1 class="section-title">Service Category Reports</h1>
                    <div class="filter-section">
                        <div class="input-group">
                            <label for="service-type">Select Service Type:</label>
                            <select id="service-type" class="worker-input">
                                <option value="all">All Services</option>
                                <option value="Cook">Cook</option>
                                <option value="Cook 24-hour Live in">Cook 24-hour Live in</option>
                                <option value="Maid">Maid</option>
                                <option value="Nanny">Nanny</option>
                                <option value="All rounder">All Rounder</option>
                            </select>
                        </div>
                        <div class="input-group">
                            <label>Start Date:</label>
                            <div class="date-input-wrapper">
                                <input type="date" id="service-start-date" class="date-input">
                            </div>
                        </div>
                        <div class="input-group">
                            <label>End Date:</label>
                            <div class="date-input-wrapper">
                                <input type="date" id="service-end-date" class="date-input">
                            </div>
                        </div>
                        <button class="generate-btn" id="generate-service-report">Generate Report</button>
                        <button class="export-btn" id="export-service-report">Export as PDF</button>
                    </div>

                    <div class="charts-grid">
                        <!-- Service Category Sales Bar Chart -->
                        <div class="chart-card">
                            <h2>Est. Total Cost by Service Category</h2>
                            <canvas id="serviceCategoryBarChart"></canvas>
                        </div>

                        <!-- Service Category Distribution Pie Chart -->
                        <div class="chart-card">
                            <h2>Service Category Distribution</h2>
                            <div class="chart-container">
                                <canvas id="serviceCategoryPieChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Revenue Reports Section -->
                <div class="report-section">
                    <h1 class="section-title">Booking Revenue Reports</h1>
                    <div class="filter-section">
                        <div class="input-group">
                            <label>Period:</label>
                            <select id="period-select" class="worker-input">
                                <option value="daily">Daily</option>
                                <option value="weekly" selected>Weekly</option>
                                <option value="monthly">Monthly</option>
                            </select>
                        </div>
                        <div class="input-group">
                            <label>Start Date:</label>
                            <div class="date-input-wrapper">
                                <input type="date" id="revenue-start-date" class="date-input">
                            </div>
                        </div>
                        <div class="input-group">
                            <label>End Date:</label>
                            <div class="date-input-wrapper">
                                <input type="date" id="revenue-end-date" class="date-input">
                            </div>
                        </div>
                        <button class="generate-btn" id="generate-revenue-report">Generate Report</button>
                        <button class="export-btn" id="export-revenue-report">Export as PDF</button>
                    </div>

                    <div class="charts-grid">
                        <!-- Revenue Trend Line Chart -->
                        <div class="chart-card wide">
                            <h2>Est. Total Cost Trend</h2>
                            <canvas id="revenueTrendChart"></canvas>
                        </div>
                        
                        <!-- Est. Revenue Summary -->
                        <div class="chart-card wide">
                            <h2>Est. Total Cost Summary</h2>
                            <div class="summary-stats">
                                <div class="stat-item">
                                    <h3>Total Est. Cost</h3>
                                    <p id="total-revenue">$0.00</p>
                                </div>
                                <div class="stat-item">
                                    <h3>Avg. Booking Value</h3>
                                    <p id="avg-booking-value">$0.00</p>
                                </div>
                                <div class="stat-item">
                                    <h3>Total Bookings</h3>
                                    <p id="total-bookings">0</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        const ROOT = '<?=ROOT?>';
    </script>
    <script src="<?=ROOT?>/public/assets/js/bookingReports.js"></script>
</body>
</html>
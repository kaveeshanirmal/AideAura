<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Reports</title>
    <link rel="stylesheet" href="<?=ROOT?>/public/assets/css/adminReports.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.0/chart.min.js"></script>
</head>
<body>
    <div class="dashboard-container">
    <?php include(ROOT_PATH . '/app/views/components/admin_navbar.view.php'); ?>
        <div class="main-content">
            <div class="content-area">
                <div class="filter-section">
                    <div class="input-group">
                        <label>Worker ID:</label>
                        <input type="text" value="#345784" class="worker-input">
                    </div>
                    <div class="input-group">
                        <label>Start Date:</label>
                        <div class="date-input-wrapper">
                            <input type="date" placeholder="mm/dd/yyyy" class="date-input">                        </div>
                    </div>
                    <div class="input-group">
                        <label>End Date:</label>
                        <div class="date-input-wrapper">
                            <input type="date" placeholder="mm/dd/yyyy" class="date-input">                        </div>
                    </div>
                    <button class="generate-btn">Generate Report</button>
                    <button class="export-btn">Export as PDF</button>
                </div>

                <div class="charts-grid">
                    <!-- Work Time Pie Chart -->
                    <div class="chart-card">
                        <h2>Work time of worker per week</h2>
                        <div class="chart-container">
                            <canvas id="workTimePieChart"></canvas>
                        </div>
                    </div>

                    <!-- Weekly Work Hours Bar Chart -->
                    <div class="chart-card">
                        <h2>Work time of worker per day</h2>
                        <canvas id="weeklyWorkChart"></canvas>
                    </div>
                    </div>

                    <div class="filter-section-second">
                    <div class="input-group">
                    <label for="worker-field">Select the Worker Field:</label>
                    <select id="worker-field" class="worker-input">
                        <option value="cook">Cooks</option>
                        <option value="nannies">Nannies</option>
                        <option value="cleaner" selected>Cleaner</option>
                        <option value="all-rounder">All Rounders</option>
                    </select>
                </div>

                    <div class="input-group">
                        <label>Start Date:</label>
                        <div class="date-input-wrapper">
                            <input type="date" placeholder="mm/dd/yyyy" class="date-input">                        </div>
                    </div>
                    <div class="input-group">
                        <label>End Date:</label>
                        <div class="date-input-wrapper">
                            <input type="date" placeholder="mm/dd/yyyy" class="date-input">                        </div>
                    </div>
                    <button class="generate-btn">Generate Report</button>
                    <button class="export-btn">Export as PDF</button>
                </div>

                <div class="charts-grid">
                    <!-- Worker Category Sales Bar Chart -->
                    <div class="chart-card">
                        <h2>Sales by Worker Category</h2>
                        <canvas id="workerCategoryBarChart"></canvas>
                    </div>

                    <!-- Worker Category Sales Pie Chart -->
                    <div class="chart-card">
                        <h2>Worker Category Sales Share</h2>
                        <div class="chart-container">
                            <canvas id="workerCategoryPieChart"></canvas>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    
    <script src="<?=ROOT?>/public/assets/js/accountantReports.js"></script>
</body>
</html>
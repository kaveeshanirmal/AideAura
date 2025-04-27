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
<?php include(ROOT_PATH . '/app/views/components/employeeNavbar.view.php'); ?>

<div class="reports-container">
    <h1>Booking Revenue Reports</h1>
    
    <div class="report-controls">
        <div class="date-filter">
            <h3>Filter by Date Range</h3>
            <div class="date-inputs">
                <div class="form-group">
                    <label for="startDate">Start Date:</label>
                    <input type="date" id="startDate" name="startDate" value="<?= date('Y-m-d', strtotime('-1 year')) ?>" min="<?= $data['minDate'] ?>" max="<?= $data['maxDate'] ?>">
                </div>
                <div class="form-group">
                    <label for="endDate">End Date:</label>
                    <input type="date" id="endDate" name="endDate" value="<?= date('Y-m-d') ?>" min="<?= $data['minDate'] ?>" max="<?= $data['maxDate'] ?>">
                </div>
                <button id="filterBtn" class="btn">Apply Filter</button>
            </div>
        </div>
        
        <div class="report-type-selector">
            <h3>Report Type</h3>
            <div class="report-types">
                <button id="totalRevenueBtn" class="btn report-btn active">Total Revenue</button>
                <button id="serviceRevenueBtn" class="btn report-btn">Service Type Revenue</button>
            </div>
        </div>
    </div>
    
    <div class="report-sections">
        <!-- Total Revenue Report Section -->
        <div id="totalRevenueSection" class="report-section active">
            <div class="report-header">
                <h2>Total Revenue Report</h2>
                <div class="export-buttons">
                    <button id="exportTotalRevenuePDF" class="btn export-btn">Export as PDF</button>
                    <button id="exportTotalRevenueCSV" class="btn export-btn">Export as CSV</button>
                </div>
            </div>
            
            <div class="report-chart-container">
                <canvas id="totalRevenueChart"></canvas>
            </div>
            
            <div class="report-table-container">
                <h3>Monthly Revenue Breakdown</h3>
                <table id="totalRevenueTable" class="report-table">
                    <thead>
                        <tr>
                            <th>Month/Year</th>
                            <th>Total Revenue</th>
                            <th>Total Bookings</th>
                            <th>Average Revenue per Booking</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Data will be populated by JavaScript -->
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>Total</th>
                            <th id="grandTotalRevenue">Rs.0.00</th>
                            <th id="grandTotalBookings">0</th>
                            <th id="grandAverageRevenue">₹0.00</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        
        <!-- Service Type Revenue Report Section -->
        <div id="serviceRevenueSection" class="report-section">
            <div class="report-header">
                <h2>Service Type Revenue Report</h2>
                <div class="export-buttons">
                    <button id="exportServiceRevenuePDF" class="btn export-btn">Export as PDF</button>
                    <button id="exportServiceRevenueCSV" class="btn export-btn">Export as CSV</button>
                </div>
            </div>
            
            <div class="report-chart-container">
                <canvas id="serviceRevenueChart"></canvas>
            </div>
            
            <div class="report-table-container">
                <h3>Service Type Revenue Breakdown</h3>
                <table id="serviceRevenueTable" class="report-table">
                    <thead>
                        <tr>
                            <th>Service Type</th>
                            <th>Total Revenue</th>
                            <th>Total Bookings</th>
                            <th>Average Revenue per Booking</th>
                            <th>Revenue Percentage</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Data will be populated by JavaScript -->
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>Total</th>
                            <th id="serviceTotalRevenue">RS.0.00</th>
                            <th id="serviceTotalBookings">0</th>
                            <th id="serviceAverageRevenue">Rs.0.00</th>
                            <th>100%</th>
                        </tr>
                    </tfoot>
                </table>
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
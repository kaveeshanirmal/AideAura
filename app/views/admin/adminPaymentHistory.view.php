<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Payment History</title>
    <link rel="stylesheet" href="<?=ROOT?>/public/assets/css/adminPaymentHistory.css">
</head>
<body>
    
<div class="main-container">
<?php include(ROOT_PATH . '/app/views/components/admin_navbar.view.php'); ?>
    <div class="content-container">
        <main class="content-wrapper">
            <!-- Customer Payments Section -->
            <div class="payment-section">
                <h2>Payments of Customers</h2>
                <div class="table-container">
                    <table class="payment-table">
                        <thead>
                            <tr>
                                <th>Booking ID</th>
                                <th>Date</th>
                                <th>Time</th>
                                <th>Total Amount</th>
                                <th>Discount</th>
                                <th>Final Payment</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $customerPayments = [
                                ['#15267', 'Mar 1, 2023', '13.37', '35000.00', '1500.00', '33500.00'],
                                ['#15268', 'Mar 2, 2023', '13.45', '36000.00', '1800.00', '34200.00'],
                                ['#15269', 'Mar 3, 2023', '13.55', '37000.00', '2000.00', '35000.00'],
                                ['#15270', 'Mar 4, 2023', '13.65', '38000.00', '2200.00', '35800.00'],
                                ['#15271', 'Mar 5, 2023', '13.75', '39000.00', '2500.00', '36500.00'],
                                ['#15272', 'Mar 6, 2023', '13.85', '40000.00', '2700.00', '37300.00'],
                                ['#15273', 'Mar 7, 2023', '13.95', '41000.00', '3000.00', '38000.00'],
                                ['#15274', 'Mar 8, 2023', '14.05', '42000.00', '3200.00', '38800.00'],
                                ['#15275', 'Mar 9, 2023', '14.15', '43000.00', '3500.00', '39500.00'],
                                ['#15276', 'Mar 10, 2023', '14.25', '44000.00', '3800.00', '40200.00'],
                                ['#15277', 'Mar 11, 2023', '14.35', '45000.00', '4000.00', '41000.00'],
                                ['#15278', 'Mar 12, 2023', '14.45', '46000.00', '4200.00', '41800.00'],
                                ['#15279', 'Mar 13, 2023', '14.55', '47000.00', '4500.00', '42500.00'],
                                ['#15280', 'Mar 14, 2023', '14.65', '48000.00', '4800.00', '43200.00'],
                                ['#15281', 'Mar 15, 2023', '14.75', '49000.00', '5000.00', '44000.00']
                            ];
                            foreach ($customerPayments as $payment) {
                                echo "<tr>
                                    <td>{$payment[0]}</td>
                                    <td>{$payment[1]}</td>
                                    <td>{$payment[2]}</td>
                                    <td>{$payment[3]}</td>
                                    <td>{$payment[4]}</td>
                                    <td>{$payment[5]}</td>
                                </tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                    <div class="table-footer">
                        <div class="per-page">
                            <span>Per Page</span>
                            <select>
                                <option value="10">10</option>
                                <option value="20">20</option>
                                <option value="50">50</option>
                            </select>
                        </div>
                        <div class="pagination">
                            <span>of 1 pages</span>
                            <div class="pagination-arrows">
                                <button>&lt;</button>
                                <button>&gt;</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
</body>
</html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Rates</title>
    <link rel="stylesheet" href="<?=ROOT?>/public/assets/css/accountantPaymentHistory.css">
</head>
<body>
<div class="main-container">
    <?php
    include(ROOT_PATH . '/app/views/components/accountant_side_bar.view.php'); 
    ?>
    
    <div class="content-container">
        <?php
        include(ROOT_PATH . '/app/views/components/accountant_navbar.view.php');
        ?>

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

            <!-- Helper Payments Section -->
            <div class="payment-section">
                <h2>Payments for Helpers</h2>
                <div class="table-container">
                    <table class="payment-table">
                        <thead>
                            <tr>
                                <th>Payment ID</th>
                                <th>Date</th>
                                <th>Time</th>
                                <th>Total Earning</th>
                                <th>Pay Rate</th>
                                <th>Final Payment</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $helperPayments = [
                                ['#15267', 'Mar 1, 2023', '13.37', '35000.00', '78%', '33500.00'],
                            ];
                            foreach ($helperPayments as $payment) {
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

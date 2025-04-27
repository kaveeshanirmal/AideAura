<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment History</title>
    <style>
        /* [Your original CSS here — unchanged] */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            background-image: url("<?=ROOT?>/public/assets/images/booking_bg.jpg");
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            line-height: 1.6;
        }
        .main-container {
            max-width: 1200px;
            margin: 0 auto;
            background-color: white;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        .content-container {
            padding: 20px;
        }
        .payment-section {
            background-color: white;
            border-radius: 8px;
            padding: 20px;
        }
        .payment-section h2 {
            color: #3d0e07;
            margin-bottom: 20px;
            font-size: 24px;
        }
        .table-container {
            overflow-x: auto;
        }
        .payment-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            margin-bottom: 20px;
        }
        .payment-table thead {
            background: linear-gradient(135deg, #9d7d4a 0%, #3d0e07 100%);
            color: white;
        }
        .payment-table th, 
        .payment-table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #e2e8f0;
        }
        .payment-table th {
            font-weight: bold;
            text-transform: uppercase;
            font-size: 14px;
            letter-spacing: 0.5px;
        }
        .payment-table tbody tr:nth-child(even) {
            background-color: #f8f4f0;
        }
        .payment-table tbody tr:hover {
            background-color: #f0e6d8;
            transition: background-color 0.3s ease;
        }
        .table-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px;
            background-color: #efe5dc;
        }
        .per-page, 
        .pagination {
            display: flex;
            align-items: center;
        }
        .per-page span, 
        .pagination span {
            margin-right: 10px;
            color: #3d0e07;
        }
        select {
            padding: 8px;
            border: 1px solid #9d7d4a;
            border-radius: 4px;
            background-color: white;
        }
        .pagination-arrows button {
            background: linear-gradient(135deg, #9d7d4a 0%, #3d0e07 100%);
            color: white;
            border: none;
            padding: 8px 15px;
            margin: 0 5px;
            border-radius: 4px;
            cursor: pointer;
            transition: opacity 0.3s ease;
        }
        .pagination-arrows button:hover {
            opacity: 0.9;
        }
        .final-payment {
            color: #7a2c15;
            font-weight: bold;
        }
    </style>
</head>
<body>
<?php include(ROOT_PATH . '/app/views/components/navbar.view.php'); ?>

<div class="main-container">
    <div class="content-container">
        <main class="content-wrapper">
            <div class="payment-section">
                <h2>Payments of Customers</h2>
                <div class="table-container">
                    <table class="payment-table">
                        <thead>
                            <tr>
                                <th>Payment ID</th>
                                <th>Booking ID</th>
                                <th>Transaction ID</th>
                                <th>Currency</th>
                                <th>Payment Status</th>
                                <th>Payment Date</th>
                                <th>Last Updated</th>
                                <th>Service Type</th>
                                <th>Payment Method</th>
                                <th>Total Payment</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($payments)): ?>
                                <?php foreach ($payments as $payment): ?>
                                    <tr>
                                        <td>#<?= htmlspecialchars($payment->paymentID) ?></td>
                                        <td>#<?= htmlspecialchars($payment->bookingID) ?></td>
                                        <td>#<?= htmlspecialchars($payment->transactionID) ?></td>
                                        <td><?= htmlspecialchars($payment->currency) ?></td>
                                        <td><?= htmlspecialchars($payment->paymentStatus)?></td>
                                        <td><?= htmlspecialchars($payment->paymentDate) ?></td>
                                        <td><?= htmlspecialchars($payment->lastUpdated) ?>"</td>
                                        <td><?= htmlspecialchars($payment->serviceType) ?>"</td>
                                        <td><?= htmlspecialchars($payment->paymentMethod) ?></td>
                                        <td><?= htmlspecialchars($payment->amount) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" style="text-align:center;">No payments found.</td>
                                </tr>
                            <?php endif; ?>
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
                                <button>&lt; Prev</button>
                                <button>Next &gt;</button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </main>
    </div>
</div>

<?php include(ROOT_PATH . '/app/views/components/footer.view.php'); ?>
</body>
</html>

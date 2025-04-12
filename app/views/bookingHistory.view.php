<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking History</title>
    <style>
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

        #booking-body {
            margin-top: 100px;
            display: flex;
            justify-content: center;
            padding: 20px;
        }

        .booking-container {
            width: 100%;
            max-width: 1200px;
            background-color: white;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
        }

        .booking-container > p {
            background: linear-gradient(135deg, #9d7d4a 0%, #3d0e07 100%);
            color: white;
            padding: 15px 20px;
            font-size: 20px;
            font-weight: bold;
        }

        .booking-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        .booking-table thead {
            background: linear-gradient(135deg, #9d7d4a 0%, #3d0e07 100%);
            color: white;
        }

        .booking-table th {
            padding: 12px 15px;
            text-align: left;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 14px;
            letter-spacing: 0.5px;
        }

        .booking-table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #e2e8f0;
        }

        .booking-table tbody tr:nth-child(even) {
            background-color: #f8f4f0;
        }

        .booking-table tbody tr:hover {
            background-color: #f0e6d8;
            transition: background-color 0.3s ease;
        }

        .worker-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
        }

        .status {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 4px;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 12px;
        }

        .status.ongoing {
            background-color: #ffc107;
            color: #333;
        }

        .status.completed {
            background-color: #28a745;
            color: white;
        }

        .status.cancelled {
            background-color: #dc3545;
            color: white;
        }

        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 15px;
            background-color: #efe5dc;
        }

        .pagination span {
            margin: 0 10px;
            color: #3d0e07;
        }

        .pagination button {
            background: linear-gradient(135deg, #9d7d4a 0%, #3d0e07 100%);
            color: white;
            border: none;
            padding: 8px 15px;
            margin: 0 5px;
            border-radius: 4px;
            cursor: pointer;
            transition: opacity 0.3s ease;
        }

        .pagination button:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .pagination button:not(:disabled):hover {
            opacity: 0.9;
        }
    </style>
</head>
<body>
    <?php include(ROOT_PATH . '/app/views/components/navbar.view.php'); ?>
    <div id="booking-body">
        <div class="booking-container">
            <p>Booking History</p>
            <table class="booking-table">
                <thead>
                    <tr>
                        <th>Booking ID</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Worker Type</th>
                        <th>Worker</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>#15267</td>
                        <td>Mar 1, 2023, 13:37</td>
                        <td><span class="status ongoing">Ongoing</span></td>
                        <td>Cook</td>
                        <td><img src="<?=ROOT?>/public/assets/images/user_icon.png" alt="Worker" class="worker-avatar"></td>
                    </tr>
                    <tr>
                        <td>#153587</td>
                        <td>Jan 26, 2023, 15:00</td>
                        <td><span class="status completed">Completed</span></td>
                        <td>Nanny</td>
                        <td><img src="<?=ROOT?>/public/assets/images/user_icon.png" alt="Worker" class="worker-avatar"></td>
                    </tr>
                    <tr>
                        <td>#12436</td>
                        <td>Feb 12, 2023, 08:54</td>
                        <td><span class="status cancelled">Cancelled</span></td>
                        <td>Cook</td>
                        <td><img src="<?=ROOT?>/public/assets/images/user_icon.png" alt="Worker" class="worker-avatar"></td>
                    </tr>
                    <tr>
                        <td>#16879</td>
                        <td>Feb 12, 2033, 17:00</td>
                        <td><span class="status cancelled">Cancelled</span></td>
                        <td>Cleaner</td>
                        <td><img src="<?=ROOT?>/public/assets/images/user_icon.png" alt="Worker" class="worker-avatar"></td>
                    </tr>
                    <tr>
                        <td>#16378</td>
                        <td>Feb 28, 2033, 14:22</td>
                        <td><span class="status completed">Completed</span></td>
                        <td>Cook</td>
                        <td><img src="<?=ROOT?>/public/assets/images/user_icon.png" alt="Worker" class="worker-avatar"></td>
                    </tr>
                    <tr>
                        <td>#16609</td>
                        <td>March 13, 2033, 06:25</td>
                        <td><span class="status completed">Completed</span></td>
                        <td>All Rounder</td>
                        <td><img src="<?=ROOT?>/public/assets/images/user_icon.png" alt="Worker" class="worker-avatar"></td>
                    </tr>
                    <tr>
                        <td>#16907</td>
                        <td>March 18, 2033, 11:49</td>
                        <td><span class="status cancelled">Cancelled</span></td>
                        <td>Cook</td>
                        <td><img src="<?=ROOT?>/public/assets/images/user_icon.png" alt="Worker" class="worker-avatar"></td>
                    </tr>
                </tbody>
            </table>
            <div class="pagination">
                <span>10 per page</span>
                <span>1 of 1 pages</span>
                <button disabled>&lt;</button>
                <button disabled>&gt;</button>
            </div>
        </div>
    </div>
    <?php include(ROOT_PATH . '/app/views/components/footer.view.php'); ?>
</body>
</html>

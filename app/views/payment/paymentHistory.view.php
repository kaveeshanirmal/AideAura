<?php include(ROOT_PATH . '/app/views/components/navbar.view.php'); ?>
<link rel="stylesheet" href="<?=ROOT?>/public/assets/css/bookingHistory.css">
<div id="booking-body">
    <div class="booking-container">
        <p>Payment History</p>
        <table class="booking-table">
            <thead>
                <tr>
                    <th>Booking ID</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Total Amount (Rs.)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>#15267</td>
                    <td>Mar 1, 2023</td>
                    <td>13:37</td>
                    <td>3500.00</td>
                </tr>
                <tr>
                    <td>#153587</td>
                    <td>Jan 26, 2023</td>
                    <td>15:00</td>
                    <td>2000.00</td>
                </tr>
                <tr>
                    <td>#12436</td>
                    <td>Feb 12, 2033</td>
                    <td>08:54</td>
                    <td>4500.00</td>
                </tr>
                <tr>
                    <td>#16879</td>
                    <td>Feb 12, 2033</td>
                    <td>17:00</td>
                    <td>2230.00</td>
                </tr>
                <tr>
                    <td>#16378</td>
                    <td>Feb 28, 2033</td>
                    <td>17:00</td>
                    <td>1524.00</td>
                </tr>
                <tr>
                    <td>#16609</td>
                    <td>March 13, 2033</td>
                    <td>01:00</td>
                    <td>4230.00</td>
                </tr>
                <tr>
                    <td>#16907</td>
                    <td>March 18, 2033</td>
                    <td>01:00</td>
                    <td>2220.00</td>
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


<?php include(ROOT_PATH . '/app/views/components/navbar.view.php'); ?>
<link rel="stylesheet" href="<?=ROOT?>/public/assets/css/bookingHistory.css">
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
                    <td><img src="/api/placeholder/32/32" alt="Worker" class="worker-avatar"></td>
                </tr>
                <tr>
                    <td>#153587</td>
                    <td>Jan 26, 2023, 15:00</td>
                    <td><span class="status completed">Completed</span></td>
                    <td>Nanny</td>
                    <td><img src="/api/placeholder/32/32" alt="Worker" class="worker-avatar"></td>
                </tr>
                <tr>
                    <td>#12436</td>
                    <td>Feb 12, 2023, 08:54</td>
                    <td><span class="status cancelled">Cancelled</span></td>
                    <td>Cook</td>
                    <td><img src="/api/placeholder/32/32" alt="Worker" class="worker-avatar"></td>
                </tr>
                <tr>
                    <td>#16879</td>
                    <td>Feb 12, 2033, 17:00</td>
                    <td><span class="status cancelled">Cancelled</span></td>
                    <td>Cleaner</td>
                    <td><img src="/api/placeholder/32/32" alt="Worker" class="worker-avatar"></td>
                </tr>
                <tr>
                    <td>#16378</td>
                    <td>Feb 28, 2033, 14:22</td>
                    <td><span class="status completed">Completed</span></td>
                    <td>Cook</td>
                    <td><img src="/api/placeholder/32/32" alt="Worker" class="worker-avatar"></td>
                </tr>
                <tr>
                    <td>#16609</td>
                    <td>March 13, 2033, 06:25</td>
                    <td><span class="status completed">Completed</span></td>
                    <td>All Rounder</td>
                    <td><img src="/api/placeholder/32/32" alt="Worker" class="worker-avatar"></td>
                </tr>
                <tr>
                    <td>#16907</td>
                    <td>March 18, 2033, 11:49</td>
                    <td><span class="status cancelled">Cancelled</span></td>
                    <td>Cook</td>
                    <td><img src="/api/placeholder/32/32" alt="Worker" class="worker-avatar"></td>
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

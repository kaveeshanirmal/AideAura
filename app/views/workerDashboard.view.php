<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Worker Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/boxicons/2.1.4/css/boxicons.min.css">
    <script src="<?=ROOT?>/public/assets/js/workerDashboard.js" defer></script>
    <link rel="stylesheet" href="<?=ROOT?>/public/assets/css/workerDashboard.css">
</head>
<body>
<?php include(ROOT_PATH . '/app/views/components/navbar.view.php');?>
<div class="dashboard-container">
    <div class="dashboard-content">
        <div class="dashboard-header">
            <h1 class="dashboard-title">Worker Dashboard</h1>
            <div class="profile-section">
                <?php if (!empty($workerDetails['profileImage'])): ?>
                    <div class="profile-image">
                        <img src="<?=ROOT?>/<?= htmlspecialchars($workerDetails['profileImage']) ?>" alt="Profile Image">
                    </div>
                <?php endif; ?>
                <div class="profile-info">
                    <h3><?= htmlspecialchars($workerDetails['full_name'] ?? '') ?></h3>
                    <p><?= implode(', ', $workerDetails['roles'] ?? []) ?></p>
                    <div class="rating-display">
                        <div class="stars">
                            <?php for ($i = 0; $i < 5; $i++): ?>
                                <i class="bx <?= $i < floor($workerDetails['rating']) ? 'bxs-star' : 'bx-star' ?>"></i>
                            <?php endfor; ?>
                        </div>
                        <span class="rating-value"><?= number_format($workerDetails['rating'], 1) ?></span>
                        <span class="review-count">(<?= $workerDetails['reviews'] ?> reviews)</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="dashboard-grid">
            <!-- Income Card -->
            <div class="card income-card">
                <div class="card-header">
                    <h3 class="card-title">Monthly Income</h3>
                    <span class="card-period">Current Month</span>
                </div>
                <div class="card-body">
                    <div class="income-value">
                        <span class="currency">Rs.</span>
                        <span class="amount"><?php echo number_format($incomeData['currentMonth'], 2); ?></span>
                    </div>
                    <div class="income-comparison">
            <span class="change-indicator <?php echo $incomeData['percentChange'] >= 0 ? 'positive' : 'negative'; ?>">
                <?php echo ($incomeData['percentChange'] >= 0 ? '+' : '') . $incomeData['percentChange']; ?>%
            </span>
                        <span class="comparison-text">vs last month</span>
                    </div>
                </div>
            </div>

            <!-- Availability Card -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Availability Status</h3>
                </div>
                <div class="availability-toggle">
                    <button id="availability" class="toggle-btn <?= $workerDetails['availability'] ? 'available' : 'not-available' ?>" onclick="toggleAvailability()">
                        <i class='bx <?= $workerDetails['availability'] ? 'bx-check-circle' : 'bx-x-circle' ?>'></i>
                        <?= $workerDetails['availability'] ? 'Available' : 'Not Available' ?>
                    </button>
                </div>
                <p class="text-secondary" style="margin-top: 10px;">Toggle to update your availability</p>
            </div>

            <!-- Working Location Card -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Working Location</h3>
                </div>
                <div class="location-form">
                    <div class="form-group">
                        <h2 class="current-location"><?= htmlspecialchars($workerDetails['location'])?></h2>
                    </div>
                    <div class="form-group">
                        <label for="new-location">Enter new location</label>
                        <input type="text" id="new-location" class="form-control" placeholder="Enter your new location">
                    </div>
                    <button class="btn btn-primary" onclick="submitLocation()">
                        Update Location
                    </button>
                </div>
            </div>
        </div>

        <!-- Job Requests Section -->
        <div class="job-requests-section">
            <h2 class="job-requests-title">Job Requests</h2>
            <div class="job-request-list" id="jobRequestList">
                <?php if (!empty($pendingBookings)): ?>
                    <?php foreach ($pendingBookings as $booking):
                        $bookingData = $booking['value']['booking'];
                        $bookingDetails = $booking['value']['details'];
                        $date = new DateTime($bookingData->bookingDate);
                        ?>
                        <div class="job-request-item" data-booking-id="<?= $bookingData->bookingID ?>">
                            <div class="job-date">
                                <div class="job-date-day"><?= $date->format('d') ?></div>
                                <div class="job-date-weekday"><?= $date->format('D') ?></div>
                            </div>
                            <div class="job-info">
                                <h4 class="job-title"><?= htmlspecialchars($bookingData->serviceType) ?></h4>
                                <div class="job-location">
                                    <i class='bx bx-map'></i>
                                    <span><?= htmlspecialchars($bookingData->location)?></span>
                                </div>
                            </div>
                            <div class="job-actions">
                                <button class="btn-view" onclick="viewJobDetails(this.closest('.job-request-item'), <?= $bookingData->bookingID ?>)">
                                    <i class='bx bx-show'></i> View
                                </button>
                                <button class="btn-accept" onclick="acceptJob(this, <?= $bookingData->bookingID ?>)">
                                    <i class='bx bx-check'></i> Accept
                                </button>
                                <button class="btn-deny" onclick="denyJob(this, <?= $bookingData->bookingID ?>)">
                                    <i class='bx bx-x'></i> Decline
                                </button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="no-jobs-message">
                        <i class='bx bx-info-circle'></i>
                        <p>No new job requests available at the moment.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Confirmed Bookings Section -->
        <div class="bookings-section">
            <h2 class="bookings-title">Bookings</h2>
            <div class="bookings-tabs">
                <button class="tab-btn active" onclick="filterBookings('upcoming')">Upcoming</button>
                <button class="tab-btn" onclick="filterBookings('completed')">Completed</button>
                <button class="tab-btn" onclick="filterBookings('cancelled')">Cancelled</button>
            </div>
            <div class="bookings-list">
                <?php
                // Function to render booking items
                function renderBookingItem($booking, $status) {
                    $bookingData = $booking['value']['booking'];
                    $bookingDetails = $booking['value']['details'];
                    $date = new DateTime($bookingData->bookingDate);
                    ?>
                    <div class="booking-item <?= $status ?>" data-booking-id="<?= $bookingData->bookingID ?>">
                        <div class="booking-date">
                            <div class="booking-date-day"><?= $date->format('d') ?></div>
                            <div class="booking-date-month"><?= $date->format('M') ?></div>
                        </div>
                        <div class="booking-info">
                            <h4 class="booking-title"><?= htmlspecialchars($bookingData->serviceType) ?></h4>
                            <div class="booking-details">
                                <div class="booking-time">
                                    <i class='bx bx-time'></i>
                                    <span><?= htmlspecialchars($bookingData->startTime) ?></span>
                                </div>
                                <div class="booking-location">
                                    <i class='bx bx-map'></i>
                                    <span><?= htmlspecialchars($bookingData->location)?></span>
                                </div>
                                <div class="booking-client">
                                    <i class='bx bx-user'></i>
                                    <?php
                                    $customer_name = '';
                                    foreach ($bookingDetails as $detail) {
                                        if (isset($detail->detailType) && $detail->detailType === 'customer_name') {
                                            $customer_name = $detail->detailValue;
                                            break;
                                        }
                                    }
                                    ?>
                                    <span><?= htmlspecialchars($customer_name) ?></span>
                                </div>
                                <div class="booking-phone">
                                    <i class='bx bx-phone'></i>
                                    <?php
                                    $customer_phone = '';
                                    foreach ($bookingDetails as $detail) {
                                        if (isset($detail->detailType) && $detail->detailType === 'contact_phone') {
                                            $customer_phone = $detail->detailValue;
                                            break;
                                        }
                                    }
                                    ?>
                                    <span><?= htmlspecialchars($customer_phone) ?></span>
                                </div>
                            </div>
                        </div>
                        <?php if ($status === 'upcoming'): ?>
                            <div class="booking-actions">
                                <button class="btn-view" onclick="viewBookingDetails(this.closest('.booking-item'), <?= $bookingData->bookingID ?>)">
                                    <i class='bx bx-show'></i> View Details
                                </button>
                                <button class="btn-complete" onclick="completeBooking(this, <?= $bookingData->bookingID ?>)">
                                    <i class='bx bx-check-circle'></i> Complete
                                </button>
                            </div>
                        <?php else: ?>
                            <div class="booking-status <?= $status ?>">
                                <i class='bx bx-<?= $status === 'completed' ? 'check' : 'x' ?>-circle'></i>
                                <?= ucfirst($status) ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <?php
                }

                // Render confirmed bookings (upcoming)
                foreach ($confirmedBookings as $booking) {
                    renderBookingItem($booking, 'upcoming');
                }

                // Render completed bookings
                foreach ($completedBookings as $booking) {
                    renderBookingItem($booking, 'completed');
                }

                // Render cancelled bookings
                foreach ($cancelledBookings as $booking) {
                    renderBookingItem($booking, 'cancelled');
                }

                // Show message if no bookings
                if (empty($acceptedBookings) && empty($confirmedBookings) && empty($completedBookings) && empty($cancelledBookings)): ?>
                    <div class="no-jobs-message">
                        <i class='bx bx-info-circle'></i>
                        <p>No bookings found.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php include(ROOT_PATH . '/app/views/components/footer.view.php');?>

<!-- Job Details Modal -->
<div id="jobDetailsModal" class="modal">
    <div class="modal-content">
        <span class="close-modal">&times;</span>
        <h3>Job Request Details</h3>
        <div class="job-details-grid">
            <div class="detail-item">
                <span class="detail-label">Customer:</span>
                <span class="detail-value" id="detail-customer"></span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Contact:</span>
                <span class="detail-value" id="detail-contact"></span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Email:</span>
                <span class="detail-value" id="detail-email"></span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Service Type:</span>
                <span class="detail-value" id="detail-service"></span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Date:</span>
                <span class="detail-value" id="detail-date"></span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Time Slot:</span>
                <span class="detail-value" id="detail-time"></span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Location:</span>
                <span class="detail-value" id="detail-location"></span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Special Requests:</span>
                <span class="detail-value" id="detail-requests"></span>
            </div>
            <div class="detail-item total">
                <span class="detail-label">Total Price:</span>
                <span class="detail-value" id="detail-total-price"></span>
            </div>
        </div>
        <div class="modal-actions">
            <button class="btn-accept" id="modalAcceptBtn">
                <i class='bx bx-check'></i> Accept Job
            </button>
            <button class="btn-deny" id="modalDenyBtn">
                <i class='bx bx-x'></i> Decline
            </button>
        </div>
    </div>
</div>

<!-- Verification Modal -->
<div id="verificationModal" class="modal">
    <div class="modal-content">
        <span class="close-modal" onclick="closeVerificationModal()">&times;</span>
        <h3>Job Completion Verification</h3>
        <div class="verification-form">
            <p>Please enter the Job-completion code provided by the customer:</p>
            <div class="form-group">
                <input type="text" id="verificationCode" class="form-control" placeholder="Enter the 4 digit code">
                <small class="error-message" id="verificationError" style="color: var(--danger-color); display: none;"></small>
                <p id="smallText" class="text-secondary" style="margin-top: 10px;">
                    If you have not received a Job-completion code, please obtain it from the customer.
                </p>
            </div>
            <div class="modal-actions">
                <button class="btn btn-primary" onclick="verifyCompletion()">
                    <i class='bx bx-check'></i> Verify & Complete
                </button>
                <button class="btn btn-secondary" onclick="closeVerificationModal()">
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div>
</body>
</html>
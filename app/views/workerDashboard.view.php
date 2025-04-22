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
                <div class="profile-image">
                    <img src="<?=ROOT?>/<?= htmlspecialchars($workerDetails->profileImage) ?>" alt="Profile Image" />
                </div>
                <div class="profile-info">
                    <h3><?= htmlspecialchars($workerDetails->full_name) ?></h3>
                    <p><?= htmlspecialchars(implode(', ', $workerDetails->roles)) ?></p>
                    <div class="rating-display">
                        <div class="stars">
                            <?php
                            $fullStars = floor($workerDetails->rating);
                            $hasHalfStar = ($workerDetails->rating - $fullStars) >= 0.5;
                            $emptyStars = 5 - $fullStars - ($hasHalfStar ? 1 : 0);

                            // Full stars
                            for ($i = 0; $i < $fullStars; $i++) {
                                echo '<i class="bx bxs-star"></i>';
                            }

                            // Half star
                            if ($hasHalfStar) {
                                echo '<i class="bx bxs-star-half"></i>';
                            }

                            // Empty stars
                            for ($i = 0; $i < $emptyStars; $i++) {
                                echo '<i class="bx bx-star"></i>';
                            }
                            ?>
                        </div>
                        <span class="rating-value"><?= number_format($workerDetails->rating, 1) ?></span>
                        <span class="review-count">(<?= $workerDetails->reviews ?> reviews)</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="dashboard-grid">
            <!-- Income Card -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Total Income</h3>
                </div>
                <div class="stat-value">
                    Rs. 0.00
                    <span>+0%</span>
                </div>
                <p class="text-secondary">Compared to last month</p>
            </div>

            <!-- Availability Card -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Availability Status</h3>
                </div>
                <div class="availability-toggle">
                    <button id="availability" class="toggle-btn <?= $workerDetails->availability ? 'available' : 'not-available' ?>" onclick="toggleAvailability()">
                        <i class='bx <?= $workerDetails->availability ? 'bx-check-circle' : 'bx-x-circle' ?>'></i>
                        <?= $workerDetails->availability ? 'Available' : 'Not Available' ?>
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
                        <h2 class="current-location"><?= htmlspecialchars($workerDetails->location)?></h2>
                    </div>
                    <div class="form-group">
                        <label for="city">Enter new location</label>
                        <input type="text" id="city" class="form-control" placeholder="Enter your new location">
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
                        $bookingData = $booking->value->booking;
                        $bookingDetails = $booking->value->details;
                        $date = new DateTime($bookingData->date);
                        ?>
                        <div class="job-request-item" data-booking-id="<?= $bookingData->bookingID ?>">
                            <div class="job-date">
                                <div class="job-date-day"><?= $date->format('d') ?></div>
                                <div class="job-date-weekday"><?= $date->format('D') ?></div>
                            </div>
                            <div class="job-info">
                                <h4 class="job-title"><?= htmlspecialchars($bookingData->service_type) ?></h4>
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
<!--        <div class="bookings-section">-->
<!--            <h2 class="bookings-title">Confirmed Bookings</h2>-->
<!--            <div class="bookings-tabs">-->
<!--                <button class="tab-btn active" onclick="filterBookings('upcoming')">Upcoming</button>-->
<!--                <button class="tab-btn" onclick="filterBookings('completed')">Completed</button>-->
<!--                <button class="tab-btn" onclick="filterBookings('cancelled')">Cancelled</button>-->
<!--            </div>-->
<!--            <div class="bookings-list">-->
<!--                --><?php
//                // Function to render booking items
//                function renderBookingItem($booking, $status) {
//                    $bookingData = $booking->value->booking;
//                    $customerData = $booking->value->customer;
//                    $date = new DateTime($bookingData->date);
//                    ?>
<!--                    <div class="booking-item --><?php //= $status ?><!--">-->
<!--                        <div class="booking-date">-->
<!--                            <div class="booking-date-day">--><?php //= $date->format('d') ?><!--</div>-->
<!--                            <div class="booking-date-month">--><?php //= $date->format('M') ?><!--</div>-->
<!--                        </div>-->
<!--                        <div class="booking-info">-->
<!--                            <h4 class="booking-title">--><?php //= htmlspecialchars($bookingData->service_type) ?><!--</h4>-->
<!--                            <div class="booking-details">-->
<!--                                <div class="booking-time">-->
<!--                                    <i class='bx bx-time'></i>-->
<!--                                    <span>--><?php //= htmlspecialchars($bookingData->time_slot) ?><!--</span>-->
<!--                                </div>-->
<!--                                <div class="booking-location">-->
<!--                                    <i class='bx bx-map'></i>-->
<!--                                    <span>--><?php //= htmlspecialchars($customerData->city) ?><!--, --><?php //= htmlspecialchars($customerData->district) ?><!--</span>-->
<!--                                </div>-->
<!--                                <div class="booking-client">-->
<!--                                    <i class='bx bx-user'></i>-->
<!--                                    <span>--><?php //= htmlspecialchars($customerData->full_name) ?><!--</span>-->
<!--                                </div>-->
<!--                            </div>-->
<!--                        </div>-->
<!--                        --><?php //if ($status === 'upcoming'): ?>
<!--                            <div class="booking-actions">-->
<!--                                <button class="btn-complete" onclick="completeBooking(this, --><?php //= $bookingData->bookingID ?>//)">
//                                    <i class='bx bx-check-circle'></i> Complete
//                                </button>
//                                <button class="btn-cancel" onclick="cancelBooking(this, <?php //= $bookingData->bookingID ?>//)">
//                                    <i class='bx bx-x-circle'></i> Cancel
//                                </button>
//                            </div>
//                        <?php //else: ?>
<!--                            <div class="booking-status --><?php //= $status ?><!--">-->
<!--                                <i class='bx bx---><?php //= $status === 'completed' ? 'check' : 'x' ?><!---circle'></i>-->
<!--                                --><?php //= ucfirst($status) ?>
<!--                            </div>-->
<!--                        --><?php //endif; ?>
<!--                    </div>-->
<!--                    --><?php
//                }
//
//                // Render accepted bookings (upcoming)
//                foreach ($acceptedBookings as $booking) {
//                    renderBookingItem($booking, 'upcoming');
//                }
//
//                // Render confirmed bookings (upcoming)
//                foreach ($confirmedBookings as $booking) {
//                    renderBookingItem($booking, 'upcoming');
//                }
//
//                // Render completed bookings
//                foreach ($completedBookings as $booking) {
//                    renderBookingItem($booking, 'completed');
//                }
//
//                // Render cancelled bookings
//                foreach ($cancelledBookings as $booking) {
//                    renderBookingItem($booking, 'cancelled');
//                }
//
//                // Show message if no bookings
//                if (empty($acceptedBookings) && empty($confirmedBookings) && empty($completedBookings) && empty($cancelledBookings)): ?>
<!--                    <div class="no-jobs-message">-->
<!--                        <i class='bx bx-info-circle'></i>-->
<!--                        <p>No bookings found.</p>-->
<!--                    </div>-->
<!--                --><?php //endif; ?>
<!--            </div>-->
<!--        </div>-->
    </div>
</div>
<?php include(ROOT_PATH . '/app/views/components/footer.view.php');?>

<!-- Job Details Modal -->
<!--<div id="jobDetailsModal" class="modal">-->
<!--    <div class="modal-content">-->
<!--        <span class="close-modal">&times;</span>-->
<!--        <h3>Job Request Details</h3>-->
<!--        <div class="job-details-grid">-->
<!--            <div class="detail-item">-->
<!--                <span class="detail-label">Customer:</span>-->
<!--                <span class="detail-value" id="detail-customer"></span>-->
<!--            </div>-->
<!--            <div class="detail-item">-->
<!--                <span class="detail-label">Contact:</span>-->
<!--                <span class="detail-value" id="detail-contact"></span>-->
<!--            </div>-->
<!--            <div class="detail-item">-->
<!--                <span class="detail-label">Email:</span>-->
<!--                <span class="detail-value" id="detail-email"></span>-->
<!--            </div>-->
<!--            <div class="detail-item">-->
<!--                <span class="detail-label">Service Type:</span>-->
<!--                <span class="detail-value" id="detail-service"></span>-->
<!--            </div>-->
<!--            <div class="detail-item">-->
<!--                <span class="detail-label">Date:</span>-->
<!--                <span class="detail-value" id="detail-date"></span>-->
<!--            </div>-->
<!--            <div class="detail-item">-->
<!--                <span class="detail-label">Time Slot:</span>-->
<!--                <span class="detail-value" id="detail-time"></span>-->
<!--            </div>-->
<!--            <div class="detail-item">-->
<!--                <span class="detail-label">Location:</span>-->
<!--                <span class="detail-value" id="detail-location"></span>-->
<!--            </div>-->
<!--            <div class="detail-item">-->
<!--                <span class="detail-label">Special Requests:</span>-->
<!--                <span class="detail-value" id="detail-requests"></span>-->
<!--            </div>-->
<!--            <div class="detail-item total">-->
<!--                <span class="detail-label">Total Price:</span>-->
<!--                <span class="detail-value" id="detail-total-price"></span>-->
<!--            </div>-->
<!--        </div>-->
<!--        <div class="modal-actions">-->
<!--            <button class="btn-accept" id="modalAcceptBtn">-->
<!--                <i class='bx bx-check'></i> Accept Job-->
<!--            </button>-->
<!--            <button class="btn-deny" id="modalDenyBtn">-->
<!--                <i class='bx bx-x'></i> Decline-->
<!--            </button>-->
<!--        </div>-->
<!--    </div>-->
<!--</div>-->

<script>
    // AJAX polling for new job requests
    function pollForNewRequests() {
        fetch('<?= ROOT ?>/dashboard/getJobRequests', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({})
        })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success' && data.data.length > 0) {
                    console.log("fetched newest job requests");
                    updateJobRequestsList(data.data);
                }
            })
            .catch(error => console.error('Error polling for requests:', error));
    }

    // Update job requests list with new data
    function updateJobRequestsList(requests) {
        const jobRequestList = document.getElementById('jobRequestList');

        if (requests.length === 0) {
            jobRequestList.innerHTML = `
            <div class="no-jobs-message">
                <i class='bx bx-info-circle'></i>
                <p>No new job requests available at the moment.</p>
            </div>
        `;
            return;
        }

        let html = '';
        requests.forEach(request => {
            const date = new Date(request.date);
            html += `
            <div class="job-request-item" data-booking-id="${request.bookingID}">
                <div class="job-date">
                    <div class="job-date-day">${date.getDate()}</div>
                    <div class="job-date-weekday">${date.toLocaleDateString('en-US', { weekday: 'short' })}</div>
                </div>
                <div class="job-info">
                    <h4 class="job-title">${request.service_type}</h4>
                    <div class="job-location">
                        <i class='bx bx-map'></i>
                        <span>${request.customer.city}, ${request.customer.district}</span>
                    </div>
                </div>
                <div class="job-actions">
                    <button class="btn-view" onclick="viewJobDetails(this.closest('.job-request-item'), ${request.bookingID})">
                        <i class='bx bx-show'></i> View
                    </button>
                    <button class="btn-accept" onclick="acceptJob(this, ${request.bookingID})">
                        <i class='bx bx-check'></i> Accept
                    </button>
                    <button class="btn-deny" onclick="denyJob(this, ${request.bookingID})">
                        <i class='bx bx-x'></i> Decline
                    </button>
                </div>
            </div>
        `;
        });

        jobRequestList.innerHTML = html;
    }

    // Start polling when page loads
    document.addEventListener('DOMContentLoaded', function() {
        // Poll every 20 seconds
        setInterval(pollForNewRequests, 20000);
    });
</script>
</body>
</html>
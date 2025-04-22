<?php
// Worker profile data
$workerName = "Ms. Kusum Salgado";
$workerRole = "Nanny / House Keeper";
$profileImage = "images/profile.jpg";
$totalIncome = "238,040.00";
$incomeChange = "+45%";
$averageRating = 4.7;
$reviewCount = 28;
$isAvailable = true;

// Sample job requests data (would typically come from database)
$jobRequests = [
    [
        'id' => 1,
        'date_day' => '10',
        'date_weekday' => 'Wed',
        'title' => 'Babysitting',
        'location' => 'Colombo, Maharagama',
        'details' => [
            'customer_name' => 'Hasitha Dananjaya',
            'contact_phone' => '0784871617',
            'contact_email' => 'kaveesha@gmail.com',
            'gender' => 'female',
            'num_people' => '1-2',
            'num_meals' => ['lunch'],
            'diet' => 'veg',
            'addons' => ['desserts'],
            'base_price' => 500,
            'addon_price' => 200
        ]
    ],
    [
        'id' => 2,
        'date_day' => '15',
        'date_weekday' => 'Mon',
        'title' => 'Cook - 24Hrs',
        'location' => 'Colombo, Nugegoda',
        'details' => [
            'customer_name' => 'John Smith',
            'contact_phone' => '0771234567',
            'contact_email' => 'john@gmail.com',
            'gender' => 'any',
            'num_people' => '3-4',
            'num_meals' => ['breakfast', 'lunch', 'dinner'],
            'diet' => 'non-veg',
            'addons' => ['desserts', 'beverages'],
            'base_price' => 800,
            'addon_price' => 350
        ]
    ]
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enhanced Worker Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/boxicons/2.1.4/css/boxicons.min.css">
    <script src="<?=ROOT?>/public/assets/js/workerDashboard.js" defer></script>
    <link rel="stylesheet" href="<?=ROOT?>/public/assets/css/workerDashboard.css">
</head>
<body>
<?php include(ROOT_PATH . '/app/views/components/navbar.view.php');?>
<div class="dashboard-container">
    <div class="dashboard-content">
        <div class="dashboard-header">
            <h1 class="dashboard-title">Employee Dashboard</h1>
            <div class="profile-section">
                <div class="profile-image">
                    <img src="<?= htmlspecialchars($profileImage) ?>" alt="Profile Image" />
                </div>
                <div class="profile-info">
                    <h3><?= htmlspecialchars($workerName) ?></h3>
                    <p><?= htmlspecialchars($workerRole) ?></p>
                    <div class="rating-display">
                        <div class="stars">
                            <?php
                            $fullStars = floor($averageRating);
                            $hasHalfStar = ($averageRating - $fullStars) >= 0.5;
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
                        <span class="rating-value"><?= number_format($averageRating, 1) ?></span>
                        <span class="review-count">(<?= $reviewCount ?> reviews)</span>
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
                    Rs. <?= htmlspecialchars($totalIncome) ?>
                    <span><?= htmlspecialchars($incomeChange) ?></span>
                </div>
                <p class="text-secondary">Compared to last month</p>
            </div>

            <!-- Availability Card -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Availability Status</h3>
                </div>
                <div class="availability-toggle">
                    <button id="availability" class="toggle-btn <?= $isAvailable ? 'available' : 'not-available' ?>" onclick="toggleAvailability()">
                        <i class='bx <?= $isAvailable ? 'bx-check-circle' : 'bx-x-circle' ?>'></i>
                        <?= $isAvailable ? 'Available' : 'Not Available' ?>
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
                        <label for="district">Select District</label>
                        <select id="district" class="form-control">
                            <option value="Colombo">Colombo</option>
                            <option value="Galle">Galle</option>
                            <option value="Matara">Matara</option>
                            <option value="Kandy">Kandy</option>
                            <option value="Jaffna">Jaffna</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="city">Enter City</label>
                        <input type="text" id="city" class="form-control" placeholder="Enter your city">
                    </div>
                    <button class="btn btn-primary" onclick="submitLocation()">
                        Update Location
                    </button>
                </div>
            </div>
        </div>

        <!-- Job Requests Section -->
        <!-- Job Requests Section -->
        <div class="job-requests-section">
            <h2 class="job-requests-title">Job Requests</h2>
            <div class="job-request-list">
                <?php if (!empty($jobRequests)): ?>
                    <?php foreach ($jobRequests as $job): ?>
                        <div class="job-request-item">
                            <div class="job-date">
                                <div class="job-date-day"><?= htmlspecialchars($job['date_day']) ?></div>
                                <div class="job-date-weekday"><?= htmlspecialchars($job['date_weekday']) ?></div>
                            </div>
                            <div class="job-info">
                                <h4 class="job-title"><?= htmlspecialchars($job['title']) ?></h4>
                                <div class="job-location">
                                    <i class='bx bx-map'></i>
                                    <span><?= htmlspecialchars($job['location']) ?></span>
                                </div>
                            </div>
                            <div class="job-actions">
                                <button class="btn-view" onclick="viewJobDetails(this.closest('.job-request-item'), <?= $job['id'] ?>)">
                                    <i class='bx bx-show'></i> View
                                </button>
                                <button class="btn-accept" onclick="viewJobDetails(this.closest('.job-request-item'), <?= $job['id'] ?>)">
                                    <i class='bx bx-check'></i> Accept
                                </button>
                                <button class="btn-deny" onclick="denyJob(this.closest('.job-request-item'), <?= $job['id'] ?>)">
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
            <h2 class="bookings-title">Confirmed Bookings</h2>
            <div class="bookings-tabs">
                <button class="tab-btn active" onclick="filterBookings('upcoming')">Upcoming</button>
                <button class="tab-btn" onclick="filterBookings('completed')">Completed</button>
                <button class="tab-btn" onclick="filterBookings('cancelled')">Cancelled</button>
            </div>
            <div class="bookings-list">
                <!-- Booking 1 -->
                <div class="booking-item upcoming">
                    <div class="booking-date">
                        <div class="booking-date-day">12</div>
                        <div class="booking-date-month">Jun</div>
                    </div>
                    <div class="booking-info">
                        <h4 class="booking-title">House Cleaning</h4>
                        <div class="booking-details">
                            <div class="booking-time">
                                <i class='bx bx-time'></i>
                                <span>9:00 AM - 2:00 PM</span>
                            </div>
                            <div class="booking-location">
                                <i class='bx bx-map'></i>
                                <span>Colombo, Dehiwala</span>
                            </div>
                            <div class="booking-client">
                                <i class='bx bx-user'></i>
                                <span>Mrs. Perera</span>
                            </div>
                        </div>
                    </div>
                    <div class="booking-actions">
                        <button class="btn-complete" onclick="completeBooking(this)">
                            <i class='bx bx-check-circle'></i> Complete
                        </button>
                        <button class="btn-cancel" onclick="cancelBooking(this)">
                            <i class='bx bx-x-circle'></i> Cancel
                        </button>
                    </div>
                </div>

                <!-- Booking 2 -->
                <div class="booking-item upcoming">
                    <div class="booking-date">
                        <div class="booking-date-day">14</div>
                        <div class="booking-date-month">Jun</div>
                    </div>
                    <div class="booking-info">
                        <h4 class="booking-title">Child Care</h4>
                        <div class="booking-details">
                            <div class="booking-time">
                                <i class='bx bx-time'></i>
                                <span>8:00 AM - 5:00 PM</span>
                            </div>
                            <div class="booking-location">
                                <i class='bx bx-map'></i>
                                <span>Colombo, Mount Lavinia</span>
                            </div>
                            <div class="booking-client">
                                <i class='bx bx-user'></i>
                                <span>Mr. Fernando</span>
                            </div>
                        </div>
                    </div>
                    <div class="booking-actions">
                        <button class="btn-complete" onclick="completeBooking(this)">
                            <i class='bx bx-check-circle'></i> Complete
                        </button>
                        <button class="btn-cancel" onclick="cancelBooking(this)">
                            <i class='bx bx-x-circle'></i> Cancel
                        </button>
                    </div>
                </div>

                <!-- Booking 3 (Completed) -->
                <div class="booking-item completed">
                    <div class="booking-date">
                        <div class="booking-date-day">05</div>
                        <div class="booking-date-month">Jun</div>
                    </div>
                    <div class="booking-info">
                        <h4 class="booking-title">Elderly Care</h4>
                        <div class="booking-details">
                            <div class="booking-time">
                                <i class='bx bx-time'></i>
                                <span>10:00 AM - 4:00 PM</span>
                            </div>
                            <div class="booking-location">
                                <i class='bx bx-map'></i>
                                <span>Colombo, Kohuwala</span>
                            </div>
                            <div class="booking-client">
                                <i class='bx bx-user'></i>
                                <span>Mrs. Silva</span>
                            </div>
                        </div>
                    </div>
                    <div class="booking-status completed">
                        <i class='bx bx-check-circle'></i> Completed
                    </div>
                </div>

                <!-- Booking 4 (Cancelled) -->
                <div class="booking-item cancelled">
                    <div class="booking-date">
                        <div class="booking-date-day">08</div>
                        <div class="booking-date-month">Jun</div>
                    </div>
                    <div class="booking-info">
                        <h4 class="booking-title">Cooking Service</h4>
                        <div class="booking-details">
                            <div class="booking-time">
                                <i class='bx bx-time'></i>
                                <span>11:00 AM - 3:00 PM</span>
                            </div>
                            <div class="booking-location">
                                <i class='bx bx-map'></i>
                                <span>Colombo, Rajagiriya</span>
                            </div>
                            <div class="booking-client">
                                <i class='bx bx-user'></i>
                                <span>Mr. Bandara</span>
                            </div>
                        </div>
                    </div>
                    <div class="booking-status cancelled">
                        <i class='bx bx-x-circle'></i> Cancelled
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include(ROOT_PATH . '/app/views/components/footer.view.php');?>

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
                <span class="detail-label">Gender Preference:</span>
                <span class="detail-value" id="detail-gender"></span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Number of People:</span>
                <span class="detail-value" id="detail-people"></span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Meals Required:</span>
                <span class="detail-value" id="detail-meals"></span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Dietary Preference:</span>
                <span class="detail-value" id="detail-diet"></span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Add-ons:</span>
                <span class="detail-value" id="detail-addons"></span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Base Price:</span>
                <span class="detail-value" id="detail-base-price"></span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Add-on Price:</span>
                <span class="detail-value" id="detail-addon-price"></span>
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
</body>
</html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cooking Service</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?=ROOT?>/public/assets/css/forms/services.css">
    
    <style>
        body {
            background-image: url('<?=ROOT?>/public/assets/images/booking_bg.jpg');
        }
    </style>
    
    <script src="<?=ROOT?>/public/assets/js/modal.js" defer></script>
    <script src="<?=ROOT?>/public/assets/js/workhours.js" defer></script>
    <script>
        const ROOT = "<?php echo ROOT; ?>";
    </script>
</head>
<body>
    <?php include(ROOT_PATH . '/app/views/components/navbar.view.php'); ?>
    <div class="service-container">
        <?php include(ROOT_PATH . '/app/views/forms/header.view.php'); ?>
        
        <!-- Step 1: Service Selection -->
        <div class="content" id="step1-content">
            <div class="gender-container">
                <label class="label-gender" for="gender-button">Gender</label>
                <button class="gender-button">
                    Female
                    <!-- Add checkmark icon for selected option -->
                    <i class="fas fa-check-circle"></i>
                </button>
            </div>

            <!-- Cooking label and mandatory message -->
            <label class="service-name">Cooking</label>
            <div class="mandatory-message">Home-style food service is mandatory.</div>

            <!-- Home-style food card -->
            <div class="service-card" data-service="home-style-food">
                <div class="column column-1">
                    <img src="https://www.webstaurantstore.com/uploads/blog/2022/9/chef-cracking-an-egg-while-preparing-food-in-restaurant.jpg" alt="Home-style food">
                </div>
                <div class="column column-2">
                    <div class="service-title">Home-style Food</div>
                    <div class="service-price">Rs. 30,000/month starting price</div>
                    <div class="service-description">Authentic Sri Lankan home-style cooking service for delicious meals.</div>
                    <div class="service-services">+ All services inclusive</div>
                </div>
                <div class="column column-3">
                    <div class="rating">
                        <i class="fas fa-star"></i> 4.9
                    </div>
                    <div class="button-container">
                        <button class="add-button" data-service="home-style-food" onclick="openModal(this)">+</button>
                    </div>
                </div>
            </div>

            <!--Add-ons label-->
            <label class="service-name">Add-ons</label>

            <!-- Dishwashing card -->
            <div class="service-card">
                <div class="column column-1">
                    <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTTUUERs6gIQ42YA1_ViGhWhenlkJSfkprCgg&s" alt="Dishwashing">
                </div>
                <div class="column column-2">
                    <div class="service-title">Dishwashing</div>
                    <div class="service-price">Rs. 12,000/month starting price</div>
                    <div class="service-description">Professional dishwashing services for your kitchen.</div>
                    <div class="service-services">+ All services inclusive</div>
                </div>
                <div class="column column-3">
                    <div class="rating">
                        <i class="fas fa-star"></i> 4.9
                    </div>
                    <button class="add-button" data-service="dishwashing" onclick="openModal(this)">+</button>
                </div>
            </div>
        </div>

        <!-- Step 2: Work Schedule -->
        <div class="content" id="step2-content">
            <div class="schedule-container">
                <?php include(ROOT_PATH . '/app/views/forms/workSchedule.view.php'); ?>
            </div>
        </div>

        <!-- Step 3: Summary -->
        <div class="content" id="step3-content">
            <div class="summary-container">
                <label class="service-name">Booking Summary</label>
                
                <div class="summary-card">
                    <div class="summary-section">
                        <h3>Selected Services</h3>
                        <div class="summary-item">
                            <span>Home-style Food</span>
                            <span>Rs. 30,000/month</span>
                        </div>
                    </div>

                    <div class="summary-section">
                        <h3>Schedule Details</h3>
                        <div class="summary-item">
                            <span>Working Hours</span>
                            <span>7:00 AM - 2:00 PM</span>
                        </div>
                        <div class="summary-item">
                            <span>Starting Date</span>
                            <span>Selected Date</span>
                        </div>
                    </div>

                    <div class="summary-total">
                        <span>Total Monthly Cost</span>
                        <span>Rs. 30,000</span>
                    </div>
                </div>
            </div>
        </div>

        <?php include(ROOT_PATH . '/app/views/forms/footer.view.php'); ?>
    </div>
    
    <!-- Modal Overlay -->
    <div id="modal-overlay">
        <div class="modal-content">
            <button class="close-button" onclick="closeModal()">×</button>
            <div id="modal-body">
                <!-- Content will be loaded here -->
            </div>
        </div>
    </div>
    <?php include(ROOT_PATH . '/app/views/components/footer.view.php'); ?>
    <script src="<?=ROOT?>/public/assets/js/formHeader1.js"></script>
</body>
</html>

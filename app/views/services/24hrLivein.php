<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cooking Service</title>
    <!-- Font Awesome for the checkmark icon -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="services.css">


</head>
<body>
    <div class="service-container">
        
        <!-- Include the header inside the div box -->
        <?php include_once('../includes/header.php'); ?>
        
        <div class="content">
            <div class="gender-container">
                <label class="label-gender" for="gender-button">Gender</label>
                <button class="gender-button">
                    Female
                    <!-- Add checkmark icon for selected option -->
                    <i class="fas fa-check-circle"></i>
                </button>
            </div>

            <!-- nanny label and mandatory message -->
            <label class="service-name">24 Hrs Live In</label>
            <div class="mandatory-message">Select only 1 out of 4 services.</div>

            <!-- Housekeeper card -->
            <div class="service-card">
                <div class="column column-1">
                    <img src="https://www.webstaurantstore.com/uploads/blog/2022/9/chef-cracking-an-egg-while-preparing-food-in-restaurant.jpg" alt="nanny">
                </div>
                <div class="column column-2">
                    <div class="service-title">Housekeeper</div>
                    <div class="service-price">Rs. 25,000/month starting price</div>
                    <div class="service-description">Complete floor cleaning & Sanitization for a sparkling clean space.</div>
                    <div class="service-services">+ All services inclusive</div>
                </div>
                <div class="column column-3">
                    <div class="rating">
                        <i class="fas fa-star"></i> 4.9
                    </div>
                    <button class="add-button">+</button>
                </div>
            </div>

            <!-- cook card -->
            <div class="service-card">
                <div class="column column-1">
                    <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTTUUERs6gIQ42YA1_ViGhWhenlkJSfkprCgg&s" alt="cook">
                </div>
                <div class="column column-2">
                    <div class="service-title">Cook</div>
                    <div class="service-price">Rs. 30,000/month starting price</div>
                    <div class="service-description">Authentic Sri Lankan style cooking service for delicious meals.</div>
                    <div class="service-services">+ All services inclusive</div>
                </div>
                <div class="column column-3">
                    <div class="rating">
                        <i class="fas fa-star"></i> 4.9
                    </div>
                    <button class="add-button">+</button>
                </div>
            </div>

            <!-- All-Rounder card -->
            <div class="service-card">
                <div class="column column-1">
                    <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTTUUERs6gIQ42YA1_ViGhWhenlkJSfkprCgg&s" alt="AllRounder">
                </div>
                <div class="column column-2">
                    <div class="service-title">All-Rounder</div>
                    <div class="service-price">Rs. 40,000/month starting price</div>
                    <div class="service-description">Complete Household management, taking care of your home & life.</div>
                    <div class="service-services">+ All services inclusive</div>
                </div>
                <div class="column column-3">
                    <div class="rating">
                        <i class="fas fa-star"></i> 4.9
                    </div>
                    <button class="add-button">+</button>
                </div>
            </div>

        </div>

       

        <!-- Include the footer inside the div box -->
        <?php include_once('../includes/footer.php'); ?>
        
    </div>
</body>
</html>

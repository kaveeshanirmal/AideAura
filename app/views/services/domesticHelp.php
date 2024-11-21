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

            <!-- Cooking label and mandatory message -->
            <label class="service-name">Cleaning</label>
            <div class="mandatory-message">Brooming, Mopping service is mandatory.</div>

            <!-- Brooming, Mopping card -->
            <div class="service-card">
                <div class="column column-1">
                    <img src="https://www.webstaurantstore.com/uploads/blog/2022/9/chef-cracking-an-egg-while-preparing-food-in-restaurant.jpg" alt="Brooming">
                </div>
                <div class="column column-2">
                    <div class="service-title">Brooming, Mopping</div>
                    <div class="service-price">Rs. 20,000/month starting price</div>
                    <div class="service-description">Complete floor cleaning & Sanitization for a sparkling clean space</div>
                    <div class="service-services">+ All services inclusive</div>
                </div>
                <div class="column column-3">
                    <div class="rating">
                        <i class="fas fa-star"></i> 4.9
                    </div>
                    <button class="add-button">+</button>
                </div>
            </div>

            <!--Add-ons label-->
            <label class="service-name">Add-ons</label>

             <!-- Bathroom Cleaning card -->
             <div class="service-card">
                <div class="column column-1">
                    <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTTUUERs6gIQ42YA1_ViGhWhenlkJSfkprCgg&s" alt="Dishwashing">
                </div>
                <div class="column column-2">
                    <div class="service-title">Bathroom Cleaning</div>
                    <div class="service-price">Rs. 4,000/month starting price</div>
                    <div class="service-description">Thorough Bathroom cleaning for a hygienic space.</div>
                    <div class="service-services">+ All services inclusive</div>
                </div>
                <div class="column column-3">
                    <div class="rating">
                        <i class="fas fa-star"></i> 4.9
                    </div>
                    <button class="add-button">+</button>
                </div>
            </div>

             <!-- Dusting card -->
             <div class="service-card">
                <div class="column column-1">
                    <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTTUUERs6gIQ42YA1_ViGhWhenlkJSfkprCgg&s" alt="Dishwashing">
                </div>
                <div class="column column-2">
                    <div class="service-title">Dusting</div>
                    <div class="service-price">Rs. 4,000/month starting price</div>
                    <div class="service-description">Professional dusting services for a sptless dust-free home.</div>
                    <div class="service-services">+ All services inclusive</div>
                </div>
                <div class="column column-3">
                    <div class="rating">
                        <i class="fas fa-star"></i> 4.9
                    </div>
                    <button class="add-button">+</button>
                </div>
            </div>

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
                    <button class="add-button">+</button>
                </div>
            </div>
        </div>

       

        <!-- Include the footer inside the div box -->
        <?php include_once('../includes/footer.php'); ?>
        
    </div>
</body>
</html>

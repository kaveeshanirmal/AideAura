<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nanny Service</title>
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
            <label class="service-name">Baby-care Taking</label>
            <div class="mandatory-message">Select only 1 out of 5 services.</div>

            <!-- 0-2 months card -->
            <div class="service-card">
                <div class="column column-1">
                    <img src="https://www.webstaurantstore.com/uploads/blog/2022/9/chef-cracking-an-egg-while-preparing-food-in-restaurant.jpg" alt="nanny">
                </div>
                <div class="column column-2">
                    <div class="service-title">0-2 months</div>
                    <div class="service-price">Rs. 40,000/month starting price</div>
                    <div class="service-description">Japa maids, who are experts in taking care of new born babies and mothers</div>
                    <div class="service-services">+ All services inclusive</div>
                </div>
                <div class="column column-3">
                    <div class="rating">
                        <i class="fas fa-star"></i> 4.9
                    </div>
                    <button class="add-button" data-service="0-2-months" onclick="openModal(this)">+</button>
                </div>
            </div>

            <!-- 2-12 months card -->
            <div class="service-card">
                <div class="column column-1">
                    <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTTUUERs6gIQ42YA1_ViGhWhenlkJSfkprCgg&s" alt="nanny">
                </div>
                <div class="column column-2">
                    <div class="service-title">2-12 months</div>
                    <div class="service-price">Rs. 30,000/month starting price</div>
                    <div class="service-description">Babycare services for the well-being of your little one.</div>
                    <div class="service-services">+ All services inclusive</div>
                </div>
                <div class="column column-3">
                    <div class="rating">
                        <i class="fas fa-star"></i> 4.9
                    </div>
                    <button class="add-button" data-service="2-12-months" onclick="openModal(this)">+</button>
                </div>
            </div>

            <!-- 1-2 years card -->
            <div class="service-card">
                <div class="column column-1">
                    <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTTUUERs6gIQ42YA1_ViGhWhenlkJSfkprCgg&s" alt="nanny">
                </div>
                <div class="column column-2">
                    <div class="service-title">1-2 years</div>
                    <div class="service-price">Rs. 25,000/month starting price</div>
                    <div class="service-description">Professional child care service, ensuring their safety & well-being.</div>
                    <div class="service-services">+ All services inclusive</div>
                </div>
                <div class="column column-3">
                    <div class="rating">
                        <i class="fas fa-star"></i> 4.9
                    </div>
                    <button class="add-button" data-service="1-2-years" onclick="openModal(this)">+</button>
                </div>
            </div>

            <!-- 2-4 years card -->
            <div class="service-card">
                <div class="column column-1">
                    <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTTUUERs6gIQ42YA1_ViGhWhenlkJSfkprCgg&s" alt="nanny">
                </div>
                <div class="column column-2">
                    <div class="service-title">2-4 years</div>
                    <div class="service-price">Rs. 25,000/month starting price</div>
                    <div class="service-description">Experience childcare service for the nurturing of your child.</div>
                    <div class="service-services">+ All services inclusive</div>
                </div>
                <div class="column column-3">
                    <div class="rating">
                        <i class="fas fa-star"></i> 4.7
                    </div>
                    <button class="add-button" data-service="2-4-years" onclick="openModal(this)">+</button>
                </div>
            </div>

            <!-- more than 4 years card -->
            <div class="service-card">
                <div class="column column-1">
                    <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTTUUERs6gIQ42YA1_ViGhWhenlkJSfkprCgg&s" alt="nanny">
                </div>
                <div class="column column-2">
                    <div class="service-title">More than 4 years</div>
                    <div class="service-price">Rs. 35,000/month starting price</div>
                    <div class="service-description">Trusted babycare service for the growth & development of your child.</div>
                    <div class="service-services">+ All services inclusive</div>
                </div>
                <div class="column column-3">
                    <div class="rating">
                        <i class="fas fa-star"></i> 4.8
                    </div>
                    <button class="add-button" data-service="MoreThan4" onclick="openModal(this)">+</button>
                </div>
            </div>

        </div>

       

        <!-- Include the footer inside the div box -->
        <?php include_once('../includes/footer.php'); ?>
        
    </div>

    <!-- Modal Overlay -->
 <div id="modal-overlay" class="hidden">
    <div class="modal-content">
        <button class="close-button" onclick="closeModal()">×</button>
        <div id="modal-body" >
            <!-- Content dynamically loaded via PHP -->
        </div>
    </div>
</div>

<script src="modal.js"></script>

</body>
</html>

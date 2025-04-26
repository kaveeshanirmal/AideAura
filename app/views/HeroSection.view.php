<?php
//HeroFirstPage
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" with="device-width , initial-scale=1.0">
    <title>AideAura</title>
    <link rel="stylesheet" href="<?=ROOT?>/public/assets/css/Hero.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@100;200;300;400;500;600;700;800;900&display=swap');
        @import url('https://fonts.googleapis.com/css2?family=Fredoka:wght@300..700&family=Montserrat:wght@100&display=swap');
        @import url('https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&display=swap');
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
    <link href="https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css" rel="stylesheet"/>
    <script src="<?=ROOT?>/public/assets/js/HeroSection.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
</head>

<body>
<
    <?php include(ROOT_PATH . '/app/views/components/navbar.view.php'); ?>

    <div>
        <!-- Section 1 -->
        <div id="body-1">
            <section class="section-1">
                <div class="header-1">
                    <h1>Where Quality Care<br>Meets Home!</h1>
                </div>
                <div class="subheader-1">
                    <h2>
                        Experience trustworthy care for all your household work<br>with the best quality and reasonable prices
                    </h2>
                </div>
                <!-- conditionally include hero buttons based on user role -->
                <?php if(isset($_SESSION['userID']) && $_SESSION['role'] == 'worker'): ?>
                    <button class="glow-button-1" onclick="window.location.href='<?=ROOT?>/public/home/findJobs'">Look for jobs</button>
                <?php elseif(isset($_SESSION['userID']) && $_SESSION['role'] == 'customer'): ?>
                    <?php if (isset($_SESSION['booking']) && $_SESSION['booking']['status'] == 'pending'): ?>
                        <button class="glow-button-1" onclick="window.location.href='<?=ROOT?>/public/searchForWorker/waitingForResponse'">Continue Booking</button>
                    <?php elseif (isset($_SESSION['booking']) && $_SESSION['booking']['status'] == 'accepted'): ?>
                        <button class="glow-button-1" onclick="window.location.href='<?=ROOT?>/public/booking/orderSummary'">Complete your Booking</button>
                    <?php else: ?>
                        <button class="glow-button-1" onclick="window.location.href='<?=ROOT?>/public/home/findWorkers'">Find a Worker</button>
                    <?php endif; ?>
                <?php else: ?>
                    <button class="glow-button-1" onclick="window.location.href='<?=ROOT?>/public/login'">Get Started</button>
                <?php endif; ?>
                <div class="background-1"></div>
            </section>
        </div>

        <!-- Section 2 -->
        <div class="section-2">
            <div id="slide-2">
            <!-- Slide 1 -->
            <div class="item-2" style="background-image: url(<?=ROOT?>/public/assets/images/Hero_Slider_1.png);">
                <div class="content-2">
                    <div class="name-2">Babysitting</div>
                    <div class="description-2">
                        AideAura offers experienced and trustworthy babysitters to ensure your child is in safe hands.<br>Our babysitters are trained in child care, including feeding, engaging in educational activities,<br>and maintaining a secure environment. Whether for a few hours or full-time care, our<br>babysitters adapt to your family's needs.
                    </div>
                </div>
            </div>
            <!-- Slide 2 -->
            <div class="item-2" style="background-image: url(<?=ROOT?>/public/assets/images/Hero_Slider_2.png);">
                <div class="content-2">
                    <div class="name-2">Cooking</div>
                    <div class="description-2">
                        AideAura provides skilled cooks who can prepare meals according to your dietary preferences and <br>cultural cuisine. From daily meals to special occasions, our cooks ensure quality, hygiene, and <br>deliciousness in every dish. Enjoy the convenience of home-cooked meals without the <br>stress of preparation.
                    </div>
                </div>
            </div>
            <!-- Slide 3 -->
            <div class="item-2" style="background-image: url(<?=ROOT?>/public/assets/images/Hero_Slider_3.png);">
                <div class="content-2">
                    <div class="name-2">Cleaning</div>
                    <div class="description-2">
                        Our professional cleaners at AideAura guarantee a spotless and tidy home. Trained in efficient <br>cleaning techniques, our staff handles everything from deep cleaning to daily upkeep,<br>ensuring your home remains organized and welcoming. We offer flexible schedules<br>tailored to your lifestyle.
                    </div>
                </div>
            </div>
            <!-- Slide 4 -->
            <div class="item-2" style="background-image: url(<?=ROOT?>/public/assets/images/Hero_Slider_4.png);">
                <div class="content-2">
                    <div class="name-2">Gardening</div>
                    <div class="description-2">
                        AideAura's gardening services help maintain the beauty of your outdoor space. From routine lawn <br>care to plant maintenance, our gardeners take care of all aspects of your garden, keeping it lush <br> and vibrant. We ensure your garden stays healthy and enjoyable year-round.
                    </div>
                </div>
            </div>
            <!-- Slide 5 -->
            <div class="item-2" style="background-image: url(<?=ROOT?>/public/assets/images/Hero_Slider_5.png);">
                <div class="content-2">
                    <div class="name-2">Housekeeping</div>
                    <div class="description-2">
                        AideAura's housekeeping services provide comprehensive home management. Our housekeepers<br>are trained to handle laundry, organizing, and maintaining a well-ordered home environment.<br>Whether you need part-time or full-time assistance, we customize our services to meet<br>your specific requirements.
                    </div>
                </div>
            </div>
        </div>
        <div class="buttons-2">
            <button id="prev"><img src="<?=ROOT?>/public/assets/images/Prev_Icon.png" alt="prev_button"></button>
            <button id="next"><img src="<?=ROOT?>/public/assets/images/Next_Icon.png" alt="next_button"></button>
        </div>
    
        </div>

        <!-- Section 3 -->
        <div class="container-3">
            <div class="testimonial mySwiper">
                <div class="testi-content swiper-wrapper">
                <div class="slide-3 swiper-slide">
                    <img src="<?=ROOT?>/public/assets/images/Review_Customer_1.png" alt="user1" class="image" />
                    <p>
                        AideAura has been a lifesaver! I found a trustworthy babysitter who’s been wonderful with my kids. The process was so easy, and the service is reliable. 
                        <br>Highly recommend!
                    </p>

                    <i class="bx bxs-quote-alt-left quote-icon"></i>

                    <div class="details">
                    <span class="name">Marnie Lotter</span>
                    <span class="job">Using AideAura for 2 years</span>
                    </div>
                </div>
                <div class="slide-3 swiper-slide">
                    <img src="<?=ROOT?>/public/assets/images/Review_Customer_2.png" alt="user2" class="image" />
                    <p>
                        I needed help with cleaning, and AideAura matched me with someone who truly cares about their work. My home has never looked better! I’m grateful for the professional and friendly service.
                    </p>

                    <i class="bx bxs-quote-alt-left quote-icon"></i>

                    <div class="details">
                    <span class="name">James Macbeth</span>
                    <span class="job">Using AideAura for 3 months</span>
                    </div>
                </div>
                <div class="slide-3 swiper-slide">
                    <img src="<?=ROOT?>/public/assets/images/Review_Customer_3.png" alt="user3" class="image" />
                    <p>
                        Finding a good cook was a challenge until I tried AideAura. Now, I enjoy delicious meals tailored to my tastes. It’s made a real difference in my daily life!
                    </p>

                    <i class="bx bxs-quote-alt-left quote-icon"></i>

                    <div class="details">
                    <span class="name">Hannah Nasrul</span>
                    <span class="job">Using AideAura for 5 years</span>
                    </div>
                </div>
                </div>
                <div class="swiper-button-next nav-btn"></div>
                <div class="swiper-button-prev nav-btn"></div>
                <div class="swiper-pagination"></div>
            </div>
        </div>
    </div>
    <?php include(ROOT_PATH . '/app/views/components/footer.view.php'); ?>
    <?php
// review modal for customers to rate workers
if (isset($_SESSION['customerID']) && isset($_SESSION['loggedIn'])) {
    // Include the review modal
    include_once ROOT_PATH . '/app/views/review_modal.view.php';
    
    // Add JavaScript 
    echo '<script>
            // Pass PHP root URL to JavaScript
            const ROOT_URL = "' . ROOT . '";        </script>';
    echo '<script src="' . ROOT . '/public/assets/js/review_modal.js"></script>';
}
?>
</body>
</html>



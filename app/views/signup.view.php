<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="<?=ROOT?>/public/assets/css/signup.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="<?=ROOT?>/public/assets/js/signup.js" defer></script>
</head>
<body>
    <?php include(ROOT_PATH . '/app/views/components/signupNavbar.view.php'); ?>
    <div class="container">
        <form action="<?=ROOT?>/public/signup" method="POST" onsubmit="validatepassword()">
        <div class="signup-form-container">
            <div class="signup-form">
                <h3>I'm a</h3>
                <!-- two images as buttons -->
                <div class="signup-form-btn">
                    <div class="image-label">
                        <img src="assets/images/kitchenWorker.png" alt="Worker" class="worker-btn" id="worker-btn">
                        <span class="label-text">Domestic Helper</span>
                    </div>
                    <div class="image-label">
                        <img src="assets/images/customer.png" alt="Customer" class="customer-btn" id="customer-btn"> 
                        <span class="label-text">Customer</span>
                    </div>
                </div>
            </div>
        </div>
        </form>
    </div>
    <?php include(ROOT_PATH . '/app/views/components/footer.view.php'); ?>
</body>
</html>

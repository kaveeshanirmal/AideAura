<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="assets/css/signup.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="assets/js/signup.js" defer></script>
</head>
<body>
    <div class="container">
        <form action="<?=ROOT?>/public/signup" method="POST" onsubmit="validatepassword()">
        <div class="signup-form-container">
            <h2>I'm a</h2>
            <div class="signup-form">
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
</body>
</html>

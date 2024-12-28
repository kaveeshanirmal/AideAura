<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="assets/css/resetpassword.css">
</head>
<body>
    <div class="container">
        <div class="login-form">
            <h2 class="greeting">Confirm Your Password</h2>
            <form action="home" method="POST">
                <div class="input-group">
                    <label for="OTP">Enter your OTP code</label>
                    <input type="number" id="number" placeholder="Enter your OTP">
                </div>
                <button type="submit" class="login-button"> Confirm </button>
            </form>
        </div>
        <div class="image-section">
            <img src="assets/images/workerpic.png" alt="Login Image" class="login-image">
        </div>
    </div>
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="assets/css/login.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="container">
        <div class="login-form">
            <h2 class="greeting">Welcome back!</h2>
            <!-- Error message display -->
            <?php if (isset($data['error'])) : ?>
                <div class="error-message">
                    <i class="fas fa-exclamation-circle"></i>
                    <span class="error-text"><?= htmlspecialchars($data['error']) ?></span>
                </div>
            <?php endif; ?>
            <form action="<?=ROOT?>/public/login" method="POST">
                <div class="input-group">
                    <label for="username">Username</label>
                    <input type="text" name="username" placeholder="Username" required>
                </div>
                <div class="input-group">
                    <div class="label-wrapper">
                        <label for="password">Password</label>
                        <a href="resetpassword" class="forgot-password">Forgot password?</a>
                    </div>
                    <div class="password-wrapper">
                        <input type="password" name="password" placeholder="Password" required>
                        <span class="toggle-password"><i class="fas fa-eye"></i></span>
                    </div>
                </div>
                <button type="submit" class="login-button">Login</button>
            </form>
            <div class="signup-link">
                Not here on accident? <a href="signup">Sign up</a>
            </div>
        </div>
        <div class="image-section">
            <img src="assets/images/workerpic.png" alt="Login Image" class="login-image">
        </div>
    </div>

    <script>
        document.querySelector('.toggle-password').addEventListener('click', function () {
            const passwordField = document.getElementById('password');
            const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordField.setAttribute('type', type);
            this.querySelector('i').classList.toggle('fa-eye');
            this.querySelector('i').classList.toggle('fa-eye-slash');
        });
        document.querySelector('form').addEventListener('submit', function() {
            document.querySelector('.login-button').disabled = true;
        });

    </script>
</body>
</html>

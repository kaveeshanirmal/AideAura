<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="assets/css/signup.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="container">
        <div class="signup-form-container">
            <div class="signup-form">
                <h2 class="greeting">Get Started Now</h2>
                <form action="<?=ROOT?>/public/signup" method="POST" onsubmit="validatepassword()">
                    <div class="input-group">
                    <label for="role">I am a</label>
                        <select name="role" name="role">
                            <option value="customer">Customer</option>
                            <option value="worker">Worker</option>
                        </select>
                    </div>
                    <div class="input-group">
                        <label for="name">Name</label>
                        <input type="text" name="name" placeholder="Enter your name">
                    </div>
                    <div class="input-group">
                        <label for="username">Username</label>
                        <input type="text" name="username" placeholder="Enter your username">
                    </div>
                    <div class="input-group">
                        <label for="address">Address</label>
                        <input type="text" name="address" placeholder="Enter your address">
                    </div>
                    <div class="input-group">
                        <label for="phone">Phone Number</label>
                        <input type="tel" name="phone" placeholder="07x xxxx xxx">
                    </div>
                    <div class="input-group">
                        <label for="email">Email Address</label>
                        <input type="email" name="email" placeholder="Enter your email address">
                    </div>
                    <div class="input-group">
                        <div class="label-wrapper">
                            <label for="password">Password</label>
                        </div>
                        <div class="password-wrapper">
                            <input type="password" name="password" placeholder="Enter your password">
                            <span class="toggle-password"><i class="fas fa-eye"></i></span>
                        </div>
                    </div>
                    <div class="input-group">
                        <div class="label-wrapper">
                            <label for="confirm-password">Confirm Password</label>
                        </div>
                        <div class="password-wrapper">
                            <input type="password" name="confirm-password" placeholder="Confirm your password">
                            <span class="toggle-password"><i class="fas fa-eye"></i></span>
                        </div>
                    </div>
                    <div class="checkbox-group">
                        <input type="checkbox" name="terms">
                        <label for="terms">Accept all <a href="#">Terms of Use & Privacy Policy</a>.</label>
                    </div>
                    <button type="submit" class="signup-button">Sign up</button>
                </form>
                <div class="signin-link">
                    Have an account? <a href="login">Sign in</a>
                </div>
            </div>
        </div>
        <div class="image-section">
            <img src="assets/images/workerpic.png" alt="Signup Image" class="signup-image">
        </div>
    </div>

    <script>
        document.querySelectorAll('.toggle-password').forEach(item => {
            item.addEventListener('click', function () {
                const passwordField = this.previousElementSibling;
                const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordField.setAttribute('type', type);
                this.querySelector('i').classList.toggle('fa-eye');
                this.querySelector('i').classList.toggle('fa-eye-slash');
            });
        });

        function validatepassword() {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm-password').value;
            if (password !== confirmPassword) {
                alert('Passwords do not match');
            }
        }

    </script>
</body>
</html>

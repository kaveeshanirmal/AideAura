<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Request Verification</title>
  <style>
    input:invalid {
      border: 2px solid red;
    }
    input:valid {
      border: 2px solid green;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="title">Request For Verification</div>
    <div class="content">
      <form action="#" novalidate>
        <div class="user-details">
          <div class="input-box">
            <span class="details">Full Name (First Name & Last Name)</span>
            <input type="text" id="fullName" placeholder="Enter your full name" required minlength="3">
          </div>
          <div class="input-box">
            <span class="details">Username</span>
            <input type="text" id="userName" placeholder="Enter your username" required minlength="5">
          </div>
          <div class="input-box">
            <span class="details">Email</span>
            <input type="email" id="email" placeholder="Enter your email" required>
          </div>
          <div class="input-box">
            <span class="details">Phone Number</span>
            <input type="tel" id="telephone" placeholder="Enter your number" required pattern="^\d{10}$" title="Enter a valid 10-digit phone number">
          </div>
        </div>
        <div class="gender-details" id="">
          <input type="radio" name="gender" id="dot-1" value="male" required>
          <input type="radio" name="gender" id="dot-2" value="female">
          <input type="radio" name="gender" id="dot-3" value="prefer-not-to-say">
          <span class="gender-title">Gender</span>
          <div class="category">
            <label for="dot-1">
              <span class="dot one"></span>
              <span class="gender">Male</span>
            </label>
            <label for="dot-2">
              <span class="dot two"></span>
              <span class="gender">Female</span>
            </label>
            <label for="dot-3">
              <span class="dot three"></span>
              <span class="gender">Prefer not to say</span>
            </label>
          </div>
        </div>
        <span id="gender-error" style="color: red; display: none;">Please select your gender</span>
        
      <div class="input-box">
        <span class="details" id="languages">Language Skills</span>
        <div class="custom-select">
          <div class="options-list">
            <label><input type="checkbox" value="Sinhala"> Sinhala</label>
            <label><input type="checkbox" value="Tamil"> Tamil</label>
            <label><input type="checkbox" value="English"> English</label>
          </div>
        </div>
      </div>
      <span id="language-error" style="color: red; font-size: 12px; display: none;">Please select at least one language</span>

      

        <div class="user_buttons">
          <div class="next_button_container">
            <button class="next_button" id="back1">Back</button>
          </div>
          <div class="back_button_container">
            <button class="next_button" id="next1">Next</button>
          </div>
        </div>
      </form>
      
    </div>
  </div>
  <script src="<?=ROOT?>/public/assets/js/page1.js"></script>
</body>
</html>


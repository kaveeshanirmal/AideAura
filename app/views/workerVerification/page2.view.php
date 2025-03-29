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
            <span class="details">Home Town</span>
            <input type="text" id="hometown" placeholder="Enter your hometown" required minlength="3">
          </div>
  <!-- -->
            <div class="input-box">
            <span class="details">NIC / PassportID</span>
            <input type="text" id="idnumber" placeholder="200 xxx xxx xxx" required pattern="\d{12}">
        </div>
  <!-- -->
          <div class="input-box">
            <span class="details">Nationality</span>
            <select id="nationality" required>
              <option value="" disabled selected>Select your nationality</option>
              <option value="sinhalese">Sinhalese</option>
              <option value="tamil">Tamil</option>
              <option value="muslim">Muslim</option>
              <option value="burger">Burger</option>
              <option value="other">Other</option>
            </select>
          </div>
  <!-- -->
          <div class="input-box">
<!-- Make sure this HTML structure is exactly as shown here -->
<div class="form-group">
  <label>Language skills</label>
  <div class="custom-dropdown input-box" id="language-dropdown-container">
    <div class="dropdown-header" id="language-dropdown-header">
      Select languages
      <span class="dropdown-arrow">&#9662;</span>
    </div>
    <div class="dropdown-content" id="language-content">
      <div class="dropdown-item">
        <input type="checkbox" id="sinhala" name="language[]" value="Sinhala">
        <label for="sinhala">Sinhala</label>
      </div>
      <div class="dropdown-item">
        <input type="checkbox" id="tamil" name="language[]" value="Tamil">
        <label for="tamil">Tamil</label>
      </div>
      <div class="dropdown-item">
        <input type="checkbox" id="english" name="language[]" value="English">
        <label for="english">English</label>
      </div>
    </div>
  </div>
</div>
    </div>
          <div class="input-box">
            <span class="details">Age</span>
            <select id="age" required>
              <option value="" disabled selected>Select your age</option>
              <option value="18-25">18 - 25</option>
              <option value="26-35">26 - 35</option>
              <option value="36-50">36 - 50</option>
              <option value="above_50">Above 50</option>
            </select>
          </div>
          
          <div class="input-box">
            <span class="details">Service Type</span>
            <select id="service" required>
              <option value="" disabled selected>Select a service</option>
              <option value="babysitting">Babysitting</option>
              <option value="cleaning">Cleaning</option>
              <option value="gardening">Gardening</option>
              <option value="cooking">Cooking</option>
              <option value="housekeeping">House Keeping</option>
            </select>
          </div>

          <div class="input-box">
            <span class="details">Experience Level</span>
            <select id="experience" required>
              <option value="" disabled selected>Select your experience</option>
              <option value="entry">Entry Level</option>
              <option value="intermediate">Intermediate</option>
              <option value="expert">Expert</option>
            </select>
          </div>

          <div class="input-box full-width">
            <span class="details">Description</span>
                <textarea id="description" placeholder="Write a brief description about your qualifications" required></textarea>
          </div>

          <div class="input-box full-width">
            <span class="details">Upload Certificates (If Any) </span>
            <input type="file" id="certificates" accept=".pdf,.doc,.docx,.jpg,.png">
          </div>
        </div>
        
        
        <div class="user_buttons">
          <div class="next_button_container">
            <button class="next_button" id="back2">Back</button>
          </div>
          <div class="back_button_container">
            <button class="next_button" id="next2">Next</button>
          </div>
        </div>
      </form>
      
    </div>
  </div>
  <script src="<?=ROOT?>/public/assets/js/page2.js"></script>
</body>
</html>

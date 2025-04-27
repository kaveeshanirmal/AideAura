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
            <input type="text" id="idnumber" placeholder="200 xxx xxx xxx" required pattern="\d{12}" maxlength="12" inputmode="numeric">        </div>
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

          <div class="input-box">
            <span class="details">Work locations (Area You Can)</span>
            <select id="work-locations" required multiple>
              <option value="Ampara">Ampara</option>
              <option value="Anuradhapura">Anuradhapura</option>
              <option value="Badulla">Badulla</option>
              <option value="Batticaloa">Batticaloa</option>
              <option value="Colombo">Colombo</option>
              <option value="Galle">Galle</option>
              <option value="Gampaha">Gampaha</option>
              <option value="Hambantota">Hambantota</option>
              <option value="Jaffna">Jaffna</option>
              <option value="Kalutara">Kalutara</option>
              <option value="Kandy">Kandy</option>
              <option value="Kegalle">Kegalle</option>
              <option value="Kilinochchi">Kilinochchi</option>
              <option value="Kurunegala">Kurunegala</option>
              <option value="Mannar">Mannar</option>
              <option value="Matale">Matale</option>
              <option value="Matara">Matara</option>
              <option value="Monaragala">Monaragala</option>
              <option value="Mullaitivu">Mullaitivu</option>
              <option value="Nuwara Eliya">Nuwara Eliya</option>
              <option value="Polonnaruwa">Polonnaruwa</option>
              <option value="Puttalam">Puttalam</option>
              <option value="Ratnapura">Ratnapura</option>
              <option value="Trincomalee">Trincomalee</option>
              <option value="Vavuniya">Vavuniya</option>
            </select>
          </div>

          <div class="input-box full-width">
              <span class="details">Upload Certificates (If Any)</span>
              <input type="file" name="certificateFile" id="certificateFile" accept=".pdf,.doc,.docx,.jpg,.png">
          </div>

          <div class="input-box full-width">
              <span class="details">Medical and Fitness Certificate</span>
              <input type="file" name="medicalFile" id="medicalFile" accept=".pdf,.doc,.docx,.jpg,.png">
          </div>

          <div class="input-box full-width">
            <span class="details">Description</span>
                <textarea id="description" placeholder="Write a brief description about your qualifications" required></textarea>
          </div>

          <div class="input-box full-width">
            <span class="details">Bank Name and Branch code</span>
                <textarea id="bankNameCode" placeholder="Commercial Bank of Ceylon PLC 172" required></textarea>
          </div>


          <div class="input-box">
            <span class="details">Bank Account Number</span>
            <input type="text" id="accountNumber" placeholder="2002 xxxx xxxx xxxx" required pattern="\d{16}" maxlength="16" inputmode="numeric">
          </div>


          <div class="input-box">
              <span class="details">In Location Verification Code</span>
              <input type="text" id="locationVerificationCode" placeholder="Enter the code here" required pattern="\d{6}" maxlength="6" inputmode="numeric">        
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

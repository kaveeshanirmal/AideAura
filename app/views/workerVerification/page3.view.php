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
            <span class="details">Working Hours (Week Days)</span>
            <select id="workingWeekdays" required>
              <option value="" disabled selected>Select working hours</option>
              <option value="4-6">4 - 6 hours</option>
              <option value="7-9">7 - 9 hours</option>
              <option value="10-12">10 - 12 hours</option>
              <option value="above_12">More than 12 hours</option>
            </select>
          </div>
          
          <div class="input-box">
            <span class="details">Working Hours (Weekends)</span>
            <select id="workingWeekends" required>
              <option value="" disabled selected>Select working hours</option>
              <option value="4-6">4 - 6 hours</option>
              <option value="7-9">7 - 9 hours</option>
              <option value="10-12">10 - 12 hours</option>
              <option value="above_12">More than 12 hours</option>
            </select>
          </div>
          
          <div class="input-box full-width">
        <span class="details">Allergies or Physical Limitations</span>
            <textarea id="allergies" placeholder="I have an allergy to cats and dogs." required></textarea>
      </div>


          <div class="input-box full-width">
            <span class="details">Special Notes</span>
                <textarea id="notes" placeholder="I would like to mention that ..."></textarea>
          </div>

        </div>
        
        
        <div class="user_buttons">
          <div class="next_button_container">
            <button class="next_button" id="back3">Back</button>
          </div>
          <div class="back_button_container">
            <button class="next_button" id="submit">Submit</button>
          </div>
        </div>
      </form>
      
    </div>
  </div>
  <script src="<?=ROOT?>/public/assets/js/page3.js"></script>
</body>
</html>

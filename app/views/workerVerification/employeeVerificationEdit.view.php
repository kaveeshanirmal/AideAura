<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verification Request</title>
    <!-- Placeholder for dynamic CSS -->
    <link rel="stylesheet" href="<?=ROOT?>/public/assets/css/employeeVerificationForm/verificationRequestEdit.css">
    <script src="<?=ROOT?>/public/assets/js/employeeVerificationForm/employeeVerificationEdit.js" defer></script>
</head>
<body>
    <?php include(ROOT_PATH . '/app/views/components/navbar.view.php');?>
    <div class="container">
        <div class="title">Request For Verification</div>
        <form action="#" novalidate>
            <!-- Section: Basic Details -->
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
                <div class="input-box gender-details">
                    <span class="details">Gender</span>
                    <div class="category">
                        <label for="dot-1">
                            <input type="radio" name="gender" id="dot-1" value="male" required>
                            <span class="dot"></span>
                            <span class="gender">Male</span>
                        </label>
                        <label for="dot-2">
                            <input type="radio" name="gender" id="dot-2" value="female">
                            <span class="dot"></span>
                            <span class="gender">Female</span>
                        </label>
                        <label for="dot-3">
                            <input type="radio" name="gender" id="dot-3" value="prefer-not-to-say">
                            <span class="dot"></span>
                            <span class="gender">Prefer not to say</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Section: Additional Details -->
            <div class="user-details">
                <div class="input-box">
                    <span class="details">Home Town</span>
                    <input type="text" id="hometown" placeholder="Enter your hometown" required minlength="3">
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
                    <span class="details">Upload Certificates (If Any)</span>
                    <input type="file" id="certificates" accept=".pdf,.doc,.docx,.jpg,.png">
                </div>
            </div>

            <!-- Section: Work Preferences -->
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
                    <span class="details">Special Notes</span>
                    <textarea id="notes" placeholder="I would like to mention that ..."></textarea>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="user_buttons">
                <button class="next_button" id="submit">Submit</button>
            </div>
        </form>
    </div>
    <?php include(ROOT_PATH . '/app/views/components/footer.view.php'); ?>
</body>


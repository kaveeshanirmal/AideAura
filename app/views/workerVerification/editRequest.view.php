<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verification Request</title>
    <!-- Placeholder for dynamic CSS -->
    <link rel="stylesheet" href="<?=ROOT?>/public/assets/css/employeeVerificationForm/verificationRequestEdit.css">
</head>
<body>
    <?php include(ROOT_PATH . '/app/views/components/navbar.view.php');?>
    <div class="container">
        <div class="title">Request For Verification</div>
        <form class="verification-form" id="verificationForm" method="POST" action="<?=ROOT?>/public/workerVerification/update" enctype="multipart/form-data">
            <!-- Section: Basic Details -->
            <div class="user-details">
                <div class="input-box">
                    <span class="details">Full Name (First Name & Last Name)</span>
                    <input type="text" id="fullName" name="fullName" placeholder="Enter your full name" value="<?= htmlspecialchars($requestData->full_name ?? '') ?>" readonly>
                </div>
                <div class="input-box">
                    <span class="details">Username</span>
                    <input type="text" id="userName" name="userName" placeholder="Enter your username" value="<?= htmlspecialchars($requestData->username ?? '') ?>" readonly>
                </div>
                <div class="input-box">
                    <span class="details">Email</span>
                    <input type="email" id="email" name="email" placeholder="Enter your email" value="<?= htmlspecialchars($requestData->email ?? '') ?>" readonly>
                </div>
                <div class="input-box">
                    <span class="details">Phone Number</span>
                    <input type="tel" id="telephone" name="telephone" placeholder="Enter your number" value="<?= htmlspecialchars($requestData->phone_number ?? '') ?>" readonly>
                </div>
                <div class="input-box gender-details">
                    <span class="details">Gender</span>
                    <div class="category">
                        <label for="dot-1">
                            <input type="radio" name="gender" id="dot-1" value="male" 
                                <?= ($requestData->gender ?? '') === 'male' ? 'checked' : '' ?> 
                                onclick="return false;" readonly>
                            <span class="dot"></span>
                            <span class="gender">Male</span>
                        </label>
                        <label for="dot-2">
                            <input type="radio" name="gender" id="dot-2" value="female" 
                                <?= ($requestData->gender ?? '') === 'female' ? 'checked' : '' ?> 
                                onclick="return false;" readonly>
                            <span class="dot"></span>
                            <span class="gender">Female</span>
                        </label>
                        <label for="dot-3">
                            <input type="radio" name="gender" id="dot-3" value="prefer-not-to-say" 
                                <?= ($requestData->gender ?? '') === 'prefer-not-to-say' ? 'checked' : '' ?> 
                                onclick="return false;" readonly>
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
                    <input type="text" id="hometown" name="hometown" placeholder="Enter your hometown" value="<?= htmlspecialchars($requestData->hometown ?? '') ?>" readonly>
                </div>
                <div class="input-box">
                    <span class="details">Age</span>
                    <select id="age" name="age" disabled>
                        <option value="" disabled>Select your age</option>
                        <option value="18-25" <?= ($requestData->age ?? '') === '18-25' ? 'selected' : '' ?>>18 - 25</option>
                        <option value="26-35" <?= ($requestData->age ?? '') === '26-35' ? 'selected' : '' ?>>26 - 35</option>
                        <option value="36-50" <?= ($requestData->age ?? '') === '36-50' ? 'selected' : '' ?>>36 - 50</option>
                        <option value="above_50" <?= ($requestData->age ?? '') === 'above_50' ? 'selected' : '' ?>>Above 50</option>
                    </select>
                </div>
                <div class="input-box">
                    <span class="details">Service Type</span>
                    <select id="service" name="service" disabled>
                        <option value="" disabled>Select a service</option>
                        <option value="babysitting" <?= ($requestData->service ?? '') === 'babysitting' ? 'selected' : '' ?>>Babysitting</option>
                        <option value="cleaning" <?= ($requestData->service ?? '') === 'cleaning' ? 'selected' : '' ?>>Cleaning</option>
                        <option value="gardening" <?= ($requestData->service ?? '') === 'gardening' ? 'selected' : '' ?>>Gardening</option>
                        <option value="cooking" <?= ($requestData->service ?? '') === 'cooking' ? 'selected' : '' ?>>Cooking</option>
                        <option value="housekeeping" <?= ($requestData->service ?? '') === 'housekeeping' ? 'selected' : '' ?>>House Keeping</option>
                    </select>
                </div>
                <div class="input-box">
                    <span class="details">Experience Level</span>
                    <select id="experience" name="experience" disabled>
                        <option value="" disabled>Select your experience</option>
                        <option value="entry" <?= ($requestData->experience ?? '') === 'entry' ? 'selected' : '' ?>>Entry Level</option>
                        <option value="intermediate" <?= ($requestData->experience ?? '') === 'intermediate' ? 'selected' : '' ?>>Intermediate</option>
                        <option value="expert" <?= ($requestData->experience ?? '') === 'expert' ? 'selected' : '' ?>>Expert</option>
                    </select>
                </div>
                <div class="input-box full-width">
                    <span class="details">Description</span>
                    <textarea id="description" name="description" readonly><?= htmlspecialchars($requestData->description ?? '') ?></textarea>
                </div>
                <div class="input-box full-width">
                    <span class="details">Upload Certificates (If Any)</span>
                    <input type="file" id="certificates" name="certificates" accept=".pdf,.doc,.docx,.jpg,.png" disabled>
                </div>
            </div>

            <!-- Section: Work Preferences -->
            <div class="user-details">
                <div class="input-box">
                    <span class="details">Working Hours (Week Days)</span>
                    <select id="workingWeekdays" name="workingWeekdays" disabled>
                        <option value="" disabled>Select working hours</option>
                        <option value="4-6" <?= ($requestData->working_weekdays ?? '') === '4-6' ? 'selected' : '' ?>>4 - 6 hours</option>
                        <option value="7-9" <?= ($requestData->working_weekdays ?? '') === '7-9' ? 'selected' : '' ?>>7 - 9 hours</option>
                        <option value="10-12" <?= ($requestData->working_weekdays ?? '') === '10-12' ? 'selected' : '' ?>>10 - 12 hours</option>
                        <option value="above_12" <?= ($requestData->working_weekdays ?? '') === 'above_12' ? 'selected' : '' ?>>More than 12 hours</option>
                    </select>
                </div>
                <div class="input-box">
                    <span class="details">Working Hours (Weekends)</span>
                    <select id="workingWeekends" name="workingWeekends" disabled>
                        <option value="" disabled>Select working hours</option>
                        <option value="4-6" <?= ($requestData->working_weekends ?? '') === '4-6' ? 'selected' : '' ?>>4 - 6 hours</option>
                        <option value="7-9" <?= ($requestData->working_weekends ?? '') === '7-9' ? 'selected' : '' ?>>7 - 9 hours</option>
                        <option value="10-12" <?= ($requestData->working_weekends ?? '') === '10-12' ? 'selected' : '' ?>>10 - 12 hours</option>
                        <option value="above_12" <?= ($requestData->working_weekends ?? '') === 'above_12' ? 'selected' : '' ?>>More than 12 hours</option>
                    </select>
                </div>
                <div class="input-box full-width">
                    <span class="details">Special Notes</span>
                    <textarea id="notes" name="notes" readonly><?= htmlspecialchars($requestData->special_notes ?? '') ?></textarea>
                </div>
            </div>
            
            <!-- Edit Button -->
            <div class="user_buttons">
                <button type="button" class="next_button" id="edit-btn" onclick="makeEditable()">
                    Edit Application
                </button>
            </div>
            
            <!-- Submit Button (initially hidden) -->
            <div class="user_buttons" style="display: none;" id="submit-section">
                <button type="submit" class="next_button" id="submit-btn">Submit Changes</button>
            </div>
        </form>
    </div>
    
    <script>
    function makeEditable() {
        // Get all input, select, and textarea elements
        const inputs = document.querySelectorAll('input, select, textarea');
        const submitSection = document.getElementById('submit-section');
        const editBtn = document.getElementById('edit-btn');
        
        // Remove readonly and disabled attributes
        inputs.forEach(input => {
            if (input.hasAttribute('readonly')) {
                input.removeAttribute('readonly');
            }
            if (input.hasAttribute('disabled')) {
                input.removeAttribute('disabled');
            }
            
            // For radio buttons, re-enable clicking
            if (input.type === 'radio') {
                input.removeAttribute('onclick');
            }
        });
        
        // Show submit button, hide edit button
        submitSection.style.display = 'block';
        editBtn.style.display = 'none';
    }
    </script>
    
    <?php include(ROOT_PATH . '/app/views/components/footer.view.php'); ?>
</body>
</html>
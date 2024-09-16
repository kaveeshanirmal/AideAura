<!-- navbar -->
<?php include(ROOT_PATH . '/app/views/components/navbar.view.php'); ?>
<link rel="stylesheet" href="<?=ROOT?>/public/assets/css/personalInfo.css">

<div class="main-content">
    <h1>Personal Information</h1>
    <form class="personal-info-form" id="personalInfoForm" method="POST" action="<?=ROOT?>/public/profile/update" enctype="multipart/form-data">
        <div class="form-group">
            <label for="profileImage">Profile Photo</label>
            <div>
                <img id="profileImageDisplay" src="<?=ROOT?>/<?= htmlspecialchars($user->profileImage) ?>" alt="Profile Image" width="150px">
            </div>
            <input type="file" id="profileImage" name="profileImage" accept="image/*" class="hidden">
        </div>
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" id="name" name="name" value="<?= htmlspecialchars($user->name) ?>" readonly>
        </div>
        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" value="<?= htmlspecialchars($user->username) ?>" readonly>
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="<?= htmlspecialchars($user->email) ?>" readonly>
        </div>
        <div class="form-group">
            <label for="phone">Mobile Number</label>
            <input type="text" id="phone" name="phone" value="<?= htmlspecialchars($user->phoneNo) ?>" readonly>
        </div>
        <div class="form-group">
            <label for="address">Home Address</label>
            <input type="text" id="address" name="address" value="<?= htmlspecialchars($user->address) ?>" readonly>
        </div>
        <button type="button" id="editBtn" class="btn edit-btn">Edit Details</button>
        <button type="submit" id="submitBtn" class="btn submit-btn hidden">Submit</button>
        <button type="button" id="cancelBtn" class="btn cancel-btn hidden">Cancel</button>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('personalInfoForm');
    const editBtn = document.getElementById('editBtn');
    const submitBtn = document.getElementById('submitBtn');
    const cancelBtn = document.getElementById('cancelBtn');
    const inputs = form.querySelectorAll('input');
    const profileImageInput = document.getElementById('profileImage');
    const profileImageDisplay = document.getElementById('profileImageDisplay');

    let originalValues = {};

    // Enable edit mode
    editBtn.addEventListener('click', function() {
        inputs.forEach(input => {
            input.removeAttribute('readonly');
            originalValues[input.name] = input.value;
        });
        profileImageInput.classList.remove('hidden'); // Show image selector when in edit mode
        editBtn.classList.add('hidden');
        submitBtn.classList.remove('hidden');
        cancelBtn.classList.remove('hidden');
    });

    // Cancel changes
    cancelBtn.addEventListener('click', function() {
        inputs.forEach(input => {
            input.setAttribute('readonly', true);
            input.value = originalValues[input.name];
        });
        profileImageInput.classList.add('hidden'); // Hide image selector
        editBtn.classList.remove('hidden');
        submitBtn.classList.add('hidden');
        cancelBtn.classList.add('hidden');
    });

    // Preview new profile image before submitting
    profileImageInput.addEventListener('change', function() {
        const file = profileImageInput.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                profileImageDisplay.src = e.target.result;
            }
            reader.readAsDataURL(file);
        }
    });

    // Handle form submission
    submitBtn.addEventListener('click', function(event) {
        event.preventDefault(); // Prevent default form submission
        form.submit(); // Submit the form (image + other details)
    });
});
</script>

<!-- footer -->
<?php include(ROOT_PATH . '/app/views/components/footer.view.php'); ?>

document.addEventListener("DOMContentLoaded", function () {
  // Toggle password visibility
  document.querySelectorAll(".toggle-password").forEach((item) => {
    item.addEventListener("click", function () {
      const passwordField = this.previousElementSibling;
      const type =
        passwordField.getAttribute("type") === "password" ? "text" : "password";
      passwordField.setAttribute("type", type);
      this.querySelector("i").classList.toggle("fa-eye");
      this.querySelector("i").classList.toggle("fa-eye-slash");
    });
  });

  // Role buttons (Worker or Customer)
  const workerBtn = document.querySelector(".worker-btn");
  const customerBtn = document.querySelector(".customer-btn");

  workerBtn.addEventListener("click", () => {
    renderForm("worker");
  });

  customerBtn.addEventListener("click", () => {
    renderForm("customer");
  });

  // Validation functions
  function showError(input, message) {
    const formGroup = input.closest(".input-group");
    const errorDiv = formGroup.querySelector(".error-message");

    // Create error message element if it doesn't exist
    if (!errorDiv) {
      const newErrorDiv = document.createElement("div");
      newErrorDiv.className = "error-message";
      newErrorDiv.innerHTML = message;
      newErrorDiv.style.color = "red";
      newErrorDiv.style.fontSize = "12px";
      newErrorDiv.style.marginTop = "5px";
      formGroup.appendChild(newErrorDiv);
    } else {
      errorDiv.innerHTML = message;
      errorDiv.style.display = "block";
    }

    // Add error styling to input
    input.style.borderColor = "red";
  }

  function clearError(input) {
    const formGroup = input.closest(".input-group");
    const errorDiv = formGroup.querySelector(".error-message");

    if (errorDiv) {
      errorDiv.style.display = "none";
    }

    input.style.borderColor = "";
  }

  function validateName(input) {
    const nameRegex = /^[a-zA-Z\s]{2,30}$/;
    if (!nameRegex.test(input.value.trim())) {
      showError(
        input,
        "Name should contain only letters and be 2-30 characters",
      );
      return false;
    }
    clearError(input);
    return true;
  }

  function validateUsername(input) {
    const usernameRegex = /^[a-zA-Z0-9_]{4,20}$/;
    if (!usernameRegex.test(input.value.trim())) {
      showError(
        input,
        "Username should be 4-20 characters and contain only letters, numbers, and underscores",
      );
      return false;
    }
    clearError(input);
    return true;
  }

  function validateAddress(input) {
    if (input.value.trim().length < 5) {
      showError(input, "Please enter a valid address (minimum 5 characters)");
      return false;
    }
    clearError(input);
    return true;
  }

  function validatePhone(input) {
    // Phone number format example: 07x xxxx xxx
    const phoneRegex = /^07\d{8}$/;
    const value = input.value.replace(/\s/g, "");
    if (!phoneRegex.test(value)) {
      showError(
        input,
        "Please enter a valid phone number (e.g., 07x xxxx xxx)",
      );
      return false;
    }
    clearError(input);
    return true;
  }

  function validateEmail(input) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(input.value.trim())) {
      showError(input, "Please enter a valid email address");
      return false;
    }
    clearError(input);
    return true;
  }

  function validatePassword(input) {
    // Minimum 8 characters, at least one uppercase letter, one lowercase letter, one number
    const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,}$/;
    if (!passwordRegex.test(input.value)) {
      showError(
        input,
        "Password must be at least 8 characters with at least one uppercase letter, one lowercase letter, and one number",
      );
      return false;
    }
    clearError(input);
    return true;
  }

  function validateConfirmPassword(passwordInput, confirmInput) {
    if (passwordInput.value !== confirmInput.value) {
      showError(confirmInput, "Passwords do not match");
      return false;
    }
    clearError(confirmInput);
    return true;
  }

  // Render the form dynamically based on role
  function getForm(role, selectedServices = "") {
    const serviceInput = selectedServices
      ? `<input type="hidden" name="serviceType" value="${selectedServices}">`
      : "";

    const form = `
        <div class="signup-form">
            <h2 class="greeting">Get Started Now</h2>
            <form id="signup-form">
                <input type="hidden" id="selected-role" name="role" value="${role}">
                ${serviceInput} <!-- Conditionally include serviceType input -->
                <div class="input-group">
                    <label for="firstName">First Name</label>
                    <input type="text" name="firstName" placeholder="Enter your first name" required>
                </div>
                <div class="input-group">
                    <label for="lastName">Last Name</label>
                    <input type="text" name="lastName" placeholder="Enter your last name" required>
                </div>
                <div class="input-group">
                    <label for="username">Username</label>
                    <input type="text" name="username" placeholder="Enter your username" required id="username">
                </div>
                <div class="input-group">
                    <label for="address">Address</label>
                    <input type="text" name="address" placeholder="Enter your address" required>
                </div>
                <div class="input-group">
                    <label for="phone">Phone Number</label>
                    <input type="tel" name="phone" placeholder="07x xxxx xxx" required>
                </div>
                <div class="input-group">
                    <label for="email">Email Address</label>
                    <input type="email" name="email" placeholder="Enter your email address" required>
                </div>
                <div class="input-group">
                    <div class="label-wrapper">
                        <label for="password">Password</label>
                    </div>
                    <div class="password-wrapper">
                        <input type="password" name="password" placeholder="Enter your password" required>
                        <span class="toggle-password"><i class="fas fa-eye"></i></span>
                    </div>
                </div>
                <div class="input-group">
                    <div class="label-wrapper">
                        <label for="confirm-password">Confirm Password</label>
                    </div>
                    <div class="password-wrapper">
                        <input type="password" name="confirm-password" placeholder="Confirm your password" required>
                        <span class="toggle-password"><i class="fas fa-eye"></i></span>
                    </div>
                </div>
                <div class="checkbox-group">
                    <input type="checkbox" name="terms" required>
                    <label for="terms">Accept all <a href="#">Terms of Use & Privacy Policy</a>.</label>
                </div>
                <button type="submit" class="signup-button">Sign up</button>
            </form>
            <div class="signin-link">
                Have an account? <a href="login">Sign in</a>
            </div>
        </div>`;

    return form;
  }

  // Add event listeners for real-time validation and form submission
  function setupFormValidation() {
    const form = document.getElementById("signup-form");
    if (!form) return;

    // Add validation to inputs when they lose focus (blur event)
    const firstName = form.querySelector('input[name="firstName"]');
    const lastName = form.querySelector('input[name="lastName"]');
    const username = form.querySelector('input[name="username"]');
    const address = form.querySelector('input[name="address"]');
    const phone = form.querySelector('input[name="phone"]');
    const email = form.querySelector('input[name="email"]');
    const password = form.querySelector('input[name="password"]');
    const confirmPassword = form.querySelector(
      'input[name="confirm-password"]',
    );
    const terms = form.querySelector('input[name="terms"]');

    if (firstName)
      firstName.addEventListener("blur", () => validateName(firstName));
    if (lastName)
      lastName.addEventListener("blur", () => validateName(lastName));
    if (username)
      username.addEventListener("blur", () => validateUsername(username));
    if (address)
      address.addEventListener("blur", () => validateAddress(address));
    if (phone) phone.addEventListener("blur", () => validatePhone(phone));
    if (email) email.addEventListener("blur", () => validateEmail(email));
    if (password)
      password.addEventListener("blur", () => validatePassword(password));
    if (confirmPassword)
      confirmPassword.addEventListener("blur", () =>
        validateConfirmPassword(password, confirmPassword),
      );

    // Form submission handler
    form.addEventListener("submit", function (event) {
      event.preventDefault();

      // Validate all fields
      let isValid = true;

      if (firstName && !validateName(firstName)) isValid = false;
      if (lastName && !validateName(lastName)) isValid = false;
      if (username && !validateUsername(username)) isValid = false;
      if (address && !validateAddress(address)) isValid = false;
      if (phone && !validatePhone(phone)) isValid = false;
      if (email && !validateEmail(email)) isValid = false;
      if (password && !validatePassword(password)) isValid = false;
      if (
        confirmPassword &&
        !validateConfirmPassword(password, confirmPassword)
      )
        isValid = false;

      // Check if terms are accepted
      if (terms && !terms.checked) {
        const termsGroup = terms.closest(".checkbox-group");
        const errorMsg = document.createElement("div");
        errorMsg.className = "error-message";
        errorMsg.innerHTML =
          "You must accept the Terms of Use & Privacy Policy";
        errorMsg.style.color = "red";
        errorMsg.style.fontSize = "12px";
        errorMsg.style.marginTop = "5px";

        // Remove any existing error message before adding a new one
        const existingError = termsGroup.querySelector(".error-message");
        if (existingError) existingError.remove();

        termsGroup.appendChild(errorMsg);
        isValid = false;
      }

      if (isValid) {
        // Create an object to hold form data
        const formData = {
          role: document.querySelector('input[name="role"]').value,
          firstName: firstName ? firstName.value : "",
          lastName: lastName ? lastName.value : "",
          username: username ? username.value : "",
          address: address ? address.value : "",
          phone: phone ? phone.value : "",
          email: email ? email.value : "",
          password: password ? password.value : "",
          confirmPassword: confirmPassword ? confirmPassword.value : "",
          terms: terms ? terms.checked : false,
        };

        // Log the form data to the console
        console.log("Form data being submitted:", formData);

        // Optionally submit the form after logging
        // form.submit(); // Uncomment this to allow submission after validation passes
      } else {
        console.log("Form validation failed");
      }
    });
  }

  // Render the form based on role (customer or worker)
  function renderForm(role) {
    if (role === "customer") {
      const formContainer = document.querySelector(".signup-form-container");
      formContainer.innerHTML = getForm("customer");
      setupFormValidation(); // Add validation to the newly rendered form
    } else {
      const container = document.querySelector(".signup-form-container");
      container.innerHTML = getServiceTypes();

      const imageLabels = document.querySelectorAll(".image-label");

      // Set up the click listener for the image labels to toggle selection
      imageLabels.forEach((label) => {
        label.addEventListener("click", function () {
          const isSelected = this.getAttribute("data-selected") === "true";
          this.setAttribute("data-selected", !isSelected);
          this.querySelector(".service-btn").classList.toggle("selected");
        });
      });

      const nextBtn = document.getElementById("next-btn");
      nextBtn.addEventListener("click", () => {
        // Gather all selected services inside the button click event
        const selectedServices = [];
        imageLabels.forEach((label) => {
          if (label.getAttribute("data-selected") === "true") {
            const serviceName = label.querySelector(".label-text").innerText;
            selectedServices.push(serviceName);
          }
        });

        // Check if at least one service is selected
        const errorMessage = document.querySelector(".error-message");
        if (selectedServices.length === 0) {
          // Show error message if no services are selected
          errorMessage.style.display = "block";
          window.scrollTo(0, 0); // Scroll to the top of the page
          return; // Prevent proceeding to the next step
        } else {
          // Hide error message if services are selected
          errorMessage.style.display = "none";

          // Create a comma-separated string of selected services
          const selectedServicesString = selectedServices.join(",");
          const container = document.querySelector(".signup-form-container");
          container.innerHTML = getForm("worker", selectedServicesString);
          setupFormValidation(); // Add validation to the newly rendered form

          window.scrollTo(0, 0); // Scroll to the top of the page
        }
      });
    }
  }

  // Get the service selection form
  function getServiceTypes() {
    const serviceTypes = `
    <div class="signup-form">
        <h3>I Offer</h3>
        <h5>Select one or more services</h5>
        <p>You can always change this later</p>
        <div class="error-message" style="display: none;">
            <i class="fas fa-exclamation-circle"></i>
            <span class="error-text">Please select at least one service type</span>
        </div>
        <div class="signup-form-btn">
            <div class="image-label" data-selected="false" id="service1">
                <img src="assets/images/service_cook.png" alt="Service 1" class="service-btn">
                <span class="label-text">Cook</span>
            </div>
            <div class="image-label" data-selected="false" id="service2">
                <img src="assets/images/service_cook24.png" alt="Service 2" class="service-btn">
                <span class="label-text">Cook 24-hour Live in</span>
            </div>
            <div class="image-label" data-selected="false" id="service3">
                <img src="assets/images/service_maid.png" alt="Service 3" class="service-btn">
                <span class="label-text">Maid</span>
            </div>
            <div class="image-label" data-selected="false" id="service4">
                <img src="assets/images/service_nanny.png" alt="Service 4" class="service-btn">
                <span class="label-text">Nanny</span>
            </div>
            <div class="image-label" data-selected="false" id="service5">
                <img src="assets/images/service_allrounder.png" alt="Service 4" class="service-btn">
                <span class="label-text">All rounder</span>
            </div>
        </div>
        <button class="signup-button" id="next-btn" type="button">Next</button>
    </div>
    `;
    return serviceTypes;
  }
});

document.addEventListener("DOMContentLoaded", function () {
  // Set up event delegation for the container to handle dynamic elements
  const container = document.querySelector(".container");

  // Handle role selection (Worker or Customer)
  container.addEventListener("click", function (e) {
    if (
      e.target.classList.contains("worker-btn") ||
      e.target.closest(".worker-btn")
    ) {
      renderForm("worker");
    } else if (
      e.target.classList.contains("customer-btn") ||
      e.target.closest(".customer-btn")
    ) {
      renderForm("customer");
    }

    // Handle service selection
    if (
      e.target.classList.contains("service-btn") ||
      e.target.closest(".image-label")
    ) {
      const imageLabel = e.target.closest(".image-label");
      if (imageLabel && imageLabel.hasAttribute("data-selected")) {
        const isSelected = imageLabel.getAttribute("data-selected") === "true";
        imageLabel.setAttribute("data-selected", !isSelected);
        imageLabel.querySelector(".service-btn").classList.toggle("selected");
      }
    }

    // Handle next button in service selection
    if (e.target.id === "next-btn") {
      processServiceSelection();
    }

    // Handle toggle password visibility
    if (
      e.target.classList.contains("toggle-password") ||
      e.target.closest(".toggle-password")
    ) {
      const toggleBtn = e.target.closest(".toggle-password");
      const passwordField = toggleBtn.previousElementSibling;
      const type =
        passwordField.getAttribute("type") === "password" ? "text" : "password";
      passwordField.setAttribute("type", type);
      toggleBtn.querySelector("i").classList.toggle("fa-eye");
      toggleBtn.querySelector("i").classList.toggle("fa-eye-slash");
    }
  });

  // Form submission with validation
  container.addEventListener("submit", function (e) {
    if (e.target.id === "signup-form") {
      e.preventDefault();
      validateForm(e.target);
    }
  });

  // Input validation on blur
  container.addEventListener(
    "blur",
    function (e) {
      // Only validate fields that have lost focus (blur event)
      if (e.target.tagName === "INPUT") {
        validateField(e.target);
      }
    },
    true,
  ); // Use capturing phase to catch the event before it bubbles

  // Validation functions
  function showError(input, message) {
    const formGroup = input.closest(".input-group");
    if (!formGroup) return;

    // Remove any existing error message first
    clearError(input);

    const errorDiv = document.createElement("div");
    errorDiv.className = "error-message";
    errorDiv.innerHTML = message;
    errorDiv.style.color = "red";
    errorDiv.style.fontSize = "12px";
    errorDiv.style.marginTop = "5px";
    formGroup.appendChild(errorDiv);

    // Add error styling to input
    input.style.borderColor = "red";
  }

  function clearError(input) {
    const formGroup = input.closest(".input-group");
    if (!formGroup) return;

    const errorDiv = formGroup.querySelector(".error-message");
    if (errorDiv) {
      errorDiv.remove();
    }
    input.style.borderColor = "";
  }

  function validateField(input) {
    const name = input.name;

    switch (name) {
      case "firstName":
      case "lastName":
        return validateName(input);
      case "username":
        return validateUsername(input);
      case "address":
        return validateAddress(input);
      case "phone":
        return validatePhone(input);
      case "email":
        return validateEmail(input);
      case "password":
        return validatePassword(input);
      case "confirm-password":
        const passwordInput = input
          .closest("form")
          .querySelector('input[name="password"]');
        return validateConfirmPassword(passwordInput, input);
      case "terms":
        return validateTerms(input);
    }

    return true;
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

  function validateTerms(input) {
    if (!input.checked) {
      const termsGroup = input.closest(".checkbox-group");
      const errorMsg = document.createElement("div");
      errorMsg.className = "error-message";
      errorMsg.innerHTML = "You must accept the Terms of Use & Privacy Policy";
      errorMsg.style.color = "red";
      errorMsg.style.fontSize = "12px";
      errorMsg.style.marginTop = "5px";

      // Remove any existing error message before adding a new one
      const existingError = termsGroup.querySelector(".error-message");
      if (existingError) existingError.remove();

      termsGroup.appendChild(errorMsg);
      return false;
    }

    const termsGroup = input.closest(".checkbox-group");
    const existingError = termsGroup.querySelector(".error-message");
    if (existingError) existingError.remove();

    return true;
  }

  function validateForm(form) {
    let isValid = true;

    // Validate all inputs in the form
    const inputs = form.querySelectorAll("input");
    inputs.forEach((input) => {
      if (!validateField(input)) {
        isValid = false;
      }
    });

    if (isValid) {
      // Create an object to hold form data
      const formData = {
        role: form.querySelector('input[name="role"]').value,
      };

      // Add all input values to the form data
      inputs.forEach((input) => {
        if (input.type === "checkbox") {
          formData[input.name] = input.checked;
        } else {
          formData[input.name] = input.value;
        }
      });

      console.log("Form data being submitted:", formData);

      // Uncomment to actually submit the form
      // form.submit();
    } else {
      console.log("Form validation failed");
    }
  }

  function processServiceSelection() {
    const imageLabels = document.querySelectorAll(
      ".image-label[data-selected]",
    );
    const selectedServices = [];

    imageLabels.forEach((label) => {
      if (label.getAttribute("data-selected") === "true") {
        const serviceName = label.querySelector(".label-text").innerText;
        selectedServices.push(serviceName);
      }
    });

    const errorMessage = document.querySelector(".error-message");

    if (selectedServices.length === 0) {
      // Show error message if no services are selected
      if (errorMessage) {
        errorMessage.style.display = "block";
      }
      window.scrollTo(0, 0);
      return;
    }

    // Hide error message if services are selected
    if (errorMessage) {
      errorMessage.style.display = "none";
    }

    // Create a comma-separated string of selected services
    const selectedServicesString = selectedServices.join(",");
    const container = document.querySelector(".signup-form-container");
    container.innerHTML = getForm("worker", selectedServicesString);
    window.scrollTo(0, 0);
  }

  // Render the form based on role (customer or worker)
  function renderForm(role) {
    const formContainer = document.querySelector(".signup-form-container");

    if (role === "customer") {
      formContainer.innerHTML = getForm("customer");
    } else {
      formContainer.innerHTML = getServiceTypes();
    }

    window.scrollTo(0, 0);
  }

  // Get the service selection form
  function getServiceTypes() {
    return `
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
  }

  // Get the form based on role
  function getForm(role, selectedServices = "") {
    const serviceInput = selectedServices
      ? `<input type="hidden" name="serviceType" value="${selectedServices}">`
      : "";

    return `
      <div class="signup-form">
          <h2 class="greeting">Get Started Now</h2>
          <form id="signup-form">
              <input type="hidden" id="selected-role" name="role" value="${role}">
              ${serviceInput}
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
      </div>
    `;
  }
});

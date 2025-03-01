// Function to add error spans after each input field
function addErrorSpans() {
  const inputs = document.querySelectorAll(".input-box input");
  inputs.forEach((input) => {
    // Remove existing error span if it exists
    const existingErrorSpan =
      input.parentElement.querySelector(".error-message");
    if (existingErrorSpan) {
      existingErrorSpan.remove();
    }

    // Create and add new error span
    const errorSpan = document.createElement("span");
    errorSpan.className = "error-message";
    errorSpan.style.color = "red";
    errorSpan.style.display = "none";
    errorSpan.style.fontSize = "12px";
    errorSpan.style.marginTop = "5px";
    input.parentElement.appendChild(errorSpan);
  });
}

// Function to show error message with error handling
function showError(input, message) {
  try {
    const errorSpan = input.parentElement.querySelector(".error-message");
    if (!errorSpan) {
      // If error span doesn't exist, create it
      const newErrorSpan = document.createElement("span");
      newErrorSpan.className = "error-message";
      newErrorSpan.style.color = "red";
      newErrorSpan.style.fontSize = "12px";
      newErrorSpan.style.marginTop = "5px";
      input.parentElement.appendChild(newErrorSpan);
      newErrorSpan.textContent = message;
      newErrorSpan.style.display = "block";
    } else {
      errorSpan.textContent = message;
      errorSpan.style.display = "block";
    }
    input.style.borderColor = "red";
  } catch (error) {
    console.error("Error showing validation message:", error);
  }
}

// Function to hide error message with error handling
function hideError(input) {
  try {
    const errorSpan = input.parentElement.querySelector(".error-message");
    if (errorSpan) {
      errorSpan.style.display = "none";
      input.style.borderColor = "green";
    }
  } catch (error) {
    console.error("Error hiding validation message:", error);
  }
}

// Validation function
function validateInput(input) {
  const value = input.value;

  switch (input.getAttribute("placeholder")) {
    case "Enter your full name":
      const fullNamePattern = /^[a-zA-Z]+ [a-zA-Z]+$/;
      if (!fullNamePattern.test(value)) {
        showError(
          input,
          "Please enter your valid full name (First Name & Last Name)",
        );
        return false;
      }
      break;

    case "Enter your username":
      const usernamePattern = /^[a-zA-Z0-9_]{3,20}$/;
      if (!usernamePattern.test(value)) {
        showError(
          input,
          "Username must be 3-20 characters, only letters, numbers, or underscores",
        );
        return false;
      }
      break;

    case "Enter your email":
      const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      if (!emailPattern.test(value)) {
        showError(input, "Please enter a valid email address");
        return false;
      }
      break;

    case "Enter your number":
      if (!/^\d{10}$/.test(value)) {
        showError(input, "Please enter a valid 10-digit phone number");
        return false;
      }
      break;
  }

  hideError(input);
  return true;
}

// Initialize validation after DOM is fully loaded
function initializeValidation() {
  // First add error spans
  addErrorSpans();

  // Set up real-time validation
  document.querySelectorAll(".input-box input").forEach((input) => {
    input.addEventListener("input", function () {
      validateInput(this);
    });
  });
}

// Export validation function for navigation.js to use
window.validateForm = function () {
  let isValid = true;

  // Ensure error spans exist before validation
  addErrorSpans();

  // Validate all inputs
  document.querySelectorAll(".input-box input").forEach((input) => {
    if (!validateInput(input)) {
      isValid = false;
    }
  });

  // Validate gender selection
  const genderRadios = document.querySelectorAll('input[name="gender"]');
  const genderError = document.getElementById("gender-error");
  let genderSelected = false;

  genderRadios.forEach((radio) => {
    if (radio.checked) {
      genderSelected = true;
    }
  });

  if (!genderSelected) {
    genderError.style.display = "block";
    isValid = false;
  } else {
    genderError.style.display = "none";
  }

  return isValid;
};

// Initialize validation when DOM is loaded and when script is loaded
if (document.readyState === "loading") {
  document.addEventListener("DOMContentLoaded", initializeValidation);
} else {
  initializeValidation();
}

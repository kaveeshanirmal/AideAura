// Function to add error spans after each input field
function addErrorSpans() {
  const inputs = document.querySelectorAll(".input-box select, .input-box textarea");
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

function validateInput(input) {
  const value = input.value;

  switch (input.getAttribute("id")) {
    case "workingWeekdays":
      if (!value) {
        showError(input, "Please select working hours for week days!");
        return false;
      }
      break;

    case "workingWeekends":
      if (!value) {
        showError(input, "Please select working hours for weekends!");
        return false;
      }
      break;

    case "allergies":
      if (value.trim().length < 10) {
        showError(input, "Please provide a description of your allergies or not!");
        return false;
      }
      break;
      }

  hideError(input);
  return true;
}

function initializeValidation() {
  // First add error spans
  addErrorSpans();

  // Then set up real-time validation
  document.querySelectorAll(".input-box select").forEach((input) => {
    input.addEventListener("input", function () {
      validateInput(this);
    });
  });

  document.querySelectorAll(".input-box textarea").forEach((input) => {
    input.addEventListener("input", function () {
      validateInput(this);
    });
  });  
}

window.validateForm = function () {
  let isValid = true;

  // Ensure error spans exist before validation
  addErrorSpans();

  // Validate all inputs
  document.querySelectorAll(".input-box select").forEach((input) => {
    if (!validateInput(input)) {
      isValid = false;
    }
  });

  document.querySelectorAll(".input-box textarea").forEach((input) => {
    if (!validateInput(input)) {
      isValid = false;
    }
  });

  return isValid;
};

if (document.readyState === "loading") {
  document.addEventListener("DOMContentLoaded", initializeValidation);
} else {
  initializeValidation();
}

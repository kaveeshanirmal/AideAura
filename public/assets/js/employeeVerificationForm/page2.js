// Function to add error spans after each input field
function addErrorSpans() {
  const inputs = document.querySelectorAll(
    ".input-box input, .input-box textarea, .input-box select",
  );
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

// Function to validate individual inputs
function validateInput(input) {
  const value = input.value;

  switch (input.getAttribute("id")) {
    case "hometown":
      if (value.trim().length < 3) {
        showError(input, "Hometown must be at least 3 characters long!");
        return false;
      }
      break;

    case "age":
      if (!value) {
        showError(input, "Please select your age!");
        return false;
      }
      break;

    case "service":
      if (!value) {
        showError(input, "Please select a service type!");
        return false;
      }
      break;

    case "experience":
      if (!value) {
        showError(input, "Please select your experience level!");
        return false;
      }
      break;

    case "description":
      if (value.trim().length < 10) {
        showError(
          input,
          "Please provide a description of at least 10 characters!",
        );
        return false;
      }
      break;
  }

  hideError(input);

  // File Upload Validation (Optional)
  if (input.type === "file") {
    const fileInput = input;
    if (fileInput.files.length > 0) {
      const file = fileInput.files[0];
      const validExtensions = ["pdf", "doc", "docx", "jpg", "png"];
      const fileExtension = file.name.split(".").pop().toLowerCase();
      if (!validExtensions.includes(fileExtension)) {
        showError(
          input,
          "Only PDF, DOC, DOCX, JPG, and PNG files are allowed!",
        );
        return false;
      }
    }
  }

  return true;
}

// Initialize validation
function initializeValidation() {
  addErrorSpans();

  document
    .querySelectorAll(
      ".input-box input, .input-box textarea, .input-box select",
    )
    .forEach((input) => {
      input.addEventListener("input", function () {
        validateInput(this);
      });
    });
}

// Validate the entire form
window.validateForm = function () {
  let isValid = true;

  addErrorSpans();

  document
    .querySelectorAll(
      ".input-box input, .input-box textarea, .input-box select",
    )
    .forEach((input) => {
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

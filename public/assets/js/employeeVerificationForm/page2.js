// Function to add error spans after each input field
function addErrorSpans() {
  const inputs = document.querySelectorAll(
    ".input-box input, .input-box textarea, .input-box select",
  );
  inputs.forEach((input) => {
    const existingErrorSpan = input.parentElement.querySelector(".error-message");
    if (existingErrorSpan) existingErrorSpan.remove();

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
    let errorSpan = input.parentElement.querySelector(".error-message");
    if (!errorSpan) {
      errorSpan = document.createElement("span");
      errorSpan.className = "error-message";
      errorSpan.style.color = "red";
      errorSpan.style.fontSize = "12px";
      errorSpan.style.marginTop = "5px";
      input.parentElement.appendChild(errorSpan);
    }
    errorSpan.textContent = message;
    errorSpan.style.display = "block";
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

    case "idnumber":
      if (!/^\d{12}$/.test(value.trim())) {
        showError(input, "NIC/Passport number must be exactly 12 digits!");
        return false;
      }
      break;

    case "nationality":
      if (!value) {
        showError(input, "Please select your nationality!");
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

    case "work-locations":
      const selectedLocations = Array.from(input.selectedOptions);
      if (selectedLocations.length === 0) {
        showError(input, "Please select your work locations!");
        return false;
      }
      break;

    case "bankNameCode":
      if (!value) {
        showError(input, "Please provide your Bank Name and Branch Code!");
        return false;
      }
      break;

    case "accountNumber":
      if (!/^\d{16}$/.test(value.trim())) {
        showError(input, "Account number must be exactly 16 digits!");
        return false;
      }
      break;

    case "locationVerificationCode":
      if (!/^\d{6}$/.test(value.trim())) {
        showError(input, "Invalid in-location verification code");
        return false;
      }
      break;

    case "certificateFile":
      const certFileInput = input;
      if (certFileInput.files.length > 0) {
        const certFile = certFileInput.files[0];
        const validExtensions = ["pdf", "doc", "docx", "jpg", "png"];
        const certExtension = certFile.name.split(".").pop().toLowerCase();
        if (!validExtensions.includes(certExtension)) {
          showError(input, "Invalid file type for certificates!");
          return false;
        }
      }
      break;

    case "medicalFile":
      const medFileInput = input;
      if (medFileInput.files.length > 0) {
        const medFile = medFileInput.files[0];
        const validExtensions = ["pdf", "doc", "docx", "jpg", "png"];
        const medExtension = medFile.name.split(".").pop().toLowerCase();
        if (!validExtensions.includes(medExtension)) {
          showError(input, "Invalid file type for medical report!");
          return false;
        }
      }
      break;

    case "description":
      if (value.trim().length < 10) {
        showError(input, "Please provide a brief description (at least 10 characters).");
        return false;
      }
      break;

    default:
      break;
  }

  hideError(input);
  return true;
}

// Initialize validation
function initializeValidation() {
  addErrorSpans();

  document
    .querySelectorAll(".input-box input, .input-box textarea, .input-box select")
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
    .querySelectorAll(".input-box input, .input-box textarea, .input-box select")
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

// Language dropdown functionality
document.addEventListener('DOMContentLoaded', function () {
  const dropdownHeader = document.getElementById('language-dropdown-header');
  const dropdownContent = document.getElementById('language-content');

  if (!dropdownHeader || !dropdownContent) {
    console.error("Language dropdown elements not found!");
    return;
  }

  let dropdownArrow = dropdownHeader.querySelector('.dropdown-arrow');
  if (!dropdownArrow) {
    dropdownArrow = document.createElement('span');
    dropdownArrow.className = 'dropdown-arrow';
    dropdownArrow.innerHTML = '&#9662;';
    dropdownHeader.appendChild(dropdownArrow);
  }

  const checkboxes = dropdownContent.querySelectorAll('input[type="checkbox"]');

  dropdownHeader.addEventListener('click', function (event) {
    event.preventDefault();
    dropdownContent.classList.toggle('show');

    if (dropdownContent.classList.contains('show')) {
      dropdownArrow.style.transform = 'rotate(180deg)';
    } else {
      dropdownArrow.style.transform = 'rotate(0)';
    }
  });

  checkboxes.forEach(checkbox => {
    checkbox.addEventListener('change', function () {
      updateDropdownText();
    });
  });

  function updateDropdownText() {
    const selectedLanguages = [];
    checkboxes.forEach(checkbox => {
      if (checkbox.checked) {
        selectedLanguages.push(checkbox.value);
      }
    });

    if (selectedLanguages.length === 0) {
      dropdownHeader.textContent = 'Select languages';
      dropdownHeader.appendChild(dropdownArrow);
    } else if (selectedLanguages.length <= 2) {
      dropdownHeader.textContent = selectedLanguages.join(', ');
      dropdownHeader.appendChild(dropdownArrow);
    } else {
      dropdownHeader.textContent = `${selectedLanguages.length} languages selected`;
      dropdownHeader.appendChild(dropdownArrow);
    }
  }

  document.addEventListener('click', function (event) {
    if (!dropdownHeader.contains(event.target) && !dropdownContent.contains(event.target)) {
      dropdownContent.classList.remove('show');
      dropdownArrow.style.transform = 'rotate(0)';
    }
  });
});

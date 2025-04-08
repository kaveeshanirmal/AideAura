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
        showError(input,"Please provide your Bank Name and Branch Code!",
        );
        return false;
      }
      break;

      
case "accountNumber":
  if (!/^\d{16}$/.test(value.trim())) {
    showError(input, "Account number must be exactly 16 digits!");
    return false;
  }
  break;

      case "medical":
        const medicalFileInput = input;
        if (medicalFileInput.files.length === 0) {
          showError(input, "Medical and Fitness Certificate is required!");
          return false;
        } else {
          const medicalFile = medicalFileInput.files[0];
          const validExtensions = ["pdf", "doc", "docx", "jpg", "png"];
          const fileExtension = medicalFile.name.split(".").pop().toLowerCase();
          if (!validExtensions.includes(fileExtension)) {
            showError(input, "Only PDF, DOC, DOCX, JPG, and PNG files are allowed!");
            return false;
          }
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

//
// Language dropdown functionality
document.addEventListener('DOMContentLoaded', function() {
    // Make sure elements exist before adding event listeners
    const dropdownHeader = document.getElementById('language-dropdown-header');
    const dropdownContent = document.getElementById('language-content');
    
    if (!dropdownHeader || !dropdownContent) {
        console.error("Language dropdown elements not found!");
        return;
    }
    
    // Create arrow element if it doesn't exist
    let dropdownArrow = dropdownHeader.querySelector('.dropdown-arrow');
    if (!dropdownArrow) {
        dropdownArrow = document.createElement('span');
        dropdownArrow.className = 'dropdown-arrow';
        dropdownArrow.innerHTML = '&#9662;';
        dropdownHeader.appendChild(dropdownArrow);
    }
    
    // Ensure we get all checkboxes within the dropdown
    const checkboxes = dropdownContent.querySelectorAll('input[type="checkbox"]');
    
    // Make sure the click event works properly
    dropdownHeader.addEventListener('click', function(event) {
        event.preventDefault(); // Prevent any default behavior
        console.log("Dropdown header clicked"); // Debug log
        dropdownContent.classList.toggle('show');
        
        // Rotate arrow when dropdown is open
        if (dropdownContent.classList.contains('show')) {
            dropdownArrow.style.transform = 'rotate(180deg)';
        } else {
            dropdownArrow.style.transform = 'rotate(0)';
        }
    });
    
    // Rest of the code remains the same
    // Update header text based on selected items
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
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
    
    // Close dropdown when clicking outside
    document.addEventListener('click', function(event) {
        if (!dropdownHeader.contains(event.target) && !dropdownContent.contains(event.target)) {
            dropdownContent.classList.remove('show');
            dropdownArrow.style.transform = 'rotate(0)';
        }
    });
});
// Function to add error spans after each input field
function addErrorSpans() {
  const inputs = document.querySelectorAll(".input-box input .input-box textarea, .input-box select");
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
      

      case "hometown":
          if (value.trim().length < 3) {
              showError(input, "Hometown must be at least 3 characters long!");
              return false;
          }
          break;

      case "age":
          //const age = input.value;
          if (!value) {
              showError(input, "Please select your age!");
              return false;
          }
          break;

      case "service":
          //const serviceType = input.value;
          if (!value) {
              showError(input, "Please select a service type!");
              return false;
          }
          break;

      case "experience":
          //const experienceLevel = input.value;
          if (!value) {
              showError(input, "Please select your experience level!");
              return false;
          }
          break;

      case "description": // Assuming this is for the textarea description
          if (value.trim().length < 10) {
              showError(input, "Please provide a description of at least 10 characters!");
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
              showError(input, "Only PDF, DOC, DOCX, JPG, and PNG files are allowed!");
              return false;
          }
      }
  }

  
  return true;
}

// Save form data to sessionStorage
// function saveFormData() {
//     const formData = {};
//     document.querySelectorAll(".input-box input").forEach((input) => {
//         formData[input.id] = input.value; // Save input value using its ID
//     });
//     document.querySelectorAll(".input-box select").forEach((select) => {
//         formData[select.id] = select.value; // Save input value using its ID
//     });
//     document.querySelectorAll(".input-box textarea").forEach((textarea) => {
//         formData[textarea.id] = textarea.value; // Save input value using its ID
//     });
  
//     sessionStorage.setItem("formData", JSON.stringify(formData));
// }
  
// function restoreFormData() {
//     const savedData = sessionStorage.getItem("formData");
//     if (savedData) {
//        const formData = JSON.parse(savedData);
  
//        // Restore input values
//        Object.keys(formData).forEach((id) => {
//            const input = document.getElementById(id);
//            if (input) {
//                input.value = formData[id];
//            }
//        });
//     }
// }



function initializeValidation() {

  //restoreFormData();
  // First add error spans
  addErrorSpans();

  // Then set up real-time validation
  document.querySelectorAll(".input-box input, .input-box textarea, .input-box select").forEach((input) => {
      input.addEventListener("input", function () {
          validateInput(this);
          //saveFormData();
      });
  });
}

window.validateForm = function () {
  let isValid = true;

  // Ensure error spans exist before validation
  addErrorSpans();

  // Validate all inputs
  document.querySelectorAll(".input-box input, .input-box textarea, .input-box select").forEach((input) => {
      if (!validateInput(input)) {
          isValid = false;
      }

  
  });

  return isValid;

}

if (document.readyState === "loading") {
  document.addEventListener("DOMContentLoaded", initializeValidation);
} else {
  initializeValidation();
}

// document.querySelector("form").addEventListener("submit", function (e) {
//     e.preventDefault();
  
//     let isValid = true;
  
    
//     const homeTown = document.querySelector('input[placeholder="Enter your hometown"]');
//     if (homeTown.value.trim().length < 3) {
//       alert("Hometown must be at least 3 characters long!");
//       isValid = false;
//     }
  
//     // Age Validation
//     const age = document.querySelector('select[placeholder = "Select your age"]');
//     if (!age) {
//       alert("Please select your age!");
//       isValid = false;
//     }
  
//     // Service Type Validation
//     const serviceType = document.querySelector('select[placeholder = "Select a service"]');
//     if (!serviceType) {
//       alert("Please select a service type!");
//       isValid = false;
//     }
  
//     // Experience Level Validation
//     const experienceLevel = document.querySelector('select[placeholder = "Select your experience"]');
//     if (!experienceLevel) {
//       alert("Please select your experience level!");
//       isValid = false;
//     }
  
//     // Description Validation
//     const description = document.querySelector("textarea");
//     if (description.value.trim().length < 10) {
//       alert("Please provide a description of at least 10 characters!");
//       isValid = false;
//     }
  
//     // File Upload Validation (Optional)
//     const fileInput = document.querySelector('input[type="file"]');
//     if (fileInput.files.length > 0) {
//       const file = fileInput.files[0];
//       const validExtensions = ["pdf", "doc", "docx", "jpg", "png"];
//       const fileExtension = file.name.split(".").pop().toLowerCase();
//       if (!validExtensions.includes(fileExtension)) {
//         alert("Only PDF, DOC, DOCX, JPG, and PNG files are allowed!");
//         isValid = false;
//       }
//     }

    
//   if (isValid) {
//     alert("Form submitted successfully!");
    
//     // this.submit();
//   }
  
    
//   });
  

//   document.querySelectorAll("input, select, textarea").forEach((element) => {
//     element.addEventListener("input", function () {
//       if (this.checkValidity()) {
//         this.style.borderColor = "green";
//       } else {
//         this.style.borderColor = "red";
//       }
//     });
//   });
  
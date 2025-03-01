const content = document.getElementById("content");
const styleLink = document.getElementById("dynamic-styles");

// Helper function for navigation
function navigateToPage(currentPage, nextPage, cssFile, jsFile) {
  if (currentPage) saveFormData(currentPage); // Save current page's data
  loadPage(nextPage, cssFile, jsFile, () => {
    restoreFormData(nextPage); // Restore next page's data
  });
}

// Load and render the specified page
function loadPage(page, cssFile, jsFile, callback) {
  console.log("Loading page:", page);
  fetch(`${ROOT}/app/views/workerVerification/${page}.view.php`)
    .then((response) => {
      if (!response.ok) throw new Error("Page not found");
      return response.text();
    })
    .then((html) => {
      content.innerHTML = html;
      styleLink.href = `${ROOT}/public/assets/css/employeeVerificationForm/${cssFile}.css`;
      loadScript(jsFile, callback);
    })
    .catch((err) => console.error(err));
}

// Dynamically load a JavaScript file
function loadScript(jsFile, callback) {
  const script = document.createElement("script");
  script.src = `${ROOT}/public/assets/js/employeeVerificationForm/${jsFile}.js`;
  script.type = "text/javascript";

  script.onload = () => {
    console.log(`${jsFile} loaded successfully!`);
    addEventListeners(jsFile);
    if (callback) callback();
  };

  script.onerror = (error) => {
    console.error(`Failed to load script: ${jsFile}`, error);
  };

  document.body.appendChild(script);
}

// Add event listeners for page-specific navigation
function addEventListeners(page) {
  if (page === "page1") {
    document.getElementById("next1").addEventListener("click", (event) => {
      event.preventDefault();
      if (typeof window.validateForm === "function" && window.validateForm()) {
        navigateToPage("page1", "page2", "page2", "page2");
      } else {
        console.log("Form validation failed");
      }
    });

    const backButton = document.getElementById("back1");
    backButton.addEventListener("click", (event) => {
      console.log("Back button 1 pressed");
      event.preventDefault();
      window.history.back(); // Navigate to the previous page
    });
  } else if (page === "page2") {
    document.getElementById("back2").addEventListener("click", (event) => {
      event.preventDefault();
      navigateToPage("page2", "page1", "page1", "page1");
    });

    document.getElementById("next2").addEventListener("click", (event) => {
      event.preventDefault();
      if (typeof window.validateForm === "function" && window.validateForm()) {
        navigateToPage("page2", "page3", "page3", "page3");
      } else {
        console.log("Form validation failed");
      }
    });
  } else if (page === "page3") {
    document.getElementById("back3").addEventListener("click", (event) => {
      event.preventDefault();
      navigateToPage("page3", "page2", "page2", "page2");
    });

    document.getElementById("submit").addEventListener("click", (event) => {
      event.preventDefault();
      if (typeof window.validateForm === "function" && window.validateForm()) {
        submitForm();
        clearFormData(); // Clear all data on successful submission
      } else {
        console.log("Form validation failed");
      }
    });
  }
}

// Initial page load
navigateToPage(null, "page1", "page1", "page1");

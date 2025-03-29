const ROOT = "<?php echo ROOT; ?>";

document.addEventListener("DOMContentLoaded", function () {
  // Back button functionality
  const backButton = document.getElementById("back-btn");
  backButton.addEventListener("click", (event) => {
    event.preventDefault();
    window.history.back(); // Navigate to the previous page
  });

  // Modal and map elements
  const modal = document.getElementById("map-modal");
  const closeModal = document.querySelector(".close-modal");
  const pickLocationBtn = document.getElementById("pick-location");
  const serviceLocationInput = document.getElementById("service-location");
  let map, marker;

  // Clear all error messages function
  function clearErrorMessages() {
    const errorMessages = document.querySelectorAll(".error-message");
    errorMessages.forEach((message) => {
      message.style.display = "none";
    });
  }

  // Show error message function
  function showError(elementId, show) {
    const errorElement = document.getElementById(elementId);
    if (errorElement) {
      errorElement.style.display = show ? "block" : "none";
    }
  }

  // Map button click handler
  if (pickLocationBtn && modal && serviceLocationInput) {
    pickLocationBtn.addEventListener("click", function () {
      console.log("Pick Location button clicked");
      modal.style.display = "block";
      // Initialize map after modal is displayed
      setTimeout(initMap, 100);
    });

    closeModal.addEventListener("click", function () {
      modal.style.display = "none";
    });

    window.onclick = function (event) {
      if (event.target === modal) {
        modal.style.display = "none";
      }
    };
  } else {
    console.error("Map elements not found!");
  }

  // Initialize Leaflet map
  function initMap() {
    console.log("Initializing map...");

    if (map) {
      map.remove(); // Remove existing map instance
    }

    // Default location (Colombo, Sri Lanka) in case geolocation fails
    const defaultLocation = [6.9271, 79.8612];

    map = L.map("map").setView(defaultLocation, 13);

    L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
      attribution: "&copy; OpenStreetMap contributors",
    }).addTo(map);

    marker = L.marker(defaultLocation, { draggable: true }).addTo(map);

    // Try to get the user's current location
    if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition(
        (position) => {
          const userLocation = [
            position.coords.latitude,
            position.coords.longitude,
          ];
          console.log("User's current location:", userLocation);

          map.setView(userLocation, 13);
          marker.setLatLng(userLocation);
        },
        (error) => {
          console.error("Geolocation error:", error);
        },
      );
    } else {
      console.error("Geolocation is not supported by this browser.");
    }

    marker.on("dragend", function () {
      const latlng = marker.getLatLng();
      console.log("Marker moved to:", latlng);

      fetch(
        `https://nominatim.openstreetmap.org/reverse?format=json&lat=${latlng.lat}&lon=${latlng.lng}`,
      )
        .then((response) => response.json())
        .then((data) => {
          if (data.display_name) {
            console.log("Address found:", data.display_name);
            document.getElementById("service-location").value =
              data.display_name;
          } else {
            alert("Could not fetch address.");
          }
        })
        .catch((error) => console.error("Error fetching address:", error));
    });
  }

  // Toggle collapsible sections
  const toggleButtons = document.querySelectorAll(".toggle-btn");

  toggleButtons.forEach((button) => {
    button.addEventListener("click", function () {
      const section = this.closest(".collapsible-section");
      const content = section.querySelector(".section-content");
      const summary = section.querySelector(".section-summary");

      // Toggle classes for animation
      content.classList.toggle("collapsed");
      summary.classList.toggle("hidden");
      this.classList.toggle("expanded");

      // Update the form values in summary when collapsing
      if (content.classList.contains("collapsed")) {
        updateSummaryValues(section);
      }
    });
  });

  // Update summary values function
  function updateSummaryValues(section) {
    const summaryValues = section.querySelectorAll(".summary-value");

    summaryValues.forEach((summary) => {
      const fieldId = summary.getAttribute("data-field");
      const input = document.getElementById(fieldId);
      if (input && input.value) {
        summary.textContent = input.value;
      }
    });
  }

  // Form total calculation
  const form = document.querySelector("form");
  const totalAmount = document.querySelector(".total-amount");

  form.addEventListener("change", function () {
    const formData = new FormData(form);

    fetch(`${ROOT}/public/selectService/cookingService`, {
      method: "POST",
      body: formData,
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.total) {
          totalAmount.textContent = `Rs. ${data.total}.00`;
        }
      })
      .catch((error) => console.error("Error:", error));
  });

  // Add event listeners for real-time validation
  const fullName = document.getElementById("customer-name");
  const phoneNumber = document.getElementById("contact-phone");
  const email = document.getElementById("contact-email");
  const serviceLocation = document.getElementById("service-location");
  const preferredDate = document.getElementById("preferred-date");
  const arrivalTime = document.getElementById("arrival-time");
  const acknowledgment = document.getElementById("data-acknowledgment");

  // Clear errors when fields are changed
  fullName.addEventListener("input", () => showError("name-error", false));
  phoneNumber.addEventListener("input", () => showError("phone-error", false));
  email.addEventListener("input", () => showError("email-error", false));
  serviceLocation.addEventListener("input", () =>
    showError("location-error", false),
  );
  preferredDate.addEventListener("change", () =>
    showError("date-error", false),
  );
  arrivalTime.addEventListener("change", () => showError("time-error", false));
  acknowledgment.addEventListener("change", () =>
    showError("acknowledgment-error", false),
  );

  // Form validation
  const nxtBtn = document.getElementById("nxt-btn");
  nxtBtn.addEventListener("click", function () {
    let isValid = true;

    // Clear all errors first
    clearErrorMessages();

    // Full Name Validation (Non-empty)
    if (fullName.value.trim() === "") {
      showError("name-error", true);
      isValid = false;
    }

    // Phone Number Validation (10-digit)
    const phonePattern = /^\d{10}$/;
    if (!phonePattern.test(phoneNumber.value.replace(/\s+/g, ""))) {
      showError("phone-error", true);
      isValid = false;
    }

    // Email Validation
    const emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    if (!emailPattern.test(email.value)) {
      showError("email-error", true);
      isValid = false;
    }

    // Service Location Validation (Non-empty)
    if (serviceLocation.value.trim() === "") {
      showError("location-error", true);
      isValid = false;
    }

    // Preferred Date Validation (Future Date)
    const today = new Date().toISOString().split("T")[0];
    if (preferredDate.value < today) {
      showError("date-error", true);
      isValid = false;
    }

    // Arrival Time Validation (Future Time on the same day)
    if (preferredDate.value === today) {
      const now = new Date();
      const currentHour = now.getHours();
      const currentMinute = now.getMinutes();

      const [timeHour, timeMinute] = arrivalTime.value.split(":").map(Number);

      if (
        timeHour < currentHour ||
        (timeHour === currentHour && timeMinute <= currentMinute)
      ) {
        showError("time-error", true);
        isValid = false;
      }
    }

    // Checkbox Validation
    if (!acknowledgment.checked) {
      showError("acknowledgment-error", true);
      isValid = false;
    }

    if (isValid) {
      // Proceed to payment page or form submission
      console.log("Form is valid! Proceeding to payment.");
      // form.submit(); // Uncomment this to actually submit the form
    } else {
      // Scroll to the first error
      const firstError = document.querySelector(
        '.error-message[style="display: block;"]',
      );
      if (firstError) {
        firstError.scrollIntoView({ behavior: "smooth", block: "center" });
      }
    }
  });

  // Prevent navigation without confirmation
  window.addEventListener("beforeunload", function (event) {
    event.preventDefault();
    event.returnValue =
      "Are you sure you want to leave? Changes may not be saved.";
  });
});

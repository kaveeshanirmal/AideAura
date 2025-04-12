document.addEventListener("DOMContentLoaded", function () {
  // Back button functionality
  const backButton = document.getElementById("back-btn");
  backButton.addEventListener("click", (event) => {
    event.preventDefault();
    window.history.back();
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

    window.addEventListener("click", function (event) {
      if (event.target === modal) {
        modal.style.display = "none";
      }
    });
  } else {
    console.error("Map elements not found!");
  }

  // Initialize Leaflet map
  function initMap() {
    console.log("Initializing map...");

    if (map) {
      map.remove();
    }

    const defaultLocation = [6.9271, 79.8612];
    map = L.map("map").setView(defaultLocation, 13);

    L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
      attribution: "&copy; OpenStreetMap contributors",
    }).addTo(map);

    marker = L.marker(defaultLocation, { draggable: true }).addTo(map);

    if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition(
        (position) => {
          const userLocation = [
            position.coords.latitude,
            position.coords.longitude,
          ];
          map.setView(userLocation, 13);
          marker.setLatLng(userLocation);
          updateAddressFromMarker();
        },
        (error) => {
          console.error("Geolocation error:", error);
        },
      );
    }

    marker.on("dragend", updateAddressFromMarker);
  }

  function updateAddressFromMarker() {
    const latlng = marker.getLatLng();
    fetch(
      `https://nominatim.openstreetmap.org/reverse?format=json&lat=${latlng.lat}&lon=${latlng.lng}`,
    )
      .then((response) => response.json())
      .then((data) => {
        if (data.display_name) {
          serviceLocationInput.value = data.display_name;
        }
      })
      .catch((error) => console.error("Error fetching address:", error));
  }

  // Toggle collapsible sections
  const toggleButtons = document.querySelectorAll(".toggle-btn");
  toggleButtons.forEach((button) => {
    button.addEventListener("click", function () {
      const section = this.closest(".collapsible-section");
      const content = section.querySelector(".section-content");
      const summary = section.querySelector(".section-summary");

      content.classList.toggle("collapsed");
      summary.classList.toggle("hidden");
      this.classList.toggle("expanded");

      if (content.classList.contains("collapsed")) {
        updateSummaryValues(section);
      }
    });
  });

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

  // Form elements
  const form = document.getElementById("bookingInfoForm");
  const totalAmount = document.querySelector(".total-amount");
  const fullName = document.getElementById("customer-name");
  const phoneNumber = document.getElementById("contact-phone");
  const email = document.getElementById("contact-email");
  const serviceLocation = document.getElementById("service-location");
  const preferredDate = document.getElementById("preferred-date");
  const arrivalTime = document.getElementById("arrival-time");
  const acknowledgment = document.getElementById("data-acknowledgment");
  const proceedToPaymentButton = document.getElementById("nxt-btn");

  // Clear errors when fields are changed
  [
    fullName,
    phoneNumber,
    email,
    serviceLocation,
    preferredDate,
    arrivalTime,
    acknowledgment,
  ].forEach((field) => {
    if (field) {
      const errorId = field.id + "-error";
      field.addEventListener("input", () => showError(errorId, false));
    }
  });

  // Form validation function
  function validateForm() {
    let isValid = true;
    clearErrorMessages();

    // Name validation
    if (fullName.value.trim() === "") {
      showError("name-error", true);
      isValid = false;
    }

    // Phone validation
    const phonePattern = /^\d{10}$/;
    if (!phonePattern.test(phoneNumber.value.replace(/\s+/g, ""))) {
      showError("phone-error", true);
      isValid = false;
    }

    // Email validation
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailPattern.test(email.value)) {
      showError("email-error", true);
      isValid = false;
    }

    // Location validation
    if (serviceLocation.value.trim() === "") {
      showError("location-error", true);
      isValid = false;
    }

    // Date validation
    const today = new Date().toISOString().split("T")[0];
    if (preferredDate.value < today) {
      showError("date-error", true);
      isValid = false;
    }

    // Time validation
    if (preferredDate.value === today) {
      const now = new Date();
      const [timeHour, timeMinute] = arrivalTime.value.split(":").map(Number);

      if (
        timeHour < now.getHours() ||
        (timeHour === now.getHours() && timeMinute <= now.getMinutes())
      ) {
        showError("time-error", true);
        isValid = false;
      }
    }

    // Acknowledgment validation
    if (!acknowledgment.checked) {
      showError("acknowledgment-error", true);
      isValid = false;
    }

    if (!isValid) {
      const firstError = document.querySelector(
        '.error-message[style="display: block;"]',
      );
      firstError?.scrollIntoView({ behavior: "smooth", block: "center" });
    }

    return isValid;
  }

  // Form submission handler
  // Remove the form submit handler and only handle the button click
  proceedToPaymentButton.addEventListener("click", async function (e) {
    e.preventDefault();

    if (!validateForm()) {
      return;
    }

    const submitBtn = document.getElementById("nxt-btn");
    submitBtn.disabled = true;
    submitBtn.textContent = "Processing...";

    try {
      const formData = new FormData(form);
      const response = await fetch(
        `${ROOT}/public/selectService/submitBookingInfo`,
        {
          method: "POST",
          body: formData,
          headers: {
            Accept: "application/json",
          },
        },
      );

      // First check if the response is JSON
      const contentType = response.headers.get("content-type");
      if (!contentType || !contentType.includes("application/json")) {
        const text = await response.text();
        console.error("Expected JSON, got:", text);
        throw new Error("Server did not return JSON");
      }

      const data = await response.json();

      if (data.success) {
        console.log("Form submitted successfully:", data);
        window.location.href = `${ROOT}/public/selectService/bookingSummary`;
      } else {
        alert(data.message || "Error submitting form");
      }
    } catch (error) {
      console.error("Error:", error);
      alert("An error occurred. Please try again.");
    } finally {
      submitBtn.disabled = false;
      submitBtn.textContent = "Booking Summary";
    }
  });
});

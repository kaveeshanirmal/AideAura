function pollForNewRequests() {
  fetch(`${ROOT}/public/dashboard/getJobRequests`, {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify({}),
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.status === "success" && data.data.length > 0) {
        // Store the requests in a global variable
        window.latestRequests = data.data;
        console.log("Saved the bookingData to the window", data);
        updateJobRequestsList(data.data);
      }
    })
    .catch((error) => {});
}

// Update job requests list with new data
function updateJobRequestsList(requests) {
  const jobRequestList = document.getElementById("jobRequestList");

  if (!requests || requests.length === 0) {
    jobRequestList.innerHTML = `
        <div class="no-jobs-message">
            <i class='bx bx-info-circle'></i>
            <p>No new job requests available at the moment.</p>
        </div>
    `;
    return;
  }

  // Get existing booking IDs to avoid duplicates
  const existingBookingIds = Array.from(
    document.querySelectorAll(".job-request-item"),
  ).map((item) => item.getAttribute("data-booking-id"));

  let newItemsAdded = false;

  requests.forEach((request) => {
    let bookingData = request.value.booking;
    // const bookingId = bookingData.bookingID;
    const bookingId = request.key;
    // const bookingDetails = request.value.details;
    const bookingIdStr = String(bookingId);

    // Skip if this booking ID already exists in the DOM
    if (existingBookingIds.includes(bookingIdStr)) {
      return;
    }

    newItemsAdded = true;

    const dateValue = bookingData.bookingDate;
    const dateObj = dateValue ? new Date(dateValue) : new Date();
    const serviceType = bookingData.serviceType;
    const location = bookingData.location;

    // Create a new item element
    const newItem = document.createElement("div");
    newItem.className = "job-request-item";
    newItem.setAttribute("data-booking-id", bookingIdStr);

    newItem.innerHTML = `
        <div class="job-date">
            <div class="job-date-day">${dateObj.getDate()}</div>
            <div class="job-date-weekday">${dateObj.toLocaleDateString("en-US", { weekday: "short" })}</div>
        </div>
        <div class="job-info">
            <h4 class="job-title">${serviceType}</h4>
            <div class="job-location">
                <i class='bx bx-map'></i>
                <span>${location}</span>
            </div>
        </div>
        <div class="job-actions">
            <button class="btn-view" onclick="viewJobDetails(this.closest('.job-request-item'), ${bookingIdStr})">
                <i class='bx bx-show'></i> View
            </button>
            <button class="btn-accept" onclick="acceptJob(this, ${bookingIdStr})">
                <i class='bx bx-check'></i> Accept
            </button>
            <button class="btn-deny" onclick="denyJob(this, ${bookingIdStr})">
                <i class='bx bx-x'></i> Decline
            </button>
        </div>
    `;

    // Remove no-jobs-message if it exists
    const noJobsMessage = jobRequestList.querySelector(".no-jobs-message");
    if (noJobsMessage) {
      jobRequestList.removeChild(noJobsMessage);
    }

    // Add the new item at the beginning of the list
    if (jobRequestList.firstChild) {
      jobRequestList.insertBefore(newItem, jobRequestList.firstChild);
    } else {
      jobRequestList.appendChild(newItem);
    }
  });

  // If no items were added and no existing items are present
  if (!newItemsAdded && existingBookingIds.length === 0) {
    jobRequestList.innerHTML = `
        <div class="no-jobs-message">
            <i class='bx bx-info-circle'></i>
            <p>No new job requests available at the moment.</p>
        </div>
    `;
  }
}

// Start polling when page loads
document.addEventListener("DOMContentLoaded", function () {
  // Poll every 20 seconds
  setInterval(pollForNewRequests, 20000);
});

// Toggle availability status
function toggleAvailability() {
  const button = document.getElementById("availability");
  const isAvailable = button.classList.contains("available");

  fetch(`${ROOT}/public/dashboard/availability`, {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify({
      availability: isAvailable ? "offline" : "online",
    }),
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.status === "success") {
        const icon = button.querySelector("i");

        if (isAvailable) {
          // Switch to Not Available state
          button.classList.remove("available");
          button.classList.add("not-available");
          icon.classList.remove("bx-check-circle");
          icon.classList.add("bx-x-circle");
          button.innerHTML = '<i class="bx bx-x-circle"></i> Not Available';
        } else {
          // Switch to Available state
          button.classList.remove("not-available");
          button.classList.add("available");
          icon.classList.remove("bx-x-circle");
          icon.classList.add("bx-check-circle");
          button.innerHTML = '<i class="bx bx-check-circle"></i> Available';
        }
      } else {
        alert("Failed to update availability");
      }
    })
    .catch((error) => {
      console.error("Error:", error);
      alert("Failed to update availability");
    });
}

// Format city input
function formatCity() {
  const cityInput = document.getElementById("new-location");
  if (cityInput.value) {
    // First trim any extra whitespace
    cityInput.value = cityInput.value.trim();

    // Split the string by spaces to get individual words
    const words = cityInput.value.split(" ");

    // Capitalize the first letter of each word
    const capitalizedWords = words.map((word) => {
      if (word.length > 0) {
        return word.charAt(0).toUpperCase() + word.slice(1).toLowerCase();
      }
      return word;
    });

    // Join the words back together with spaces
    cityInput.value = capitalizedWords.join(" ");
  }
}

// Submit location
function submitLocation() {
  formatCity();
  const newLocation = document.getElementById("new-location").value;

  if (!newLocation) {
    alert("Please enter a new location");
    return;
  }

  fetch(`${ROOT}/public/dashboard/updateLocation`, {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify({
      newLocation: newLocation,
    }),
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.status === "success") {
        const locationField = document.querySelector(".current-location");
        locationField.textContent = newLocation;
        alert("Location updated successfully");
      } else {
        alert("Failed to update location");
      }
    })
    .catch((error) => {
      console.error("Error:", error);
      alert("Failed to update location");
    });
}

// Accept job request
function acceptJob(button, bookingId) {
  const jobItem = button.closest(".job-request-item");
  const jobTitle = jobItem.querySelector(".job-title").textContent;

  // Add visual feedback
  jobItem.style.backgroundColor = "rgba(76, 175, 80, 0.1)";
  button.innerHTML = '<i class="bx bx-check-double"></i> Accepted';
  button.disabled = true;

  // Disable the deny button
  const denyButton = jobItem.querySelector(".btn-deny");
  denyButton.disabled = true;

  // Send acceptance to server
  fetch(`${ROOT}/public/booking/accept`, {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify({
      bookingID: bookingId,
    }),
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.status !== "success") {
        alert("Failed to accept booking");
        // Revert UI changes if failed
        button.innerHTML = '<i class="bx bx-check"></i> Accept';
        button.disabled = false;
        denyButton.disabled = false;
        jobItem.style.backgroundColor = "";
      } else {
        setTimeout(() => {
          jobItem.style.opacity = "0.6";
        }, 1000);
      }
    })
    .catch((error) => {
      console.error("Error:", error);
      alert("Failed to accept booking");
      // Revert UI changes if failed
      button.innerHTML = '<i class="bx bx-check"></i> Accept';
      button.disabled = false;
      denyButton.disabled = false;
      jobItem.style.backgroundColor = "";
    });
}

// Deny job request
function denyJob(button, bookingId) {
  const jobItem = button.closest(".job-request-item");
  const jobTitle = jobItem.querySelector(".job-title").textContent;

  if (!confirm(`Are you sure you want to decline the "${jobTitle}" request?`)) {
    return;
  }

  // Add visual feedback
  jobItem.style.backgroundColor = "rgba(244, 67, 54, 0.1)";
  button.innerHTML = '<i class="bx bx-x-circle"></i> Declined';
  button.disabled = true;

  // Disable the accept button
  const acceptButton = jobItem.querySelector(".btn-accept");
  acceptButton.disabled = true;

  // Send denial to server
  fetch(`${ROOT}/public/booking/reject`, {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify({
      bookingID: bookingId,
    }),
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.status !== "success") {
        alert("Failed to decline booking");
        // Revert UI changes if failed
        button.innerHTML = '<i class="bx bx-x"></i> Decline';
        button.disabled = false;
        acceptButton.disabled = false;
        jobItem.style.backgroundColor = "";
      } else {
        setTimeout(() => {
          jobItem.style.opacity = "0.6";
        }, 1000);
      }
    })
    .catch((error) => {
      console.error("Error:", error);
      alert("Failed to decline booking");
      // Revert UI changes if failed
      button.innerHTML = '<i class="bx bx-x"></i> Decline';
      button.disabled = false;
      acceptButton.disabled = false;
      jobItem.style.backgroundColor = "";
    });
}

// Filter bookings based on tab selection
function filterBookings(filter) {
  const bookingsList = document.querySelector(".bookings-list");
  const tabButtons = document.querySelectorAll(".tab-btn");

  // Update active tab
  tabButtons.forEach((btn) => {
    btn.classList.remove("active");
    if (btn.textContent.toLowerCase().includes(filter)) {
      btn.classList.add("active");
    }
  });

  // Show relevant bookings
  bookingsList.className = "bookings-list";
  if (filter === "completed") {
    bookingsList.classList.add("show-completed");
  } else if (filter === "cancelled") {
    bookingsList.classList.add("show-cancelled");
  }
}

// View job details
function viewJobDetails(jobItem, jobId) {
  if (!window.latestRequests) {
    console.log("No requests data yet, polling now...");
    pollForNewRequests(); // Trigger immediate poll
    setTimeout(() => {
      viewJobDetails(jobItem, jobId); // Retry after polling
    }, 1000);
    return;
  }
  // Find the job in our polling data
  console.log("found requests from window");
  console.log(window.latestRequests);
  const requests = window.latestRequests || [];
  const matchingRequest = requests.find((req) => req.key == jobId);

  if (matchingRequest) {
    console.log("Match found in local data:", matchingRequest);
    const bookingData = matchingRequest.value.booking;
    const bookingDetails = matchingRequest.value.details;

    const detailsMap = bookingDetails.reduce((acc, detail) => {
      acc[detail.detailType] = detail.detailValue;
      return acc;
    }, {});

    // Clear previous content
    const detailsGrid = document.querySelector(".job-details-grid");
    detailsGrid.innerHTML = "";

    // Add common fields - these are guaranteed to exist
    const date = new Date(bookingData.bookingDate);

    // Create and add standard fields
    // Updated standard fields section
    const standardFields = {
      Customer: detailsMap.customer_name,
      "Service Type": bookingData.serviceType,
      Date: date.toLocaleDateString(),
      "Time Slot": bookingData.startTime,
      Location: bookingData.location,
    };

    const priceFields = [
      {
        label: "Base Price",
        value: `Rs. ${Number(detailsMap.base_price || 0).toFixed(2)}`,
      },
      {
        label: "Add-on Price",
        value: `Rs. ${Number(detailsMap.addon_price || 0).toFixed(2)}`,
      },
      {
        label: "Total Price",
        value: `Rs. ${Number(bookingData.totalCost || 0).toFixed(2)}`,
        isTotal: true,
      },
    ];

    // Add standard fields
    for (const [label, value] of Object.entries(standardFields)) {
      if (value) {
        addDetailItem(detailsGrid, label, value);
      }
    }

    // Define keys to exclude
    const excludedKeys = [
      "bookingID",
      "customer_name",
      "contact_phone",
      "contact_email",
      "serviceType",
      "bookingDate",
      "startTime",
      "endTime",
      "location",
      "status",
      "workerID",
      "customerID",
      "createdAt",
    ];

    // Add dynamic fields from bookingDetails
    bookingDetails.forEach((detail) => {
      const key = detail.detailType;
      const value = detail.detailValue;

      if (!excludedKeys.includes(key) && value && value !== "") {
        const label = formatLabel(key);

        // Handle JSON-stringified arrays (like ["dishwashing","desserts"])
        const parsedValue = tryParseJson(value) || value;

        addDetailItem(detailsGrid, label, formatValue(parsedValue));
      }
    });

    function tryParseJson(value) {
      try {
        return JSON.parse(value);
      } catch {
        return null;
      }
    }
    const priceContainer = document.createElement("div");
    priceContainer.className = "price-section";

    priceFields.forEach((field) => {
      const item = document.createElement("div");
      item.className = `detail-item${field.isTotal ? " total-price" : ""}`;

      item.innerHTML = `
    <span class="detail-label">${field.label}:</span>
    <span class="detail-value">${field.value}</span>
  `;

      priceContainer.appendChild(item);
    });

    detailsGrid.appendChild(priceContainer);

    // Make the total price item special (for styling)
    const totalPriceItem = detailsGrid.querySelector(
      '[data-label="Total Price"]',
    );
    if (totalPriceItem) {
      totalPriceItem.classList.add("total");
    }

    // Store reference to the job item
    const modal = document.getElementById("jobDetailsModal");
    modal.jobItem = jobItem;
    modal.jobId = jobId;

    // Show modal
    modal.style.display = "block";
  } else {
    console.error("No match found in local data for jobId:", jobId);
  }
}

// Helper function to format the label from camelCase or snake_case
function formatLabel(key) {
  // Convert camelCase to spaces
  const spacedKey = key.replace(/([A-Z])/g, " $1");
  // Convert snake_case or kebab-case to spaces
  const formattedKey = spacedKey.replace(/[_-]/g, " ");
  // Capitalize first letter of each word
  return formattedKey
    .split(" ")
    .map((word) => word.charAt(0).toUpperCase() + word.slice(1))
    .join(" ");
}

// Helper function to format the value appropriately
function formatValue(value) {
  if (typeof value === "number") return value;
  if (!isNaN(value)) return Number(value); // Handle numeric strings
  if (Array.isArray(value)) {
    return value
      .map((item) =>
        typeof item === "string"
          ? item.charAt(0).toUpperCase() + item.slice(1)
          : item,
      )
      .join(", ");
  } else if (typeof value === "boolean") {
    return value ? "Yes" : "No";
  } else if (typeof value === "string") {
    return value.charAt(0).toUpperCase() + value.slice(1);
  }
  return value;
}

// Helper function to add a detail item to the grid
function addDetailItem(container, label, value) {
  const detailItem = document.createElement("div");
  detailItem.className = "detail-item";
  detailItem.setAttribute("data-label", label);

  const labelSpan = document.createElement("span");
  labelSpan.className = "detail-label";
  labelSpan.textContent = label + ":";

  const valueSpan = document.createElement("span");
  valueSpan.className = "detail-value";
  valueSpan.textContent = value;

  detailItem.appendChild(labelSpan);
  detailItem.appendChild(valueSpan);
  container.appendChild(detailItem);
}

// Close modal
function closeModal() {
  document.getElementById("jobDetailsModal").style.display = "none";
}

// Initialize modal functionality when DOM loads
document.addEventListener("DOMContentLoaded", function () {
  const modal = document.getElementById("jobDetailsModal");
  const closeBtn = document.querySelector(".close-modal");

  // Close modal when X is clicked
  closeBtn.addEventListener("click", closeModal);

  // Close modal when clicking outside content
  window.addEventListener("click", function (event) {
    if (event.target === modal) {
      closeModal();
    }
  });

  // Handle accept button in modal
  document
    .getElementById("modalAcceptBtn")
    .addEventListener("click", function () {
      const jobId = modal.jobId;
      const jobItem = modal.jobItem;
      const acceptButton = jobItem.querySelector(".btn-accept");
      acceptJob(acceptButton, jobId);
      closeModal();
    });

  // Handle deny button in modal
  document
    .getElementById("modalDenyBtn")
    .addEventListener("click", function () {
      const jobId = modal.jobId;
      const jobItem = modal.jobItem;
      const denyButton = jobItem.querySelector(".btn-deny");
      denyJob(denyButton, jobId);
      closeModal();
    });
});

// Functionality needed for viewing booking details
function viewBookingDetails(bookingItem, bookingId) {
  // Poll for new data if needed
  if (!window.latestBookings) {
    // Create a global array to store booking data if it doesn't exist
    window.latestBookings = [];

    // Fetch booking data
    fetch(`${ROOT}/public/dashboard/getAllBookings`, {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({}),
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.status === "success" && data.data.length > 0) {
          window.latestBookings = data.data;
          viewBookingDetails(bookingItem, bookingId); // Retry after data is loaded
        } else {
          console.error("No bookings data available");
          alert("Failed to load booking details");
        }
      })
      .catch((error) => {
        console.error("Error:", error);
        alert("Failed to load booking details");
      });
    return;
  }

  // Find the booking in our data
  const bookings = window.latestBookings || [];
  const matchingBooking = bookings.find(
    (booking) =>
      booking.key == bookingId ||
      (booking.value &&
        booking.value.booking &&
        booking.value.booking.bookingID == bookingId),
  );

  if (matchingBooking) {
    console.log("Match found in local data:", matchingBooking);
    const bookingData = matchingBooking.value.booking;
    const bookingDetails = matchingBooking.value.details;

    // Create a details map for easier access
    const detailsMap = bookingDetails.reduce((acc, detail) => {
      acc[detail.detailType] = detail.detailValue;
      return acc;
    }, {});

    // Clear previous content
    const detailsGrid = document.querySelector(".job-details-grid");
    detailsGrid.innerHTML = "";

    // Parse date
    const date = new Date(bookingData.bookingDate);

    // Standard fields
    const standardFields = {
      Customer: detailsMap.customer_name,
      Contact: detailsMap.contact_phone,
      Email: detailsMap.contact_email,
      "Service Type": bookingData.serviceType,
      Date: date.toLocaleDateString(),
      "Time Slot": bookingData.startTime,
      Location: bookingData.location,
    };

    // Add price fields
    const priceFields = [
      {
        label: "Base Price",
        value: `Rs. ${Number(detailsMap.base_price || 0).toFixed(2)}`,
      },
      {
        label: "Add-on Price",
        value: `Rs. ${Number(detailsMap.addon_price || 0).toFixed(2)}`,
      },
      {
        label: "Total Price",
        value: `Rs. ${Number(bookingData.totalCost || 0).toFixed(2)}`,
        isTotal: true,
      },
    ];

    // Add standard fields to grid
    for (const [label, value] of Object.entries(standardFields)) {
      if (value) {
        addDetailItem(detailsGrid, label, value);
      }
    }

    // Add special requests if any
    if (detailsMap.special_requests) {
      addDetailItem(
        detailsGrid,
        "Special Requests",
        detailsMap.special_requests,
      );
    }

    // Excluded keys (already handled or system fields)
    const excludedKeys = [
      "bookingID",
      "customer_name",
      "contact_phone",
      "contact_email",
      "serviceType",
      "bookingDate",
      "startTime",
      "endTime",
      "location",
      "status",
      "workerID",
      "customerID",
      "createdAt",
      "base_price",
      "addon_price",
      "special_requests",
    ];

    // Add any remaining fields from bookingDetails
    bookingDetails.forEach((detail) => {
      const key = detail.detailType;
      const value = detail.detailValue;

      if (!excludedKeys.includes(key) && value && value !== "") {
        const label = formatLabel(key);

        // Parse JSON arrays if needed
        const parsedValue = tryParseJson(value) || value;

        addDetailItem(detailsGrid, label, formatValue(parsedValue));
      }
    });

    // Add price section
    const priceContainer = document.createElement("div");
    priceContainer.className = "price-section";

    priceFields.forEach((field) => {
      const item = document.createElement("div");
      item.className = `detail-item${field.isTotal ? " total" : ""}`;

      item.innerHTML = `
                <span class="detail-label">${field.label}:</span>
                <span class="detail-value">${field.value}</span>
            `;

      priceContainer.appendChild(item);
    });

    detailsGrid.appendChild(priceContainer);

    // Reference the modal and set properties
    const modal = document.getElementById("jobDetailsModal");

    // Update the modal title
    const modalTitle = modal.querySelector("h3");
    modalTitle.textContent = "Booking Details";

    // Hide or modify action buttons for bookings
    const modalActions = modal.querySelector(".modal-actions");

    // For upcoming bookings, show "Mark as Complete" button
    if (bookingItem.classList.contains("upcoming")) {
      modalActions.innerHTML = `
                <button class="btn-complete" onclick="completeBookingFromModal(${bookingId})">
                    <i class='bx bx-check-circle'></i> Mark as Complete
                </button>
            `;
    } else {
      // For completed or cancelled bookings, hide action buttons
      modalActions.style.display = "none";
    }

    // Show the modal
    modal.style.display = "block";
  } else {
    console.error("No match found for bookingId:", bookingId);
    console.log("bookings dump ", bookings);
    alert("Failed to load booking details");
  }
}

// Function to complete a booking from the modal
function completeBookingFromModal(bookingId) {
  currentBookingIdForVerification = bookingId;
  closeModal(); // Close the details modal
  document.getElementById("verificationModal").style.display = "block"; // Open verification modal
}

// Helper function to try parsing JSON
function tryParseJson(value) {
  try {
    return JSON.parse(value);
  } catch {
    return null;
  }
}

// Modify the DOMContentLoaded event listener to initialize modal
document.addEventListener("DOMContentLoaded", function () {
  const modal = document.getElementById("jobDetailsModal");
  const closeBtn = document.querySelector(".close-modal");

  // Close modal when X is clicked
  closeBtn.addEventListener("click", closeModal);

  // Close modal when clicking outside content
  window.addEventListener("click", function (event) {
    if (event.target === modal) {
      closeModal();
    }
  });

  // Modify the event listeners for modal action buttons
  // These will be set dynamically when opening the modal now

  // Add function to fetch all bookings at page load
  fetchAllBookings();

  // Poll for new requests
  setInterval(pollForNewRequests, 20000);
});

// Function to fetch all bookings
function fetchAllBookings() {
  fetch(`${ROOT}/public/dashboard/getLatestBookings`, {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify({}),
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.status === "success" && data.data.length > 0) {
        window.latestBookings = data.data;
        console.log("Saved all bookings to the window", data);
      }
    })
    .catch((error) => {
      console.error("Error fetching bookings:", error);
    });
}

// Functionality to handle booking completion
let currentBookingIdForVerification = null;

function completeBooking(button, bookingId) {
  // Store the booking ID for verification
  currentBookingIdForVerification = bookingId;

  // Show the verification modal
  document.getElementById("verificationModal").style.display = "block";
}

function closeVerificationModal() {
  document.getElementById("verificationModal").style.display = "none";
  currentBookingIdForVerification = null;
  document.getElementById("verificationError").style.display = "none";
}

function verifyCompletion() {
  const verificationCode = document
    .getElementById("verificationCode")
    .value.trim();
  const errorElement = document.getElementById("verificationError");

  if (!verificationCode) {
    errorElement.textContent = "Please enter the verification code";
    errorElement.style.display = "block";
    return;
  }

  // Find the booking item in the DOM
  const bookingItem = document.querySelector(
    `.booking-item[data-booking-id="${currentBookingIdForVerification}"]`,
  );

  if (bookingItem) {
    // Change status to completed
    bookingItem.classList.remove("upcoming");
    bookingItem.classList.add("completed");

    // Remove action buttons and add status indicator
    const actionsDiv = bookingItem.querySelector(".booking-actions");
    actionsDiv.innerHTML = `
            <div class="booking-status completed">
                <i class='bx bx-check-circle'></i> Completed
            </div>
        `;

    // Send completion to server with verification code
    fetch(`${ROOT}/public/booking/completeBooking`, {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({
        bookingID: currentBookingIdForVerification,
        verificationCode: verificationCode,
      }),
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.status === "success") {
          closeVerificationModal();
        } else {
          // Revert UI changes if failed
          bookingItem.classList.remove("completed");
          bookingItem.classList.add("upcoming");
          actionsDiv.innerHTML = `
                    <button class="btn-complete" onclick="completeBooking(this, ${currentBookingIdForVerification})">
                        <i class='bx bx-check-circle'></i> Complete
                    </button>
                `;

          errorElement.textContent =
            data.message ||
            "Verification failed. Please check the code and try again.";
          errorElement.style.display = "block";
        }
      })
      .catch((error) => {
        console.error("Error:", error);
        // Revert UI changes if failed
        bookingItem.classList.remove("completed");
        bookingItem.classList.add("upcoming");
        actionsDiv.innerHTML = `
                <button class="btn-complete" onclick="completeBooking(this, ${currentBookingIdForVerification})">
                    <i class='bx bx-check-circle'></i> Complete
                </button>
            `;

        errorElement.textContent = "An error occurred. Please try again.";
        errorElement.style.display = "block";
      });
  }
}

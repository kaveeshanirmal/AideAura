// Toggle availability status
function toggleAvailability() {
  const button = document.getElementById("availability");
  const isAvailable = button.classList.contains("available");

  fetch("<?= ROOT ?>/dashboard/availability", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify({
      availability: !isAvailable,
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
  const cityInput = document.getElementById("city");
  if (cityInput.value) {
    cityInput.value = cityInput.value.trim();
    cityInput.value =
      cityInput.value.charAt(0).toUpperCase() + cityInput.value.slice(1);
  }
}

// Submit location
function submitLocation() {
  const district = document.getElementById("district").value;
  const city = document.getElementById("city").value;

  if (!city) {
    alert("Please enter a city");
    return;
  }

  formatCity();

  fetch("<?= ROOT ?>/dashboard/updateLocation", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify({
      district: district,
      city: city,
    }),
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.status === "success") {
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
  fetch("<?= ROOT ?>/bookings/accept", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify({
      bookingId: bookingId,
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
  fetch("<?= ROOT ?>/bookings/deny", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify({
      bookingId: bookingId,
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

// Complete booking
function completeBooking(button, bookingId) {
  const bookingItem = button.closest(".booking-item");
  const bookingTitle = bookingItem.querySelector(".booking-title").textContent;

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

  // Send completion to server
  fetch("<?= ROOT ?>/bookings/complete", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify({
      bookingId: bookingId,
    }),
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.status !== "success") {
        alert("Failed to mark booking as completed");
        // Revert UI changes if failed
        bookingItem.classList.remove("completed");
        bookingItem.classList.add("upcoming");
        actionsDiv.innerHTML = `
                <button class="btn-complete" onclick="completeBooking(this, ${bookingId})">
                    <i class='bx bx-check-circle'></i> Complete
                </button>
                <button class="btn-cancel" onclick="cancelBooking(this, ${bookingId})">
                    <i class='bx bx-x-circle'></i> Cancel
                </button>
            `;
      }
    })
    .catch((error) => {
      console.error("Error:", error);
      alert("Failed to mark booking as completed");
      // Revert UI changes if failed
      bookingItem.classList.remove("completed");
      bookingItem.classList.add("upcoming");
      actionsDiv.innerHTML = `
            <button class="btn-complete" onclick="completeBooking(this, ${bookingId})">
                <i class='bx bx-check-circle'></i> Complete
            </button>
            <button class="btn-cancel" onclick="cancelBooking(this, ${bookingId})">
                <i class='bx bx-x-circle'></i> Cancel
            </button>
        `;
    });
}

// Cancel booking
function cancelBooking(button, bookingId) {
  const bookingItem = button.closest(".booking-item");
  const bookingTitle = bookingItem.querySelector(".booking-title").textContent;

  if (!confirm(`Are you sure you want to cancel "${bookingTitle}"?`)) {
    return;
  }

  // Change status to cancelled
  bookingItem.classList.remove("upcoming");
  bookingItem.classList.add("cancelled");

  // Remove action buttons and add status indicator
  const actionsDiv = bookingItem.querySelector(".booking-actions");
  actionsDiv.innerHTML = `
        <div class="booking-status cancelled">
            <i class='bx bx-x-circle'></i> Cancelled
        </div>
    `;

  // Send cancellation to server
  fetch("<?= ROOT ?>/bookings/cancel", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify({
      bookingId: bookingId,
    }),
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.status !== "success") {
        alert("Failed to cancel booking");
        // Revert UI changes if failed
        bookingItem.classList.remove("cancelled");
        bookingItem.classList.add("upcoming");
        actionsDiv.innerHTML = `
                <button class="btn-complete" onclick="completeBooking(this, ${bookingId})">
                    <i class='bx bx-check-circle'></i> Complete
                </button>
                <button class="btn-cancel" onclick="cancelBooking(this, ${bookingId})">
                    <i class='bx bx-x-circle'></i> Cancel
                </button>
            `;
      }
    })
    .catch((error) => {
      console.error("Error:", error);
      alert("Failed to cancel booking");
      // Revert UI changes if failed
      bookingItem.classList.remove("cancelled");
      bookingItem.classList.add("upcoming");
      actionsDiv.innerHTML = `
            <button class="btn-complete" onclick="completeBooking(this, ${bookingId})">
                <i class='bx bx-check-circle'></i> Complete
            </button>
            <button class="btn-cancel" onclick="cancelBooking(this, ${bookingId})">
                <i class='bx bx-x-circle'></i> Cancel
            </button>
        `;
    });
}

// View job details
function viewJobDetails(jobItem, jobId) {
  // Fetch job details from server
  fetch(`${ROOT}/bookings/details/${jobId}`)
    .then((response) => response.json())
    .then((data) => {
      if (data.status === "success") {
        const booking = data.booking;
        const customer = data.customer;
        const date = new Date(booking.date);

        // Populate modal with data
        document.getElementById("detail-customer").textContent =
          customer.full_name;
        document.getElementById("detail-contact").textContent =
          customer.contact_no;
        document.getElementById("detail-email").textContent = customer.email;
        document.getElementById("detail-service").textContent =
          booking.service_type;
        document.getElementById("detail-date").textContent =
          date.toLocaleDateString();
        document.getElementById("detail-time").textContent = booking.time_slot;
        document.getElementById("detail-location").textContent =
          `${customer.city}, ${customer.district}`;
        document.getElementById("detail-requests").textContent =
          booking.special_requests || "None";
        document.getElementById("detail-total-price").textContent =
          `Rs. ${booking.price.toFixed(2)}`;

        // Store reference to the job item
        const modal = document.getElementById("jobDetailsModal");
        modal.jobItem = jobItem;
        modal.jobId = jobId;

        // Show modal
        modal.style.display = "block";
      } else {
        alert("Failed to load job details");
      }
    })
    .catch((error) => {
      console.error("Error:", error);
      alert("Failed to load job details");
    });
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

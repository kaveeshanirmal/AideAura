// Toggle availability status
function toggleAvailability() {
  const button = document.getElementById("availability");
  const icon = button.querySelector("i");

  if (button.classList.contains("available")) {
    // Switch to Not Available state
    button.classList.remove("available");
    button.classList.add("not-available");
    icon.classList.remove("bx-check-circle");
    icon.classList.add("bx-x-circle");
    button.innerHTML = '<i class="bx bx-x-circle"></i> Not Available';

    // Here you would typically send the status to the server
    // Example: updateAvailabilityStatus(false);
  } else {
    // Switch to Available state
    button.classList.remove("not-available");
    button.classList.add("available");
    icon.classList.remove("bx-x-circle");
    icon.classList.add("bx-check-circle");
    button.innerHTML = '<i class="bx bx-check-circle"></i> Available';

    // Here you would typically send the status to the server
    // Example: updateAvailabilityStatus(true);
  }
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

  // Here you would typically send the data to the server
  alert(`Location updated to ${city}, ${district}`);
}

// Accept job request
function acceptJob(button) {
  const jobItem = button.closest(".job-request-item");
  const jobTitle = jobItem.querySelector(".job-title").textContent;

  // Add visual feedback
  jobItem.style.backgroundColor = "rgba(76, 175, 80, 0.1)";
  button.innerHTML = '<i class="bx bx-check-double"></i> Accepted';
  button.disabled = true;

  // Disable the deny button
  const denyButton = jobItem.querySelector(".btn-deny");
  denyButton.disabled = true;

  // Here you would typically send the acceptance to the server
  setTimeout(() => {
    jobItem.style.opacity = "0.6";
  }, 1000);

  alert(`You have accepted the ${jobTitle} job request`);
}

// Deny job request
function denyJob(button) {
  const jobItem = button.closest(".job-request-item");
  const jobTitle = jobItem.querySelector(".job-title").textContent;

  // Add visual feedback
  jobItem.style.backgroundColor = "rgba(244, 67, 54, 0.1)";
  button.innerHTML = '<i class="bx bx-x-circle"></i> Declined';
  button.disabled = true;

  // Disable the accept button
  const acceptButton = jobItem.querySelector(".btn-accept");
  acceptButton.disabled = true;

  // Here you would typically send the denial to the server
  setTimeout(() => {
    jobItem.style.opacity = "0.6";
  }, 1000);

  alert(`You have declined the ${jobTitle} job request`);
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
function completeBooking(button) {
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

  // Here you would typically send the completion to the server
  alert(`You have marked "${bookingTitle}" as completed`);
}

// Cancel booking
function cancelBooking(button) {
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

  // Here you would typically send the cancellation to the server
  alert(`You have cancelled "${bookingTitle}"`);
}

// View job details
function viewJobDetails(jobItem, jobId) {
  // Get job data from the clicked item (in a real app, you would fetch from server)
  const jobData = {
    customer: jobItem.querySelector(".job-title").textContent,
    contact: "Loading...", // These would come from your AJAX call
    email: "Loading...",
    gender: "Loading...",
    people: "Loading...",
    meals: "Loading...",
    diet: "Loading...",
    addons: "Loading...",
    basePrice: "Loading...",
    addonPrice: "Loading...",
    totalPrice: "Loading...",
  };

  // Populate modal with initial data
  document.getElementById("detail-customer").textContent = jobData.customer;
  document.getElementById("detail-contact").textContent = jobData.contact;
  document.getElementById("detail-email").textContent = jobData.email;
  document.getElementById("detail-gender").textContent = jobData.gender;
  document.getElementById("detail-people").textContent = jobData.people;
  document.getElementById("detail-meals").textContent = jobData.meals;
  document.getElementById("detail-diet").textContent = jobData.diet;
  document.getElementById("detail-addons").textContent = jobData.addons;
  document.getElementById("detail-base-price").textContent = jobData.basePrice;
  document.getElementById("detail-addon-price").textContent =
    jobData.addonPrice;
  document.getElementById("detail-total-price").textContent =
    jobData.totalPrice;

  // Store reference to the job item
  const modal = document.getElementById("jobDetailsModal");
  modal.jobItem = jobItem;
  modal.jobId = jobId;

  // Show modal
  modal.style.display = "block";

  // In a real implementation, you would now fetch the details via AJAX:
  /*
    fetch(`get_job_details.php?id=${jobId}`)
        .then(response => response.json())
        .then(data => {
            // Update modal with real data
            document.getElementById('detail-customer').textContent = data.customer_name;
            document.getElementById('detail-contact').textContent = data.contact_phone;
            // ... and so on for all fields
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Failed to load job details');
        });
    */
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
      acceptJob(modal.jobItem);
      closeModal();
    });

  // Handle deny button in modal
  document
    .getElementById("modalDenyBtn")
    .addEventListener("click", function () {
      denyJob(modal.jobItem);
      closeModal();
    });
});

// Update your existing job request items to include view details button
// Modify your job request items HTML to include:
/*
<div class="job-actions">
    <button class="btn-view" onclick="viewJobDetails(this.closest('.job-request-item'))">
        <i class='bx bx-show'></i> View
    </button>
    <button class="btn-accept" onclick="viewJobDetails(this.closest('.job-request-item'))">
        <i class='bx bx-check'></i> Accept
    </button>
    <button class="btn-deny" onclick="denyJob(this.closest('.job-request-item'))">
        <i class='bx bx-x'></i> Decline
    </button>
</div>
*/

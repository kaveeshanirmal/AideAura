
function toggleAvailability() {
    const button = document.getElementById("availability");
  
    if (button.classList.contains("available")) {
      button.classList.remove("available");
      button.classList.add("not-available");
      button.textContent = "Unavailable"; // Update the text
    } else {
      button.classList.remove("not-available");
      button.classList.add("available");
      button.textContent = "Available"; // Update the text
    }
};

// Initialize state based on the default class
window.onload = () => {
    const button = document.getElementById("availability");
    if (button.classList.contains("not-available")) {
      button.textContent = "Unavailable";
    } else {
      button.textContent = "Available";
    }
};

function formatCity() {
    let cityInput = document.getElementById("city");
    let cityValue = cityInput.value.trim();

    if (cityValue.length > 0) {
      cityInput.value = cityValue.charAt(0).toUpperCase() + cityValue.slice(1).toLowerCase();
    }
};

function submitLocation() {
    let district = document.getElementById("district").value;
    let city = document.getElementById("city").value.trim();

    if (city === "") {
      alert("Please enter a city name before submitting.");
      return;
    }

    console.log("Submitted Location:", district, city);
    alert(`Location Submitted!\nDistrict: ${district}\nCity: ${city}`);
};

function acceptJob(button) {
    let jobItem = button.closest(".event-item");
    jobItem.style.backgroundColor = "#d4edda"; // Light green
    jobItem.style.border = "1px solid #28a745"; // Green border
};

function denyJob(button) {
    let jobItem = button.closest(".event-item");
    jobItem.remove();
};




// Function to open the modal
function openModal(button) {
    const modalOverlay = document.getElementById("modal-overlay");
    const modalBody = document.getElementById("modal-body");

    // Check if modal elements exist
    if (!modalOverlay || !modalBody) {
        console.error("Modal elements not found");
        return;
    }

    const serviceType = button.getAttribute("data-service");

    // Map service types to their respective PHP files
    const serviceForms = {
        "home-style-food": `${ROOT}/public/serviceForms/getForm/home-style-food`, // Use backticks for template literals
        "dishwashing": `${ROOT}/public/serviceForms/getForm/dishwashing`,
        // Add other service URLs here
    };

    const formUrl = serviceForms[serviceType];

    if (formUrl) {
        fetch(formUrl)
            .then((response) => {
                if (!response.ok)
                    throw new Error(`HTTP error! status: ${response.status}`);
                return response.text();
            })
            .then((html) => {
                modalBody.innerHTML = html; // Load the form into the modal
                modalOverlay.classList.add("show"); // Show the modal overlay
            })
            .catch((error) => {
                console.error("Error loading form:", error);
                modalBody.innerHTML =
                    "<p>Unable to load form. Please try again later.</p>";
                modalOverlay.classList.add("show"); // Show modal with error message
            });
    } else {
        console.error("Invalid service type:", serviceType);
    }
}

// Function to close the modal
function closeModal() {
    const modalOverlay = document.getElementById("modal-overlay");

    // Check if modal elements exist
    if (!modalOverlay) {
        console.error("Modal overlay not found");
        return;
    }

    // Fade out modal and overlay
    modalOverlay.classList.remove("show"); // Hide modal overlay
}

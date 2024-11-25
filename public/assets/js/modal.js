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
        "home-style-food": `${ROOT}/public/serviceForms/getForm/home-style-food`,
        "dishwashing": `${ROOT}/public/serviceForms/getForm/dishwashing`,
        // Add other service URLs here
    };

    const formUrl = serviceForms[serviceType];

    if (formUrl) {
        fetch(formUrl)
            .then((response) => {
                if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
                return response.text();
            })
            .then((html) => {
                modalBody.innerHTML = html; // Load the form into the modal
                modalOverlay.classList.add("show"); // Show the modal overlay
            })
            .catch((error) => {
                console.error("Error loading form:", error);
                modalBody.innerHTML = "<p>Unable to load form. Please try again later.</p>";
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
    
    // Call a function to update the service card after closing the modal
    setTimeout(() => {
        updateServiceCard(); // This ensures the update happens after a small delay
    }, 100); // Small delay to ensure modal is closed first
}

// Function to update the service card
function updateServiceCard() {
    // Assuming service cards are rendered in the same page
    const serviceCard = document.querySelector('.service-card[data-service="home-style-food"]');
    if (serviceCard) {
        // Apply the 'selected' class to the service card for the blue shadow
        serviceCard.classList.add('selected');
        
        // Find the add button inside the service card
        const addButton = serviceCard.querySelector('.add-button');
        if (addButton) {
            // Add selected class to the button
            addButton.classList.add('selected');
            
            // Update button styles
            addButton.style.backgroundColor = 'white';
            addButton.style.border = '2px solid #812019';
            
            // Change button content to a red-colored minus icon
            addButton.innerHTML = '<i class="fas fa-minus" style="color: #812019;"></i>';
        }
    }
}

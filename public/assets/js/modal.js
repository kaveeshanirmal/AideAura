function openModal(button) {
    console.log('1. Opening modal...');
    
    const modalOverlay = document.getElementById("modal-overlay");
    const modalBody = document.getElementById("modal-body");
    const modalContent = document.querySelector('.modal-content');
    const serviceFormModal = document.querySelector('.service-form-modal');

    if (!modalOverlay || !modalBody) {
        console.error("Modal elements not found");
        return;
    }

    // Reset all modal-related elements to be visible
    modalOverlay.style.display = 'flex';  // Changed from 'block' to 'flex'
    if (modalContent) modalContent.style.display = 'block';
    if (serviceFormModal) serviceFormModal.style.display = 'block';

    const serviceType = button.getAttribute("data-service");
    console.log('2. Service Type:', serviceType);

    const serviceForms = {
        "home-style-food": `${ROOT}/public/serviceForms/getForm/home-style-food`,
        dishwashing: `${ROOT}/public/serviceForms/getForm/dishwashing`,
    };

    const formUrl = serviceForms[serviceType];
    console.log('3. Form URL:', formUrl);

    if (formUrl) {
        console.log('4. Starting fetch...');
        
        fetch(formUrl)
            .then(response => response.text())
            .then(html => {
                modalBody.innerHTML = html;
                modalOverlay.classList.add('show');
            })
            .catch(error => {
                console.error('Error loading form:', error);
            });
    }
}

function closeModal() {
    console.log('Closing modal...');
    
    // Close modal using multiple approaches (same as our working Done button)
    const modalOverlay = document.getElementById('modal-overlay');
    const modalContent = document.querySelector('.modal-content');
    const serviceFormModal = document.querySelector('.service-form-modal');
    
    // Hide modal overlay
    if (modalOverlay) {
        modalOverlay.style.display = 'none';
        modalOverlay.classList.remove('show');
    }

    // Hide modal content
    if (modalContent) {
        modalContent.style.display = 'none';
    }

    // Hide service form modal
    if (serviceFormModal) {
        serviceFormModal.style.display = 'none';
    }
}

// Make closeModal available globally
window.closeModal = closeModal;

// Event delegation for add buttons
document.addEventListener('click', function(event) {
    const addButton = event.target.closest('.add-button');
    if (addButton) {
        console.log('Add button clicked:', addButton);
        console.log('Button selected state:', addButton.classList.contains('selected'));
    }
});
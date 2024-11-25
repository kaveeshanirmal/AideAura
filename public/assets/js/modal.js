function openModal(button) {
    console.log('1. Opening modal...');
    
    const modalOverlay = document.getElementById("modal-overlay");
    const modalBody = document.getElementById("modal-body");

    if (!modalOverlay || !modalBody) {
        console.error("Modal elements not found");
        return;
    }

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
        
        // First reset the modal completely
        modalOverlay.style.display = 'none';
        modalBody.innerHTML = '';
        modalOverlay.classList.remove('show');
        
        // Important: Don't replace modalBody, just clear it
        fetch(formUrl)
            .then((response) => {
                console.log('5. Fetch response received:', response.status);
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.text();
            })
            .then((html) => {
                console.log('6. HTML content received');
                modalBody.innerHTML = html;
                
                console.log('7. Showing modal');
                // Use setTimeout to ensure DOM is updated
                setTimeout(() => {
                    modalOverlay.style.display = 'flex';
                    modalOverlay.classList.add('show');
                    
                    console.log('8. Initializing form calculations');
                    const formId = serviceType === 'home-style-food' ? 'homeStyleForm' : 'dishwashingForm';
                    initializeFormCalculations(formId);
                }, 0);
            })
            .catch((error) => {
                console.error('Error in fetch:', error);
                modalBody.innerHTML = "<p>Unable to load form. Please try again later.</p>";
                modalOverlay.classList.add("show");
            });
    }
}

function validateFormSelections() {
    const form = document.querySelector('form');
    if (!form) return false;

    // Get all required radio button groups
    const requiredGroups = ['people', 'dogs'];
    
    // Add meal-specific validations
    if (form.id === 'homeStyleForm') {
        requiredGroups.push('meals', 'preference');
    }

    // Check each required group
    for (const groupName of requiredGroups) {
        const selectedOption = form.querySelector(`input[name="${groupName}"]:checked`);
        if (!selectedOption) {
            alert(`Please select an option for ${groupName.replace('-', ' ')}`);
            return false;
        }
    }

    // Check agreement checkbox if it exists
    const agreementCheckbox = form.querySelector('input[type="checkbox"]#agreement');
    if (agreementCheckbox && !agreementCheckbox.checked) {
        alert('Please accept the agreement to continue');
        return false;
    }

    return true;
}

function closeModal() {
    const modalOverlay = document.getElementById("modal-overlay");
    
    if (!modalOverlay) {
        console.error("Modal overlay not found");
        return;
    }

    modalOverlay.classList.remove("show");
    
    // Use setTimeout to match CSS transition
    setTimeout(() => {
        modalOverlay.style.display = 'none';
        const modalBody = document.getElementById("modal-body");
        if (modalBody) {
            modalBody.innerHTML = '';
        }
    }, 300);
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
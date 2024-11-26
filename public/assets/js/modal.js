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
        dishwashing: `${ROOT}/public/serviceForms/getForm/dishwashing`,
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



// Function to submit service form data
async function submitServiceForm(serviceType, formData) {
    const loadingText = document.getElementById('modal-salary');
    const originalText = loadingText.textContent;
    loadingText.textContent = 'Calculating...';

    try {
        // Convert service type to API endpoint format
        const endpoint = serviceType === 'home-style-food' ? 'homeStyleFood' : 'dishwashing';
        
        const response = await fetch(`${ROOT}/services/process${endpoint}`, {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        if (result.success) {
            // Update the main page totals
            window.updateServiceTotals(serviceType, result.data);
            
            // Update the service card
            updateServiceCard(serviceType, true);
            
            // Close the modal
            closeModal();
        } else {
            alert(result.error || 'An error occurred while processing your request');
        }
    } catch (error) {
        console.error('Submission error:', error);
        alert('Error submitting form. Please try again.');
    } finally {
        loadingText.textContent = originalText;
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

// Function to update the service card
function updateServiceCard(serviceType, isSelected = true) {
    const serviceCard = document.querySelector(`.service-card[data-service="${serviceType}"]`);
    if (serviceCard) {
        if (isSelected) {
            serviceCard.classList.add('selected');
            
            const addButton = serviceCard.querySelector('.add-button');
            if (addButton) {
                addButton.classList.add('selected');
                addButton.style.backgroundColor = 'white';
                addButton.style.border = '2px solid #812019';
                addButton.innerHTML = '<i class="fas fa-minus" style="color: #812019;"></i>';
                
                // Update button click handler
                addButton.onclick = () => removeService(serviceType);
            }
        } else {
            serviceCard.classList.remove('selected');
            
            const addButton = serviceCard.querySelector('.add-button');
            if (addButton) {
                addButton.classList.remove('selected');
                addButton.style.backgroundColor = '';
                addButton.style.border = '';
                addButton.innerHTML = '+';
                
                // Restore original click handler
                addButton.onclick = () => openModal(addButton);
            }
        }
    }
}

// Function to remove a service
function removeService(serviceType) {
    // Reset the service totals
    window.updateServiceTotals(serviceType, { monthly_price: 0, working_hours: 0 });
    
    // Update the service card to unselected state
    updateServiceCard(serviceType, false);
}

// Function to initialize real-time calculations
function initializeFormCalculations(serviceType) {
    const form = document.querySelector(`form[id$="${serviceType}Form"]`);
    if (!form) return;

    form.querySelectorAll('input[type="radio"]').forEach(radio => {
        radio.addEventListener('change', () => {
            calculateAndUpdateTotals(form);
        });
    });

    // Initial calculation
    calculateAndUpdateTotals(form);
}

// New function to handle calculations
function calculateAndUpdateTotals(form) {
    const serviceType = form.id === 'homeStyleForm' ? 'homeStyleFood' : 'dishwashing';
    console.log('Form ID:', form.id); // Debug log
    console.log('Service Type:', serviceType); // Debug log

    const people = form.querySelector('input[name="people"]:checked')?.value;
    console.log('Selected People:', people); // Debug log
    
    let price = 0;
    let hours = 0;

    if (serviceType === 'homeStyleFood') {
        const meals = form.querySelector('input[name="meals"]:checked')?.value;
        const preference = form.querySelector('input[name="preference"]:checked')?.value;
        console.log('Selected Options:', { meals, preference }); // Debug log

        if (people && meals) {
            const workingHours = PRICING_DATA.workingHours.find(wh => wh.meal_type === meals);
            const pricingRule = PRICING_DATA.pricingRules.find(pr => pr.meal_type === meals);

            if (workingHours && pricingRule) {
                // Base calculations
                price = parseFloat(pricingRule.base_price);
                hours = parseFloat(workingHours.base_hours);

                // People count calculations
                if (people === '5-6' || people === '7-8') {
                    price += parseFloat(pricingRule.additional_person_rate_6_plus);
                    hours += people === '5-6' ? 
                        parseFloat(workingHours.additional_hours_5_6) : 
                        parseFloat(workingHours.additional_hours_7_8);
                } else if (parseInt(people) > 1) {
                    price += (parseInt(people) - 1) * parseFloat(pricingRule.additional_person_rate_1_5);
                }

                // Non-veg calculations
                if (preference === 'veg+non-veg') {
                    price += parseFloat(pricingRule.non_veg_surcharge);
                    hours += 0.5;
                }

                price *= 30; // Monthly price
            }
        }
    } else {
        // Dishwashing calculations
        if (people) {
            const basePrice = 500;
            const baseHours = 1;

            price = basePrice;
            hours = baseHours;

            const additionalHours = PRICING_DATA.additionalHours.find(ah => ah.people_count === people);
            const additionalCharges = PRICING_DATA.additionalCharges.find(ac => ac.people_count === people);

            if (additionalHours) {
                hours += parseFloat(additionalHours.additional_minutes);
            }

            if (additionalCharges) {
                if (['2', '3', '4'].includes(people)) {
                    const extraPeople = parseInt(people) - 1;
                    price += (parseFloat(additionalCharges.additional_charge) * extraPeople * 30);
                } else if (['5-6', '7-8'].includes(people)) {
                    price += (parseFloat(additionalCharges.additional_charge) * 30);
                }
            }
        }
    }

    console.log('Calculated Values:', { price, hours }); // Debug log
    
    // Update both modal and main footer
    updateDisplayedTotals(price, hours, serviceType);
}

// New function to update displayed totals
function updateDisplayedTotals(price, hours, serviceType) {
    console.log('Updating Displayed Totals:', { serviceType, price, hours }); // Debug log
    
    // Update modal footer
    const modalSalary = document.getElementById('modal-salary');
    const modalHours = document.getElementById('modal-hours');
    
    if (modalSalary && modalHours) {
        modalSalary.textContent = `Rs.${Math.round(price).toLocaleString()}`;
        modalHours.textContent = formatHours(hours);
        console.log('Modal Updated:', { 
            salary: modalSalary.textContent, 
            hours: modalHours.textContent 
        }); // Debug log
    }

    // Update main footer
    if (window.updateServiceTotals) {
        window.updateServiceTotals(serviceType, {
            monthly_price: price,
            working_hours: hours
        });
        console.log('Main Footer Update Called'); // Debug log
    } else {
        console.error('updateServiceTotals is not defined');
    }
}

// Update handleFormSubmit
function handleFormSubmit() {
    const form = document.querySelector('form');
    if (!form) {
        console.error('Form not found');
        return;
    }

    console.log('Form Submit - Form ID:', form.id); // Debug log

    if (!validateFormSelections()) {
        return;
    }

    const serviceType = form.id === 'homeStyleForm' ? 'homeStyleFood' : 'dishwashing';
    const modalSalary = document.getElementById('modal-salary');
    const modalHours = document.getElementById('modal-hours');
    
    const price = parseFloat(modalSalary.textContent.replace(/[^0-9.-]+/g, ''));
    const hours = parseTimeToHours(modalHours.textContent);

    console.log('Form Submit - Values:', { serviceType, price, hours }); // Debug log

    // Update service card
    updateServiceCard(serviceType, true);
    
    // Update main footer one final time
    window.updateServiceTotals(serviceType, {
        monthly_price: price,
        working_hours: hours
    });

    // Close modal
    closeModal();
}

// Helper function to format hours
function formatHours(hours) {
    const wholeHours = Math.floor(hours);
    const minutes = Math.round((hours - wholeHours) * 60);
    return `${wholeHours}:${minutes.toString().padStart(2, '0')}`;
}

// Function to validate form selections
function validateFormSelections() {
    const form = document.querySelector('form');
    if (!form) return false;

    // Define all required radio groups including 'dogs'
    let requiredGroups;
    
    if (form.id === 'homeStyleForm') {
        requiredGroups = ['people', 'meals', 'preference', 'dogs']; // Include dogs for home style food
    } else {
        requiredGroups = ['people']; // Only people count for dishwashing
    }
    
    // Check if each group has a selection
    for (const group of requiredGroups) {
        const selectedOption = form.querySelector(`input[name="${group}"]:checked`);
        if (!selectedOption) {
            // You can customize the error message based on the missing field
            const fieldNames = {
                'people': 'number of people',
                'meals': 'meals per day',
                'preference': 'food preference (Veg/Non-Veg)',
                'dogs': 'whether you have dogs'
            };
            alert(`Please select ${fieldNames[group]}`);
            return false;
        }
    }
    
    return true;
}

// Add this helper function to convert time string to hours
function parseTimeToHours(timeString) {
    const [hours, minutes] = timeString.split(':').map(Number);
    return hours + (minutes / 60);
}
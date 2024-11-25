<link rel="stylesheet" href="<?=ROOT?>/public/assets/css/serviceFooter.css">
<div class="form-footer">
    <table class="form-footer-table" id="form-footer-table">
        <tr>
            <td class="salary">Monthly Salary: <span class="coloredText" id="total-salary">Rs.0</span> approx</td>
            <td class="hours">Daily Working Hours: <span class="coloredText" id="total-hours">0:00</span> approx</td>
            <td class="next-btn">
                <button id="next-button">Next</button>
            </td>
        </tr>
        <tr class="row-2">
            <td colspan="3" class="estimate">
                *estimate varies with workload, shifts, timings, and location
            </td>
        </tr>
    </table>
</div>

<script>
function validateFormSelections() {
    const form = document.querySelector('form');
    if (!form) return false;

    // First check people selection for all forms
    if (!form.querySelector('input[name="people"]:checked')) {
        alert('Please select number of people');
        return false;
    }

    // For home style food form, check meals and preference
    if (form.id === 'homeStyleForm') {
        // Check meals
        if (!form.querySelector('input[name="meals"]:checked')) {
            alert('Please select number of meals');
            return false;
        }

        // Check preference
        if (!form.querySelector('input[name="preference"]:checked')) {
            alert('Please select your meal preference');
            return false;
        }

        // Check dogs selection
        if (!form.querySelector('input[name="dogs"]:checked')) {
            alert('Please select whether you have dog(s)');
            return false;
        }
    }

    return true;
}

function handleFormSubmit() {
    console.log('handleFormSubmit called');
    
    const form = document.querySelector('form');
    if (!form) {
        console.log('No form found');
        return;
    }

    // Validate form first
    if (!validateFormSelections()) {
        console.log('Validation failed');
        return;
    }

    console.log('Validation passed');

    const serviceType = form.id === 'homeStyleForm' ? 'homeStyleFood' : 'dishwashing';
    const totalSalary = document.getElementById('total-salary');
    const totalHours = document.getElementById('total-hours');
    
    // Update service card
    if (typeof updateServiceCard === 'function') {
        updateServiceCard(serviceType, true);
    }
    
    // Update main footer totals
    if (typeof updateServiceTotals === 'function') {
        const price = parseFloat(totalSalary.textContent.replace(/[^0-9.-]+/g, ''));
        const hours = parseTimeToHours(totalHours.textContent);
        
        window.updateServiceTotals(serviceType, {
            monthly_price: price,
            working_hours: hours
        });
    }

    // Close modal - try all possible methods
    try {
        // Method 1: Using closeModal function from modal.js
        if (typeof window.closeModal === 'function') {
            window.closeModal();
        }

        // Method 2: Direct DOM manipulation
        const modalOverlay = document.getElementById('modal-overlay');
        if (modalOverlay) {
            modalOverlay.style.display = 'none';
            modalOverlay.classList.remove('show');
        }

        // Method 3: Find and hide all modal-related elements
        document.querySelectorAll('.modal-overlay, .modal-content').forEach(el => {
            el.style.display = 'none';
            el.classList.remove('show');
        });

        console.log('Modal close attempted');
    } catch (error) {
        console.error('Error closing modal:', error);
    }
}

function parseTimeToHours(timeStr) {
    const [hours, minutes] = timeStr.split(':').map(Number);
    return hours + (minutes / 60);
}

// Initialize calculations when in modal
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    if (form) {
        const serviceType = form.id === 'homeStyleForm' ? 'home-style-food' : 'dishwashing';
        initializeFormCalculations(serviceType);
    }
});
</script>

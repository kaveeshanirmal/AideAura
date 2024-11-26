<link rel="stylesheet" href="<?=ROOT?>/public/assets/css/serviceFooter.css">
<div class="form-footer">
    <table class="form-footer-table" id="form-footer-table">
        <tr>
            <td class="salary">Monthly Salary: <span class="coloredText" id="total-salary">Rs.0</span> approx</td>
            <td class="hours">Daily Working Hours: <span class="coloredText" id="total-hours">0:00</span> approx</td>
            <td class="next-btn">
                <button id="next-button" type="button" onclick="handleButtonClick()">
                    <?php echo isset($isModal) ? 'Done' : 'Next'; ?>
                </button>
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
    
    if (!validateFormSelections()) {
        console.log('Validation failed');
        return;
    }
    
    console.log('Validation passed, closing modal');
    
    // Try all possible ways to close the modal
    const modalOverlay = document.getElementById('modal-overlay');
    const modalContent = document.querySelector('.modal-content');
    const serviceFormModal = document.querySelector('.service-form-modal');
    
    console.log('Found elements:', {
        modalOverlay: modalOverlay,
        modalContent: modalContent,
        serviceFormModal: serviceFormModal
    });

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

    // Also try using the closeModal function from modal.js
    if (typeof window.closeModal === 'function') {
        window.closeModal();
    }
}

function handleButtonClick() {
    const isModal = document.querySelector('.service-form-modal') !== null;
    
    if (isModal) {
        // For modal forms - use validation
        handleFormSubmit();
    } else {
        // For main page Next button - just navigate to next step
        if (typeof updateUI === 'function' && step < 3) {
            step++;
            updateUI();
        }
    }
}
</script>

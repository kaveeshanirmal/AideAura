<link rel="stylesheet" href="<?=ROOT?>/public/assets/css/serviceFooter.css">
<?php
// Get home-style food base values
$homeStyleBasePrice = $services['home-style-food']['basePrice'];
$homeStyleBaseHours = $services['home-style-food']['baseHours'];

// Use JavaScript to get the current total price for initial display
?>

<div class="form-footer">
    <table class="form-footer-table" id="form-footer-table">
        <tr>
            <td class="salary">Monthly Salary: <span class="coloredText" id="total-salary">Rs.<?php echo $homeStyleBasePrice * 30; ?></span> approx</td>
            <td class="hours">Daily Working Hours: <span class="coloredText" id="total-hours"><?php echo $homeStyleBaseHours . ":00"; ?></span> approx</td>
            <td class="next-btn">
                <button id="next-button" type="button" onclick="validateAndSubmit()">
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
// Initialize global values
if (!window.globalValues) {
    window.globalValues = {
        totalPrice: <?php echo $homeStyleBasePrice; ?>,
        baseHours: <?php echo $homeStyleBaseHours; ?>,  // Store base hours as number
        totalHours: "<?php echo $homeStyleBaseHours; ?>:00"
    };
}

// Track current charges for each question
window.currentCharges = window.currentCharges || {
    homeStyleForm: {
        people: 0,
        meals: 0,
        preference: 0,
        dogs: 0
    },
    dishwashingForm: {
        people: 0
    }
};

// Track current minutes for each question
window.currentMinutes = window.currentMinutes || {
    homeStyleForm: {
        people: 0,
        meals: 0,
        preference: 0,
        dogs: 0
    },
    dishwashingForm: {
        people: 0
    }
};

// Calculate additional charges for selections
function calculateAdditionalCharge(formId, field, value) {
    if (formId === 'homeStyleForm') {
        switch(field) {
            case 'people':
                if (['2', '3', '4'].includes(value)) return 50;
                if (['5-6', '7-8'].includes(value)) return 100;
                break;
            case 'meals':
                if (value === 'breakfast-lunch') return 200;
                if (value === 'all') return 400;
                break;
            case 'preference':
                if (value === 'veg+non-veg') return 50;
                break;
        }
    } else if (formId === 'dishwashingForm') {
        if (field === 'people') {
            if (['2', '3', '4'].includes(value)) return 75;
            if (['5-6', '7-8'].includes(value)) return 100;
        }
    }
    return 0;
}

// Update total price based on selection change
function updatePrice(formId, field, value) {
    // Get the new charge for this selection
    const newCharge = calculateAdditionalCharge(formId, field, value);
    
    // Subtract the old charge and add the new one
    const oldCharge = window.currentCharges[formId][field];
    window.globalValues.totalPrice = window.globalValues.totalPrice - oldCharge + newCharge;
    
    // Update the stored charge
    window.currentCharges[formId][field] = newCharge;
    
    console.log(`Price update for ${formId}.${field}:`, {
        oldCharge,
        newCharge,
        newTotal: window.globalValues.totalPrice
    });
}

// Calculate additional minutes for hours
function calculateAdditionalMinutes(formId, field, value) {
    if (formId === 'homeStyleForm') {
        switch(field) {
            case 'people':
                if (['5-6', '7-8'].includes(value)) return 15;
                break;
            case 'meals':
                if (value === 'breakfast-lunch') return 30;
                if (value === 'all') return 60;
                break;
            case 'preference':
                if (value === 'veg+non-veg') return 10;
                break;
        }
    } else if (formId === 'dishwashingForm') {
        if (field === 'people' && ['5-6', '7-8'].includes(value)) return 15;
    }
    return 0;
}

// Update hours based on selection change
function updateHours(formId, field, value) {
    // Get the new minutes for this selection
    const newMinutes = calculateAdditionalMinutes(formId, field, value);
    
    // Get base hours in minutes
    let baseMinutes = window.globalValues.baseHours * 60;
    
    // Remove old minutes for this field
    const oldMinutes = window.currentMinutes[formId][field];
    
    // Store new minutes for this field
    window.currentMinutes[formId][field] = newMinutes;
    
    // Calculate total additional minutes from all selections
    let totalAdditionalMinutes = 0;
    Object.entries(window.currentMinutes).forEach(([form, fields]) => {
        Object.values(fields).forEach(minutes => {
            totalAdditionalMinutes += minutes;
        });
    });
    
    // Calculate total minutes (base + additional)
    const totalMinutes = baseMinutes + totalAdditionalMinutes;
    
    // Convert to hours:minutes format
    const hours = Math.floor(totalMinutes / 60);
    const minutes = totalMinutes % 60;
    window.globalValues.totalHours = `${hours}:${minutes.toString().padStart(2, '0')}`;
    
    console.log(`Hours update for ${formId}.${field}:`, {
        baseMinutes,
        oldMinutes,
        newMinutes,
        totalAdditionalMinutes,
        totalMinutes,
        newTotalHours: window.globalValues.totalHours
    });
}

// Main function to update all footers
function updateAllFooters() {
    const form = document.querySelector('form');
    if (form) {
        const formId = form.id;
        const formData = new FormData(form);
        const currentSelections = Object.fromEntries(formData);

        // Update price and hours for each selection
        Object.entries(currentSelections).forEach(([field, value]) => {
            updatePrice(formId, field, value);
            updateHours(formId, field, value);
        });
    }

    // Update display
    const monthlySalary = `Rs.${window.globalValues.totalPrice * 30}`;
    document.querySelectorAll('#total-salary').forEach(element => {
        element.textContent = monthlySalary;
    });
    
    document.querySelectorAll('#total-hours').forEach(element => {
        element.textContent = window.globalValues.totalHours;
    });

    console.log('Footer updated with:', {
        totalPrice: window.globalValues.totalPrice,
        totalHours: window.globalValues.totalHours,
        currentCharges: window.currentCharges,
        currentMinutes: window.currentMinutes
    });
}

// Validate form selections
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

// Handle form submission and modal closing
function validateAndSubmit() {
    if (validateFormSelections()) {
        // Update all footers with final values
        updateAllFooters();
        
        // If in modal, close it
        if (window.closeModal) {
            closeModal();
        }
        
        console.log('Form validated and submitted successfully');
        return true;
    }
    return false;
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    // Set initial values
    updateAllFooters();
    
    // Listen for changes
    document.addEventListener('change', function(event) {
        if (event.target.type === 'radio') {
            const form = event.target.closest('form');
            console.log('Selection changed:', {
                form: form?.id,
                field: event.target.name,
                value: event.target.value
            });
            updateAllFooters();
        }
    });

    // Add click handler for Done/Next button
    const nextButton = document.getElementById('next-button');
    if (nextButton) {
        nextButton.onclick = validateAndSubmit;
    }
});
</script>

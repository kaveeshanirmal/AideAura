document.addEventListener('DOMContentLoaded', function() {
    // Function to get exact total hours including minutes
    function getExactTotalHours() {
        if (window.globalValues && window.globalValues.totalHours) {
            const [hours, minutes] = window.globalValues.totalHours.split(':').map(Number);
            return hours + (minutes / 60);
        }
        return 2; // Default value
    }

    // Initialize variables
    let TOTAL_HOURS = getExactTotalHours();
    const SHIFT_BREAK = 2.5;
    let remainingHours = TOTAL_HOURS;
    
    console.log('Initial TOTAL_HOURS (exact):', TOTAL_HOURS);

    // Counter elements
    const minusBtn = document.querySelector('.minus-btn');
    const plusBtn = document.querySelector('.plus-btn');
    const shiftCount = document.querySelector('.shift-count');
    const secondShiftRow = document.querySelector('.second-shift');

    // Modify first shift duration options based on total hours
    function updateFirstShiftDurationOptions() {
        const firstDurationSelect = document.getElementById('firstShiftDuration');
        if (!firstDurationSelect) return;

        firstDurationSelect.innerHTML = '<option value="" disabled selected>Select Duration</option>';
        
        // Get floor value of total hours for dropdown options
        const floorHours = Math.floor(TOTAL_HOURS);
        
        // Start from 0.5 and increment by 0.5 up to floor hours
        for (let i = 0.5; i <= floorHours; i += 0.5) {
            const hours = i;
            const label = hours === 1 ? '1 hour' : `${hours} hours`;
            firstDurationSelect.add(new Option(label, hours.toString()));
            console.log('Adding option:', label, hours);
        }
        
        console.log('Updated duration options for total hours:', TOTAL_HOURS, 'Floor:', floorHours);
    }

    // Handle first shift duration change
    function handleFirstShiftDurationChange(event) {
        const selectedDuration = parseFloat(event.target.value);
        
        // Get exact total hours again to ensure we have the latest value
        TOTAL_HOURS = getExactTotalHours();
        remainingHours = TOTAL_HOURS - selectedDuration;
        
        // Update second shift duration display
        const secondShiftDuration = document.getElementById('secondShiftDuration');
        if (secondShiftDuration) {
            if (remainingHours >= 0.5) {
                // Convert decimal hours to hours and minutes for display
                const remainingWholeHours = Math.floor(remainingHours);
                const remainingMinutes = Math.round((remainingHours - remainingWholeHours) * 60);
                
                // Format the display string
                let displayValue = '';
                if (remainingWholeHours > 0) {
                    displayValue += `${remainingWholeHours} hour${remainingWholeHours !== 1 ? 's' : ''}`;
                }
                if (remainingMinutes > 0) {
                    if (displayValue) displayValue += ' ';
                    displayValue += `${remainingMinutes} minutes`;
                }
                
                secondShiftDuration.value = displayValue;
                
                console.log('Remaining time calculation:', {
                    totalHours: TOTAL_HOURS,
                    selectedDuration,
                    remainingHours,
                    remainingWholeHours,
                    remainingMinutes
                });
            } else {
                secondShiftDuration.value = 'Not available';
            }
        }
        
        // If selected duration equals total hours or remaining is less than 0.5, hide second shift
        if (Math.abs(selectedDuration - TOTAL_HOURS) < 0.01 || remainingHours < 0.5) {
            if (secondShiftRow) {
                secondShiftRow.style.display = 'none';
                if (shiftCount) shiftCount.textContent = '1';
                if (plusBtn) plusBtn.disabled = true;
            }
        } else {
            if (plusBtn) plusBtn.disabled = false;
        }
    }

    // Initialize event listeners
    const firstDurationSelect = document.getElementById('firstShiftDuration');
    if (firstDurationSelect) {
        firstDurationSelect.addEventListener('change', handleFirstShiftDurationChange);
    }

    // Update when total hours change
    window.addEventListener('totalHoursUpdated', function() {
        TOTAL_HOURS = getExactTotalHours();
        remainingHours = TOTAL_HOURS;
        updateFirstShiftDurationOptions();
        console.log('Total hours updated (exact):', TOTAL_HOURS);
    });

    // Initialize duration options
    updateFirstShiftDurationOptions();

    // Plus/Minus button handlers
    if (plusBtn) {
        plusBtn.addEventListener('click', function() {
            const currentCount = parseInt(shiftCount.textContent);
            if (currentCount < 2 && remainingHours >= 0.5) {
                shiftCount.textContent = '2';
                secondShiftRow.style.display = 'flex';
            }
        });
    }

    if (minusBtn) {
        minusBtn.addEventListener('click', function() {
            const currentCount = parseInt(shiftCount.textContent);
            if (currentCount > 1) {
                shiftCount.textContent = '1';
                secondShiftRow.style.display = 'none';
            }
        });
    }
});
document.addEventListener('DOMContentLoaded', function() {
    // Function to get current total hours
    function getCurrentTotalHours() {
        if (window.globalValues && window.globalValues.totalHours) {
            const [hours, minutes] = window.globalValues.totalHours.split(':').map(Number);
            return hours + (minutes / 60);
        }
        return 2; // Default value
    }

    // Initialize variables
    let TOTAL_HOURS = getCurrentTotalHours();
    const SHIFT_BREAK = 2.5;
    let remainingHours = 0;
    
    console.log('Initial TOTAL_HOURS:', TOTAL_HOURS);

    // Update working hours display
    function updateWorkingHoursDisplay() {
        const workingHoursDisplay = document.querySelector('.working-hours');
        if (workingHoursDisplay && window.globalValues?.totalHours) {
            workingHoursDisplay.textContent = `Daily Working Hours - ${window.globalValues.totalHours}`;
            TOTAL_HOURS = getCurrentTotalHours(); // Update TOTAL_HOURS
            updateFirstShiftDurationOptions(); // Update duration options
            console.log('Updated TOTAL_HOURS to:', TOTAL_HOURS);
        }
    }

    // Call initially
    updateWorkingHoursDisplay();

    // Watch for changes in global values
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (window.globalValues?.totalHours) {
                updateWorkingHoursDisplay();
            }
        });
    });

    // Observe changes to globalValues
    if (window.globalValues) {
        observer.observe(window.globalValues, {
            attributes: true,
            attributeFilter: ['totalHours']
        });
    }

    // Rest of your existing code...

    // Modify first shift duration options based on current total hours
    function updateFirstShiftDurationOptions() {
        const firstDurationSelect = document.getElementById('firstShiftDuration');
        if (!firstDurationSelect) return;

        firstDurationSelect.innerHTML = '<option value="" disabled selected>Select Duration</option>';
        
        // Add options up to current total hours
        for (let i = 1; i <= TOTAL_HOURS; i += 0.5) {
            const label = i === 1 ? '1 hour' : `${i} hours`;
            firstDurationSelect.add(new Option(label, i.toString()));
        }
        console.log('Updated duration options for total hours:', TOTAL_HOURS);
    }

    // Initialize duration options
    updateFirstShiftDurationOptions();
});
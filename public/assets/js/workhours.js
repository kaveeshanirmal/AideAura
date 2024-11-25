document.addEventListener('DOMContentLoaded', function() {
    // Counter elements
    const minusBtn = document.querySelector('.minus-btn');
    const plusBtn = document.querySelector('.plus-btn');
    const shiftCount = document.querySelector('.shift-count');
    const secondShiftRow = document.querySelector('.second-shift');
    let currentCount = 1;
    const TOTAL_HOURS = 2;
    const SHIFT_BREAK = 2.5;
    let remainingHours = 0;

    // Update buttons state
    function updateButtons() {
        minusBtn.disabled = currentCount <= 1;
        plusBtn.disabled = currentCount >= 2;
        
        minusBtn.style.backgroundColor = minusBtn.disabled ? '#ccc' : '#4c9ebe';
        plusBtn.style.backgroundColor = plusBtn.disabled ? '#ccc' : '#4c9ebe';
    }

    // Counter click handlers
    minusBtn.addEventListener('click', function() {
        if (currentCount > 1) {
            currentCount--;
            shiftCount.textContent = currentCount;
            secondShiftRow.style.display = 'none';
            updateButtons();
        }
    });

    plusBtn.addEventListener('click', function() {
        if (currentCount < 2) {
            currentCount++;
            shiftCount.textContent = currentCount;
            updateButtons();
        }
    });

    function calculateFirstShift() {
        const firstDurationSelect = document.getElementById('firstShiftDuration');
        const firstStartSelect = document.getElementById('firstStartTime');
        const firstEndTimeSpan = document.getElementById('firstEndTime');
        const secondDurationInput = document.getElementById('secondShiftDuration');

        if (firstDurationSelect.value && firstStartSelect.value) {
            const duration = parseFloat(firstDurationSelect.value);
            const [hours, minutes] = firstStartSelect.value.split(':').map(Number);
            
            let endHours = hours + Math.floor(duration);
            let endMinutes = minutes + (duration % 1) * 60;
            
            if (endMinutes >= 60) {
                endHours += 1;
                endMinutes -= 60;
            }
            
            const period = endHours >= 12 ? 'PM' : 'AM';
            const displayHours = endHours > 12 ? endHours - 12 : endHours;
            
            firstEndTimeSpan.textContent = `${displayHours}:${endMinutes.toString().padStart(2, '0')} ${period}`;
            
            // Calculate second shift details
            remainingHours = TOTAL_HOURS - duration;
            // Use singular 'hour' when remaining time is 1
            const hourText = remainingHours === 1 ? 'hour' : 'hours';
            secondDurationInput.value = `${remainingHours} ${hourText}`;
            updateSecondShiftTimes(endHours, endMinutes);
            
            // Show second shift row as soon as first shift end time is calculated
            secondShiftRow.style.display = 'flex';
            
        } else {
            firstEndTimeSpan.textContent = '--:--';
            secondDurationInput.value = '';
            document.getElementById('secondEndTime').textContent = '--:--';
            secondShiftRow.style.display = 'none';
        }
    }

    function updateSecondShiftTimes(firstEndHours, firstEndMinutes) {
        const secondStartSelect = document.getElementById('secondStartTime');
        
        // Add break time (2.5 hours = 2 hours and 30 minutes)
        let startHour = firstEndHours + Math.floor(SHIFT_BREAK); // This gives us 2 hours
        let startMinutes = firstEndMinutes + ((SHIFT_BREAK % 1) * 60); // This gives us 30 minutes
        
        // Adjust if minutes exceed 60
        if (startMinutes >= 60) {
            startHour += 1;
            startMinutes -= 60;
        }

        // Clear existing options
        secondStartSelect.innerHTML = '<option value="" disabled selected>Select Time</option>';
        
        // Add options from break end until 6 PM
        while (startHour < 18) { // 18 = 6 PM
            const timeString = `${startHour}:${startMinutes.toString().padStart(2, '0')}`;
            const displayHour = startHour > 12 ? startHour - 12 : startHour;
            const period = startHour >= 12 ? 'PM' : 'AM';
            const displayTime = `${displayHour}:${startMinutes.toString().padStart(2, '0')} ${period}`;
            
            secondStartSelect.add(new Option(displayTime, timeString));

            startMinutes += 30;
            if (startMinutes >= 60) {
                startHour += 1;
                startMinutes = 0;
            }
        }
    }

    function calculateSecondShift() {
        const secondStartSelect = document.getElementById('secondStartTime');
        const secondEndTimeSpan = document.getElementById('secondEndTime');

        if (secondStartSelect.value && remainingHours > 0) {
            const [hours, minutes] = secondStartSelect.value.split(':').map(Number);
            
            let endHours = hours + Math.floor(remainingHours);
            let endMinutes = minutes + (remainingHours % 1) * 60;
            
            if (endMinutes >= 60) {
                endHours += 1;
                endMinutes -= 60;
            }
            
            const period = endHours >= 12 ? 'PM' : 'AM';
            const displayHours = endHours > 12 ? endHours - 12 : endHours;
            
            secondEndTimeSpan.textContent = `${displayHours}:${endMinutes.toString().padStart(2, '0')} ${period}`;
        } else {
            secondEndTimeSpan.textContent = '--:--';
        }
    }

    // Event Listeners
    const firstDurationSelect = document.getElementById('firstShiftDuration');
    const firstStartSelect = document.getElementById('firstStartTime');
    const secondStartSelect = document.getElementById('secondStartTime');

    firstDurationSelect.addEventListener('change', calculateFirstShift);
    firstStartSelect.addEventListener('change', calculateFirstShift);
    secondStartSelect.addEventListener('change', calculateSecondShift);

    // Initialize
    updateButtons();

    // Date Picker Functionality
    const datePicker = document.getElementById('startDate');
    
    // Calculate minimum date (3 days from today)
    const today = new Date();
    const minDate = new Date(today);
    minDate.setDate(today.getDate() + 3);
    
    // Optional: Set maximum date (e.g., 6 months from today)
    const maxDate = new Date(today);
    maxDate.setMonth(today.getMonth() + 6);
    
    // Format dates for the input
    datePicker.min = minDate.toISOString().split('T')[0];
    datePicker.max = maxDate.toISOString().split('T')[0];
    
    // Set default value to minimum date
    datePicker.value = minDate.toISOString().split('T')[0];
    
    // Only validate minimum date
    datePicker.addEventListener('input', function() {
        const selectedDate = new Date(this.value);
        
        // Check if selected date is valid
        if (selectedDate < minDate) {
            // If selected date is before minimum date, reset to minimum date
            this.value = minDate.toISOString().split('T')[0];
            alert('Please select a date at least 3 days from today.');
        }
    });

    // Format the displayed date
    function formatDisplayDate(dateString) {
        const date = new Date(dateString);
        const options = { 
            weekday: 'long', 
            year: 'numeric', 
            month: 'long', 
            day: 'numeric' 
        };
        return date.toLocaleDateString('en-US', options);
    }

    // Update displayed date format when date changes
    datePicker.addEventListener('change', function() {
        if (this.value) {
            const formattedDate = formatDisplayDate(this.value);
            // If you have a span or div to show the formatted date:
            // document.getElementById('formattedDate').textContent = formattedDate;
        }
    });
});
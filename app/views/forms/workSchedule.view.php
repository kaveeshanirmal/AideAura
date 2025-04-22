<!-- Work Schedule Content (Step 2) -->
<div class="schedule-container">
    <label class="service-name">Select Working Shifts</label>
    <div class="pricing-messages">
        <div class="surge-pricing">
            <i class="fas fa-exclamation-circle"></i>
            Surge pricing between 7:00 - 8:30 AM/PM
        </div>
        <div class="discount-pricing">
            <i class="fas fa-tag"></i>
            Special discounts between 12:00 PM to 4:00 PM
        </div>
    </div>

    <div class="shifts-container">
        <!-- First Section -->
        <div class="shift-section">
            <table class="shifts-table">
                <tr>
                    <td>Work Shifts</td>
                    <td>
                        <div class="shift-counter">
                            <button class="counter-btn minus-btn">
                                <i class="fas fa-minus"></i>
                            </button>
                            <span class="shift-count">1</span>
                            <button class="counter-btn plus-btn">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        <div class="section-divider"></div>

        <!-- Second Section -->
        <div class="shift-section">
            <div class="working-hours" id="workingHoursDisplay">
                Daily Working Hours - <span id="totalHoursDisplay">
                    <?php echo isset($services['home-style-food']['baseHours']) ? 
                        $services['home-style-food']['baseHours'] . ':00' : 
                        '2:00'; ?>
                </span>
            </div>
        </div>

        <div class="section-divider"></div>

        <!-- Time Selection Section -->
        <div class="shift-section">
            <!-- First Shift -->
            <div class="shift-time-row">
                <div class="time-column">
                    <label>First Work Shift</label>
                    <select class="duration-select" id="firstShiftDuration">
                        <option value="" disabled selected>Select Duration</option>
                        <!-- Options will be populated by JavaScript -->
                    </select>
                </div>
                
                <div class="time-column">
                    <label>Start Time</label>
                    <select class="time-select" id="firstStartTime">
                        <option value="" disabled selected>Select Time</option>
                        <option value="5:30">5:30 AM</option>
                        <option value="6:00">6:00 AM</option>
                        <option value="6:30">6:30 AM</option>
                        <option value="7:00">7:00 AM</option>
                        <option value="7:30">7:30 AM</option>
                        <option value="8:00">8:00 AM</option>
                        <option value="8:30">8:30 AM</option>
                        <option value="9:00">9:00 AM</option>
                        <option value="9:30">9:30 AM</option>
                        <option value="10:00">10:00 AM</option>
                        <option value="10:30">10:30 AM</option>
                        <option value="11:00">11:00 AM</option>
                    </select>
                </div>
                
                <div class="time-column">
                    <label>End Time</label>
                    <span id="firstEndTime" class="end-time">--:--</span>
                </div>
            </div>

            <!-- Small gap between shifts -->
            <div style="height: 15px;"></div>

            <!-- Second Shift -->
            <div class="shift-time-row second-shift" style="display: none;">
                <div class="time-column">
                    <label>Second Work Shift</label>
                    <input type="text" class="duration-display" id="secondShiftDuration" readonly>
                </div>
                
                <div class="time-column">
                    <label>Start Time</label>
                    <select class="time-select" id="secondStartTime">
                        <option value="" disabled selected>Select Time</option>
                    </select>
                </div>
                
                <div class="time-column">
                    <label>End Time</label>
                    <span id="secondEndTime" class="end-time">--:--</span>
                </div>
            </div>
        </div>
    </div>

    <!-- After the shifts-container div -->
    <div class="start-date-section">
        <div class="date-labels">
            <label class="service-name">Start Date</label>
            <p class="date-message">Please select the date of starting the service</p>
        </div>
        <div class="date-picker-container">
            <?php
                // Calculate min date (3 days from today)
                $minDate = date('Y-m-d', strtotime('+3 days'));
                
                // Optional: Set a max date (e.g., 6 months from today)
                $maxDate = date('Y-m-d', strtotime('+6 months'));
            ?>
            <input type="date" 
                   id="startDate" 
                   class="date-picker"
                   min="<?php echo $minDate; ?>"
                   max="<?php echo $maxDate; ?>"
                   value="<?php echo $minDate; ?>">  <!-- Optional: Set default value to min date -->
        </div>
    </div>

    <!-- Add the new Notes section -->
    <div class="notes-section">
        <div class="notes-labels">
            <label class="service-name">Notes</label>
            <p class="notes-message">Additional notes (if any)...</p>
        </div>
        <div class="notes-container">
            <textarea 
                id="serviceNotes" 
                class="notes-textarea" 
                placeholder="Notes..."
                rows="4"
            ></textarea>
        </div>
    </div>

    <!-- Add new Preference section -->
    <div class="preference-section">
        <label class="service-name">Preference</label>
        <div class="preference-container">
            <label class="toggle">
                <input type="checkbox" id="preferenceToggle">
                <span class="slider"></span>
            </label>
            <p class="preference-message">
                If you HAVE a religious preference, the salary of the worker would be 10% more than the 
                estimated shown salary. Mention the preference type in the Notes section.
            </p>
        </div>
    </div>

</div>

<!-- Add this script at the bottom of the file -->
<script>
// Function to update the working hours display
function updateWorkingHoursDisplay() {
    const totalHoursDisplay = document.getElementById('totalHoursDisplay');
    if (window.globalValues && window.globalValues.totalHours) {
        totalHoursDisplay.textContent = window.globalValues.totalHours;
        
        // Dispatch event to update duration options
        window.dispatchEvent(new Event('totalHoursUpdated'));
        
        console.log('Work Schedule: Updated hours to', window.globalValues.totalHours);
    }
}

// Calculate end time based on start time and duration
function calculateEndTime(startTime, duration) {
    if (!startTime || !duration) return '--:--';
    
    // Parse start time
    const [hours, minutes] = startTime.split(':').map(Number);
    
    // Handle exact duration including decimal parts
    const durationHours = Math.floor(duration);
    const durationMinutes = Math.round((duration - durationHours) * 60); // More precise calculation
    
    let endHours = hours + durationHours;
    let endMinutes = minutes + durationMinutes;
    
    // Handle minute overflow
    if (endMinutes >= 60) {
        endHours += Math.floor(endMinutes / 60);
        endMinutes = endMinutes % 60;
    }
    
    // Format with AM/PM
    const period = endHours >= 12 ? 'PM' : 'AM';
    endHours = endHours > 12 ? endHours - 12 : (endHours === 0 ? 12 : endHours);
    
    // Ensure minutes are properly padded
    const formattedMinutes = endMinutes.toString().padStart(2, '0');
    
    console.log('End time calculation:', {
        startTime,
        duration,
        durationHours,
        durationMinutes,
        endHours,
        endMinutes
    });
    
    return `${endHours}:${formattedMinutes} ${period}`;
}

// Update end time when start time or duration changes
document.getElementById('firstStartTime').addEventListener('change', function() {
    const duration = parseFloat(document.getElementById('firstShiftDuration').value) || 0;
    const endTime = calculateEndTime(this.value, duration);
    document.getElementById('firstEndTime').textContent = endTime;
});

document.getElementById('firstShiftDuration').addEventListener('change', function() {
    const startTime = document.getElementById('firstStartTime').value;
    const endTime = calculateEndTime(startTime, parseFloat(this.value));
    document.getElementById('firstEndTime').textContent = endTime;
});

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    updateWorkingHoursDisplay();
    
    // Disable plus button initially
    const plusBtn = document.querySelector('.plus-btn');
    if (plusBtn) plusBtn.disabled = true;
});

// Listen for changes to total hours
window.addEventListener('totalHoursUpdated', function() {
    updateWorkingHoursDisplay();
});

// Debug function to check total hours and populate duration options
function populateFirstShiftDurations() {
    const firstDurationSelect = document.getElementById('firstShiftDuration');
    if (!firstDurationSelect) {
        console.error('First duration select not found');
        return;
    }

    // Clear existing options
    firstDurationSelect.innerHTML = '<option value="" disabled selected>Select Duration</option>';
    
    // Get total hours from global values
    let totalHours = 2; // Default
    if (window.globalValues && window.globalValues.totalHours) {
        const [hours, minutes] = window.globalValues.totalHours.split(':').map(Number);
        totalHours = hours + (minutes / 60);
    }
    
    // Get floor value of total hours
    const floorHours = Math.floor(totalHours);
    console.log('Populating durations for total hours:', totalHours, 'Floor:', floorHours);
    
    // Add options from 0.5 to floor hours
    for (let i = 0.5; i <= floorHours; i += 0.5) {
        const label = i === 1 ? '1 hour' : `${i} hours`;
        const option = new Option(label, i.toString());
        firstDurationSelect.add(option);
        console.log('Added option:', label, i);
    }
}

// Call on page load and when total hours update
document.addEventListener('DOMContentLoaded', function() {
    console.log('Page loaded, initial global values:', window.globalValues);
    populateFirstShiftDurations();
});

window.addEventListener('totalHoursUpdated', function() {
    console.log('Hours updated, current global values:', window.globalValues);
    populateFirstShiftDurations();
});
</script>
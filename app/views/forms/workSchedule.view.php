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
                        <option value="1">1 hour</option>
                        <option value="1.5">1.5 hours</option>
                        <option value="2">2 hours</option>
                        <option value="2.5">2.5 hours</option>
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

    <!-- After the Notes section -->
    <div class="preference-section">
        <label class="service-name">Preference</label>
        <div class="preference-container">
            <label class="toggle">
                <input type="checkbox" id="preferenceToggle">
                <span class="slider"></span>
            </label>
            <p class="preference-message">
                If you HAVE a religious preference, the salary of the worker would be 10% more than the estimated shown salary.
                In the Notes area mention your preference type if you have any.
            </p>
        </div>
    </div>

    <!-- After the Preference section -->
    <div class="agreement-section">
        <div class="agreement-container">
            <label class="agreement-checkbox">
                <input type="checkbox" id="agreementCheck" checked>
                <span class="checkmark"></span>
            </label>
            <div class="agreement-box">
                <p class="agreement-message">
                    I agree to pay Monthly salary of the worker through AideAura's online platform(s) only. The salary will be as shown here (approx.) and this includes 2 paid leaves per month which can be encashed if not taken by the worker.
                </p>
            </div>
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
        console.log('Work Schedule: Updated hours to', window.globalValues.totalHours);
    }
}

// Update on page load
document.addEventListener('DOMContentLoaded', function() {
    updateWorkingHoursDisplay();
});

// Listen for changes to total hours
window.addEventListener('totalHoursUpdated', function() {
    updateWorkingHoursDisplay();
});
</script>
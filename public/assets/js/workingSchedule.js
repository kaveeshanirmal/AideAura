document.addEventListener("DOMContentLoaded", () => {
    console.log("DOM fully loaded");
    let isEditMode = false;

    // Delegate events to the schedule container
    const scheduleContainer = document.querySelector(".schedule-container");

    scheduleContainer.addEventListener("click", (event) => {
        if (event.target.matches(".add-schedule-btn")) {
            console.log("Add Schedule button clicked");
            addNewScheduleRow();
        } else if (event.target.matches(".delete-btn")) {
            console.log("Delete button clicked");
            handleDelete(event);
        } else if (event.target.matches(".add-slot-btn")) {
            console.log("Add Slot button clicked");
            if (validateTimeSlotCount()) {
                handleAddSlot(event);
            }
        } else if (event.target.matches(".save-changes-btn")) {
            console.log("Save Changes button clicked");
            validateSchedule();
        } else if (event.target.matches(".close-overlay-btn")) {
            console.log("Close Error button clicked");
            const errorOverlay = document.querySelector(".error-overlay");
            errorOverlay.style.display = "none";
            const errorList = document.querySelector(".error-list");
            errorList.innerHTML = "";
        } else if (event.target.matches(".edit-btn")) {
            console.log("Edit button clicked");
            makeTableEditable();
        } else if (event.target.matches(".cancel-changes-btn")) {
            console.log("Cancel button clicked");
            restoreTableToViewMode();
        }
    });
});

// Function to get the list of remaining days
function getRemainingDays() {
    const selectedDays = Array.from(
        document.querySelectorAll(".day-select")
    ).map((select) => select.value);

    const allDays = [
        "Monday",
        "Tuesday",
        "Wednesday",
        "Thursday",
        "Friday",
        "Saturday",
        "Sunday",
    ];
    return allDays.filter((day) => !selectedDays.includes(day));
}

// Function to add a new schedule row
function addNewScheduleRow() {
    const remainingDays = getRemainingDays();

    if (remainingDays.length === 0) {
        alert("All days have already been selected.");
        return;
    }

    const tableBody = document.querySelector(".schedule-table tbody");
    const newRow = document.createElement("tr");

    newRow.innerHTML = `
        <td>
            <select class="day-select" name="day" editable="true">
                ${remainingDays
                    .map((day) => `<option value="${day}">${day}</option>`)
                    .join("")}
            </select>
       </td>
        <td><input type="time" class="time-input" value="09:00"></td>
        <td><input type="time" class="time-input" value="12:00"></td>
        <td>
            <button class="add-slot-btn">Add Slot</button>
            <button class="delete-btn">Delete</button>
        </td>
    `;

    tableBody.appendChild(newRow);
    console.log("New schedule row added");
}

// Handle delete button click
function handleDelete(event) {
    const row = event.target.closest("tr");
    row.remove();
    console.log("Row deleted");
}

// Handle add-slot button click
function handleAddSlot(event) {
    const currentRow = event.target.closest("tr");
    const daySelect = currentRow.querySelector(".day-select");
    const day = daySelect.value;

    const tableBody = currentRow.parentElement;
    const newRow = document.createElement("tr");

    newRow.innerHTML = `
        <td></td> <!-- Empty cell for day -->
        <input type="hidden" class="day-select" value="${day}">
        <td><input type="time" class="time-input" value="09:00"></td>
        <td><input type="time" class="time-input" value="12:00"></td>
        <td>
            <button class="add-slot-btn">Add Slot</button>
            <button class="delete-btn">Delete</button>
        </td>
    `;

    tableBody.insertBefore(newRow, currentRow.nextSibling);
    console.log(`New time slot added for ${day}`);
}

function validateSchedule() {
    const rows = document.querySelectorAll(".schedule-table tbody tr");
    const daySchedules = {};
    const errorOverlay = document.querySelector(".error-overlay");
    const errorList = document.querySelector(".error-list");
    let hasErrors = false;

    // Clear previous error messages
    errorList.innerHTML = "";

    rows.forEach((row) => {
        const daySelect = row.querySelector(".day-select");
        const startTimeInput = row.querySelectorAll('input[type="time"]')[0]; // First time input
        const endTimeInput = row.querySelectorAll('input[type="time"]')[1]; // Second time input

        if (!daySelect || !startTimeInput || !endTimeInput) return;

        const day = daySelect.value;
        const startTime = startTimeInput.value;
        const endTime = endTimeInput.value;

        if (day && startTime && endTime) {
            // Validate startTime < endTime
            const timeRangeError = validateTimeRange(startTime, endTime);
            if (timeRangeError) {
                hasErrors = true;
                const errorItem = document.createElement("li");
                errorItem.textContent = `For ${day}: ${timeRangeError}`;
                errorList.appendChild(errorItem);
                console.error(timeRangeError);
                return; // Skip further validation if time range is invalid
            }

            if (!daySchedules[day]) {
                daySchedules[day] = [];
            }

            const newSlot = { startTime, endTime };

            // Check for overlaps
            const overlapError = checkForOverlaps(daySchedules[day], newSlot);
            if (overlapError) {
                hasErrors = true;
                const errorItem = document.createElement("li");
                errorItem.textContent = `For ${day}: ${overlapError}`;
                errorList.appendChild(errorItem);
                console.error(overlapError);
                return; // Skip adding the slot since it's invalid
            }

            // Add the current slot to the day's schedule if no errors
            daySchedules[day].push(newSlot);
        }
    });

    if (!hasErrors) {
        restoreTableToViewMode();
        alert("Changes saved!");
    } else {
        errorOverlay.style.display = "flex";
    }
}

function checkForOverlaps(schedule, newSlot) {
    for (const slot of schedule) {
        if (
            (newSlot.startTime >= slot.startTime &&
                newSlot.startTime < slot.endTime) || // Overlaps at start
            (newSlot.endTime > slot.startTime &&
                newSlot.endTime <= slot.endTime) || // Overlaps at end
            (newSlot.startTime <= slot.startTime &&
                newSlot.endTime >= slot.endTime) // Fully overlaps
        ) {
            return `Overlapping time slots detected: ${newSlot.startTime} - ${newSlot.endTime}`;
        }
    }
    return null;
}

function validateTimeRange(startTime, endTime) {
    if (startTime >= endTime) {
        return `Start time (${startTime}) must be earlier than end time (${endTime}).`;
    }
    return null;
}

function validateTimeSlotCount() {
    const rows = document.querySelectorAll(".schedule-table tbody tr");
    const dayCount = {}; // Object to track the count of rows for each day

    rows.forEach((row) => {
        const daySelect = row.querySelector(".day-select");

        if (daySelect) {
            const day = daySelect.value;

            // Increment the count for the day
            if (!dayCount[day]) {
                dayCount[day] = 0;
            }
            dayCount[day]++;
        }
    });

    // Check if any day exceeds 5 slots
    for (const day in dayCount) {
        if (dayCount[day] > 5) {
            const errorOverlay = document.querySelector(".error-overlay");
            const errorList = document.querySelector(".error-list");
            errorList.innerHTML = `You can only select 5 slots per day. '${day}' has ${dayCount[day]} slots.`;
            errorOverlay.style.display = "flex";
            return false;
        }
    }

    return true;
}

function makeTableEditable() {
    const rows = document.querySelectorAll(".schedule-table tbody tr");
    const processedDays = new Set(); // Track days that have been processed

    rows.forEach((row) => {
        const cells = row.querySelectorAll("td");
        if (cells.length === 4) {
            // Extract data from cells
            const dayCell = cells[0];
            const startTimeCell = cells[1];
            const endTimeCell = cells[2];
            const actionsCell = cells[3];

            const day = dayCell.textContent.trim();
            const startTime = startTimeCell.textContent.trim();
            const endTime = endTimeCell.textContent.trim();
            actionsCell.style.display = "block";

            // Check if the day has already been processed
            if (!processedDays.has(day)) {
                // If not processed, show the dropdown
                dayCell.innerHTML = `<select class="day-select"> 
                    <option value="${day}">${day}</option></select>`;
                processedDays.add(day); // Mark the day as processed
            } else {
                // If already processed, render as a hidden input
                dayCell.innerHTML = `
                <input type="hidden" class="day-select" value="${day}">
            `;
            }

            // Render other cells as editable inputs
            startTimeCell.innerHTML = `<input type="time" class="time-input" value="${startTime}">`;
            endTimeCell.innerHTML = `<input type="time" class="time-input" value="${endTime}">`;

            actionsCell.innerHTML = `
            <button class="add-slot-btn">Add Slot</button>
            <button class="delete-btn">Delete</button>
        `;
        }
        // make buttons visible
        document.querySelector(".action-column").style.display = "block";
        document.querySelector(".save-changes-btn").style.display = "block";
        document.querySelector(".add-schedule-btn").style.display = "block";
        document.querySelector(".cancel-changes-btn").style.display = "block";
        document.querySelector(".edit-btn").style.display = "none";
    });
}

function restoreTableToViewMode() {
    // Hide buttons
    document.querySelector(".action-column").style.display = "none";
    document.querySelector(".save-changes-btn").style.display = "none";
    document.querySelector(".add-schedule-btn").style.display = "none";
    document.querySelector(".cancel-changes-btn").style.display = "none";
    document.querySelector(".edit-btn").style.display = "flex";

    const rows = document.querySelectorAll(".schedule-table tbody tr");

    rows.forEach((row) => {
        const cells = row.querySelectorAll("td");
        if (cells.length === 4) {
            // Replace inputs with static text
            const daySelect = cells[0].querySelector(".day-select");
            const startTimeInput = cells[1].querySelector(".time-input");
            const endTimeInput = cells[2].querySelector(".time-input");
            const actionsCell = cells[3];

            const day = daySelect
                ? daySelect.value
                : cells[0].textContent.trim();
            const startTime = startTimeInput
                ? startTimeInput.value
                : cells[1].textContent.trim();
            const endTime = endTimeInput
                ? endTimeInput.value
                : cells[2].textContent.trim();

            cells[0].textContent = day;
            cells[1].textContent = startTime;
            cells[2].textContent = endTime;
            actionsCell.style.display = "none";
        }
    });
}

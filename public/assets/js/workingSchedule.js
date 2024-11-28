document.addEventListener("DOMContentLoaded", function() {
    console.log('Working Schedule JS loaded');
    console.log('ROOT path:', ROOT);

    const scheduleContainer = document.querySelector(".schedule-container");
    if (!scheduleContainer) {
        console.error("Schedule container not found");
        return;
    }

    // Event delegation for all buttons
    scheduleContainer.addEventListener("click", (event) => {
        if (event.target.matches(".add-schedule-btn")) {
            console.log("Add Schedule button clicked");
            addNewScheduleRow();
        } else if (event.target.matches(".delete-btn")) {
            console.log("Delete button clicked");
            handleDelete(event);
        } else if (event.target.matches(".save-changes-btn")) {
            console.log("Save Changes button clicked");
            saveSchedules(); // Changed from validateSchedule to be more explicit
        } else if (event.target.matches(".edit-btn")) {
            console.log("Edit button clicked");
            makeTableEditable();
        } else if (event.target.matches(".cancel-changes-btn")) {
            console.log("Cancel button clicked");
            restoreTableToViewMode();
        }
    });

    // Load initial schedules
    loadSchedules();
});

function showAlert(message, type = 'error') {
    const alertContainer = document.getElementById('alert-container');
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert-message ${type}`;
    alertDiv.innerHTML = `
        <span class="close-btn">&times;</span>
        <span class="message">${message}</span>
    `;

    // Add to container
    alertContainer.appendChild(alertDiv);

    // Trigger slide in
    setTimeout(() => alertDiv.classList.add('show'), 100);

    // Add close button handler
    const closeBtn = alertDiv.querySelector('.close-btn');
    closeBtn.onclick = () => {
        alertDiv.classList.remove('show');
        setTimeout(() => alertDiv.remove(), 500);
    };

    // Auto remove after 5 seconds
    setTimeout(() => {
        if (alertDiv.parentNode) {
            alertDiv.classList.remove('show');
            setTimeout(() => alertDiv.remove(), 500);
        }
    }, 5000);
}

function saveSchedules() {
    const rows = document.querySelectorAll(".schedule-table tbody tr");
    const schedules = [];

    console.log("Starting save process...");
    console.log("Found " + rows.length + " rows");

    rows.forEach((row, index) => {
        // Skip the "no schedules" message row
        if (row.querySelector(".no-schedules")) {
            return;
        }

        let days_of_week, startTime, endTime;

        // Check if this is a new row (has select) or existing row (has text)
        const daySelect = row.querySelector(".day-select");
        if (daySelect) {
            // New row
            days_of_week = daySelect.value;
            const timeInputs = row.querySelectorAll(".time-input");
            startTime = timeInputs[0].value;
            endTime = timeInputs[1].value;
        } else {
            // Existing row
            const cells = row.querySelectorAll("td");
            days_of_week = cells[0].textContent.trim().toLowerCase();
            startTime = cells[1].textContent.trim();
            endTime = cells[2].textContent.trim();
        }

        // Validate the data
        if (!days_of_week || !startTime || !endTime) {
            showAlert("Please fill in all fields for each schedule", "error");
            return;
        }

        if (startTime >= endTime) {
            showAlert("Start time must be earlier than end time", "error");
            return;
        }

        const schedule = {
            days_of_week: days_of_week,
            startTime: startTime,
            endTime: endTime
        };

        // Add scheduleID if it exists (for existing rows)
        if (row.dataset.scheduleId) {
            schedule.scheduleID = row.dataset.scheduleId;
        }

        console.log(`Valid schedule found:`, schedule);
        schedules.push(schedule);
    });

    if (schedules.length === 0) {
        console.warn("No schedules to save");
        showAlert("No schedules to save", "error");
        return;
    }

    console.log('Sending schedules to server:', { schedules });
    
    const url = `${ROOT}/public/WorkingSchedule/saveSchedule`;
    console.log('Attempting to save to:', url);
    console.log('Data being sent:', { schedules });

    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ schedules: schedules })
    })
    .then(response => {
        console.log('Server response status:', response.status);
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        console.log('Server response data:', data);
        if (data.success) {
            loadSchedules();
            restoreTableToViewMode();
            showAlert('Changes saved successfully!', 'success');
        } else {
            const errorMessage = data.messages ? data.messages.join("\n") : "Unknown error";
            console.error("Save failed:", errorMessage);
            showAlert(`Error saving changes: ${errorMessage}`);
        }
    })
    .catch(error => {
        console.error('Save error:', error);
        showAlert(`Error saving changes: ${error.message}`);
    });
}

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
            <select class="day-select" name="days_of_week" required>
                <option value="">Select Day</option>
                ${remainingDays
                    .map((day) => `<option value="${day.toLowerCase()}">${day.charAt(0).toUpperCase() + day.slice(1)}</option>`)
                    .join("")}
            </select>
        </td>
        <td><input type="time" class="time-input" value="09:00" required></td>
        <td><input type="time" class="time-input" value="17:00" required></td>
        <td class="action-column">
            <i class="fas fa-edit edit-btn" title="Edit Schedule"></i>
            <i class="fas fa-trash-alt delete-btn" title="Delete Schedule"></i>
        </td>
    `;

    tableBody.appendChild(newRow);
    console.log('New schedule row added');
}

function loadSchedules() {
    console.log('Loading schedules...');
    fetch(`${ROOT}/public/WorkingSchedule/getSchedule`)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(schedules => {
            console.log('Received schedules:', schedules);
            const tableBody = document.querySelector(".schedule-table tbody");
            tableBody.innerHTML = '';

            if (schedules && schedules.length > 0) {
                schedules.forEach(schedule => {
                    // Capitalize the first letter of the day
                    const capitalizedDay = schedule.days_of_week.charAt(0).toUpperCase() + 
                                         schedule.days_of_week.slice(1).toLowerCase();
                    
                    const row = document.createElement("tr");
                    row.dataset.scheduleId = schedule.scheduleID;
                    row.innerHTML = `
                        <td>${capitalizedDay}</td>
                        <td>${schedule.startTime}</td>
                        <td>${schedule.endTime}</td>
                        <td class="action-column" style="display: none">
                            <i class="far fa-edit update-btn" title="Edit Icon"></i>
                            <i class="far fa-trash-alt delete-btn" title="Delete Schedule"></i>
                        </td>
                    `;
                    tableBody.appendChild(row);
                });
            } else {
                tableBody.innerHTML = `
                    <tr>
                        <td colspan="4" class="no-schedules">No schedules found. Click 'Edit Schedule' to add new schedules.</td>
                    </tr>
                `;
            }
        })
        .catch(error => {
            console.error('Error loading schedules:', error);
            showAlert('Error loading schedules. Please try again.');
            const tableBody = document.querySelector(".schedule-table tbody");
            tableBody.innerHTML = `
                <tr>
                    <td colspan="4" class="no-schedules">No schedules found. Click 'Edit Schedule' to add new schedules.</td>
                </tr>
            `;
        });
}

function getRemainingDays() {
    const days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
    const existingDays = new Set(
        Array.from(document.querySelectorAll('.schedule-table tbody tr'))
            .map(row => {
                const dayCell = row.querySelector('td:first-child');
                return dayCell ? dayCell.textContent.toLowerCase() : null;
            })
            .filter(day => day !== null)
    );

    return days.filter(day => !existingDays.has(day));
}

function makeTableEditable() {
    const actionColumns = document.querySelectorAll(".action-column");
    actionColumns.forEach(col => col.style.display = "table-cell");
    
    document.querySelector(".add-schedule-btn").style.display = "block";
    document.querySelector(".save-changes-btn").style.display = "block";
    document.querySelector(".cancel-changes-btn").style.display = "block";
    document.querySelector(".edit-btn").style.display = "none";
}

function restoreTableToViewMode() {
    const actionColumns = document.querySelectorAll(".action-column");
    actionColumns.forEach(col => col.style.display = "none");
    
    document.querySelector(".add-schedule-btn").style.display = "none";
    document.querySelector(".save-changes-btn").style.display = "none";
    document.querySelector(".cancel-changes-btn").style.display = "none";
    document.querySelector(".edit-btn").style.display = "block";
    
    loadSchedules();
}

function handleDelete(event) {
    const row = event.target.closest('tr');
    const scheduleId = row.dataset.scheduleId;

    if (confirm("Are you sure you want to delete this schedule?")) {
        fetch(`${ROOT}/public/WorkingSchedule/deleteSchedule`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ scheduleId: scheduleId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                row.remove();
                showAlert('Schedule deleted successfully!', 'success');
            } else {
                showAlert('Error deleting schedule');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('Error deleting schedule');
        });
    }
}

document.getElementById('scheduleForm').addEventListener('submit', function(e) {
    e.preventDefault();
});

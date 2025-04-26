let isSaving = false;

document.addEventListener("DOMContentLoaded", function () {
  console.log("Working Schedule JS loaded");
  console.log("ROOT path:", ROOT);

  const scheduleContainer = document.querySelector(".schedule-container");
  if (!scheduleContainer) {
    console.error("Schedule container not found");
    return;
  }

  // Add event listener for the Add New Day button
  const addNewDayBtn = document.querySelector(".add-schedule-btn");
  if (addNewDayBtn) {
    addNewDayBtn.addEventListener("click", addNewScheduleRow);
  }

  // Function to get remaining available days...
  function getRemainingDays() {
    const existingDays = Array.from(
      document.querySelectorAll(".schedule-table tbody tr"),
    ).map((row) => {
      const daySelect = row.querySelector(".day-select");
      const dayCell = row.querySelector("td:first-child");
      return daySelect
        ? daySelect.value.toLowerCase()
        : dayCell.textContent.toLowerCase();
    });

    const allDays = [
      "monday",
      "tuesday",
      "wednesday",
      "thursday",
      "friday",
      "saturday",
      "sunday",
    ];
    return allDays.filter((day) => !existingDays.includes(day));
  }

  // Function to add new schedule row
  function addNewScheduleRow() {
    const remainingDays = getRemainingDays();

    if (remainingDays.length === 0) {
      showAlert("All days have already been selected.", "error");
      return;
    }

    const tableBody = document.querySelector(".schedule-table tbody");
    const newRow = document.createElement("tr");
    newRow.classList.add("new-schedule-row");

    newRow.innerHTML = `
            <td>
                <select class="day-select" name="day_of_week" required>
                    <option value="">Select Day</option>
                    ${remainingDays
                      .map(
                        (day) =>
                          `<option value="${day.toLowerCase()}">${day.charAt(0).toUpperCase() + day.slice(1)}</option>`,
                      )
                      .join("")}
                </select>
            </td>
            <td><input type="time" class="time-input" value="09:00" required></td>
            <td><input type="time" class="time-input" value="17:00" required></td>
            <td class="action-column">
                <div class="remove-row-btn">
                    <i class="fas fa-minus" title="Remove Row"></i>
                </div>
            </td>
        `;

    tableBody.appendChild(newRow);
  }

  // Event delegation for all buttons
  scheduleContainer.addEventListener("click", (event) => {
    // Find if click was on the button or the icon inside it
    const addSlotBtn = event.target.closest(".add-slot-btn");
    const removeRowBtn = event.target.closest(".remove-row-btn");
    const updateBtn = event.target.closest(".update-btn");

    if (addSlotBtn) {
      console.log("Add slot button clicked");
      const parentRow = addSlotBtn.closest("tr");
      addTimeSlot(parentRow);
    } else if (removeRowBtn) {
      console.log("Remove row button clicked");
      removeRowBtn.closest("tr").remove();
    } else if (event.target.matches(".delete-btn")) {
      console.log("Delete button clicked");
      handleDelete(event);
    } else if (updateBtn) {
      console.log("Update button clicked");
      const row = updateBtn.closest("tr");
      makeRowEditable(row);
    }
  });

  // Add event listeners for the main edit, save, and cancel buttons
  const editBtn = document.querySelector(".edit-btn");
  if (editBtn) {
    editBtn.addEventListener("click", function () {
      makeTableEditable();
      // Hide the edit button itself
      this.style.display = "none";
    });
  }

  // Load initial schedules
  loadSchedules();

  // Remove duplicate save button event listeners and add new one
  const saveBtn = document.querySelector(".save-changes-btn");
  if (saveBtn) {
    saveBtn.addEventListener("click", function (e) {
      e.preventDefault();
      console.log("Save button clicked"); // Debug log
      saveSchedules();
    });
  }

  // Add event delegation for time input changes
  document
    .querySelector(".schedule-table")
    .addEventListener("change", function (e) {
      if (e.target.classList.contains("time-input")) {
        validateTimeSlots(e.target.closest("tr"));
      }
    });
});

function showAlert(message, type = "info") {
  // Create container if it doesn't exist
  let container = document.querySelector(".alert-container");
  if (!container) {
    container = document.createElement("div");
    container.className = "alert-container";
    document.body.appendChild(container);
  }

  // Create alert element
  const alertDiv = document.createElement("div");
  alertDiv.className = `alert alert-${type}`;

  // Create icon based on alert type
  const icon = document.createElement("i");
  switch (type) {
    case "success":
      icon.className = "fas fa-check-circle";
      break;
    case "error":
      icon.className = "fas fa-exclamation-circle";
      break;
    default:
      icon.className = "fas fa-info-circle";
  }
  icon.style.marginRight = "10px";

  // Create message element
  const messageSpan = document.createElement("span");
  messageSpan.textContent = message;

  // Assemble alert
  alertDiv.appendChild(icon);
  alertDiv.appendChild(messageSpan);
  container.appendChild(alertDiv);

  // Remove the alert after animation completes
  alertDiv.addEventListener("animationend", (event) => {
    if (event.animationName === "slideOut") {
      alertDiv.remove();
      // Remove container if it's empty
      if (container.children.length === 0) {
        container.remove();
      }
    }
  });
}

function saveSchedules() {
  if (isSaving) return;

  const rows = document.querySelectorAll(".schedule-table tbody tr");
  const workerID = document.querySelector('input[name="workerID"]').value;
  const schedules = [];

  // Process each row
  for (const row of rows) {
    if (row.querySelector(".no-schedules")) continue;

    const dayCell = row.querySelector("td:first-child");
    const isNewDay = !!dayCell.querySelector(".day-select"); // Check if it's a new day
    const day = isNewDay
      ? dayCell.querySelector(".day-select").value.toLowerCase()
      : dayCell.textContent.trim().toLowerCase();

    if (isNewDay && !day) {
      showAlert("Please select a day", "error");
      return;
    }

    // Get start and end times
    const startTimeCell = row.querySelector("td:nth-child(2)");
    const endTimeCell = row.querySelector("td:nth-child(3)");

    const startTime = startTimeCell.querySelector("input")
      ? startTimeCell.querySelector("input").value
      : startTimeCell.textContent.trim();

    const endTime = endTimeCell.querySelector("input")
      ? endTimeCell.querySelector("input").value
      : endTimeCell.textContent.trim();

    if (!startTime || !endTime) {
      showAlert("Please fill in all time fields", "error");
      return;
    }

    const startMinutes = convertTimeToMinutes(startTime);
    const endMinutes = convertTimeToMinutes(endTime);

    // Validate time range
    if (startMinutes >= endMinutes) {
      showAlert(
        `Invalid time range: End time must be after start time`,
        "error",
      );
      return;
    }

    // Only check for overlaps if it's NOT a new day
    if (!isNewDay) {
      // Check for overlaps with other slots for the same day
      const otherSlots = Array.from(rows).filter(
        (r) =>
          r !== row &&
          r.querySelector("td:first-child").textContent.trim().toLowerCase() ===
            day &&
          !r.querySelector(".no-schedules"),
      );

      for (const otherSlot of otherSlots) {
        const otherStartCell = otherSlot.querySelector("td:nth-child(2)");
        const otherEndCell = otherSlot.querySelector("td:nth-child(3)");

        const otherStart = otherStartCell.querySelector("input")
          ? otherStartCell.querySelector("input").value
          : otherStartCell.textContent.trim();

        const otherEnd = otherEndCell.querySelector("input")
          ? otherEndCell.querySelector("input").value
          : otherEndCell.textContent.trim();

        const otherStartMinutes = convertTimeToMinutes(otherStart);
        const otherEndMinutes = convertTimeToMinutes(otherEnd);

        if (
          (startMinutes >= otherStartMinutes &&
            startMinutes < otherEndMinutes) ||
          (endMinutes > otherStartMinutes && endMinutes <= otherEndMinutes) ||
          (startMinutes <= otherStartMinutes && endMinutes >= otherEndMinutes)
        ) {
          showAlert(
            `Time overlap detected for ${day.charAt(0).toUpperCase() + day.slice(1)}:\n` +
              `${formatTime(startTime)} - ${formatTime(endTime)}\n` +
              `overlaps with\n` +
              `${formatTime(otherStart)} - ${formatTime(otherEnd)}`,
            "error",
          );
          return;
        }
      }
    }

    // Add to schedules array
    schedules.push({
      days_of_week: day,
      startTime: startTime,
      endTime: endTime,
      scheduleID: row.dataset.scheduleId || null,
      workerId: workerID,
    });
  }

  if (schedules.length === 0) {
    showAlert("No schedules to save", "error");
    return;
  }

  console.log("Sending data:", { schedules, workerId: workerID });
  isSaving = true;

  // Make the API call
  fetch(`${ROOT}/public/WorkingSchedule/saveSchedule`, {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
      "X-Requested-With": "XMLHttpRequest",
    },
    body: JSON.stringify({
      schedules: schedules,
      workerId: workerID,
    }),
  })
    .then((response) => {
      if (!response.ok) {
        return response.json().then((data) => {
          throw new Error(
            data.message || `HTTP error! Status: ${response.status}`,
          );
        });
      }
      return response.json();
    })
    .then((data) => {
      console.log("Server response:", data);

      if (data.success) {
        showAlert("Schedule saved successfully!", "success");
        loadSchedules();
        resetEditMode();
      } else {
        throw new Error(
          data.messages
            ? data.messages.join("\n")
            : data.message || "Failed to save schedule",
        );
      }
    })
    .catch((error) => {
      console.error("Save error:", error);
      showAlert(error.message || "Error saving schedule", "error");
    })
    .finally(() => {
      isSaving = false;
    });
}

function formatTime(timeStr) {
  const [hours, minutes] = timeStr.split(":");
  const hour = parseInt(hours, 10);
  const period = hour >= 12 ? "PM" : "AM";
  const displayHour = hour % 12 || 12;
  return `${displayHour}:${minutes} ${period}`;
}

function convertTimeToMinutes(timeStr) {
  const [hours, minutes] = timeStr.split(":").map((num) => parseInt(num, 10));
  return hours * 60 + minutes;
}

// Add this function to validate times when they're changed
function validateTimeInput(input) {
  const row = input.closest("tr");
  const day = row
    .querySelector("td:first-child")
    .textContent.trim()
    .toLowerCase();
  const allRowsForDay = Array.from(document.querySelectorAll("tr")).filter(
    (r) =>
      r.querySelector("td:first-child").textContent.trim().toLowerCase() ===
      day,
  );

  // Collect all times for this day
  const times = allRowsForDay.map((r) => ({
    startTime:
      r.querySelector("td:nth-child(2)").querySelector("input")?.value ||
      r.querySelector("td:nth-child(2)").textContent.trim(),
    endTime:
      r.querySelector("td:nth-child(3)").querySelector("input")?.value ||
      r.querySelector("td:nth-child(3)").textContent.trim(),
  }));

  // Sort and check for overlaps
  times.sort(
    (a, b) =>
      convertTimeToMinutes(a.startTime) - convertTimeToMinutes(b.startTime),
  );

  for (let i = 0; i < times.length - 1; i++) {
    if (
      convertTimeToMinutes(times[i].endTime) >
      convertTimeToMinutes(times[i + 1].startTime)
    ) {
      showAlert(`Time overlap detected for ${day}`, "error");
      return false;
    }
  }
  return true;
}

// Add event listeners for time inputs
document.addEventListener("DOMContentLoaded", function () {
  document
    .querySelector(".schedule-table")
    .addEventListener("change", function (e) {
      if (e.target.classList.contains("time-input")) {
        validateTimeInput(e.target);
      }
    });
});

function resetEditMode() {
  // Remove edit mode class from table
  document.querySelector(".schedule-table").classList.remove("table-edit-mode");

  // Hide action buttons
  document.querySelector(".add-schedule-btn").style.display = "none";
  document.querySelector(".save-changes-btn").style.display = "none";

  // Show edit schedule button again
  document.querySelector(".edit-btn").style.display = "block";

  // Reset any input fields back to text
  document.querySelectorAll(".time-input").forEach((input) => {
    const cell = input.parentElement;
    cell.textContent = input.value;
  });
}

// Add debug logging
function debugScheduleData(schedules) {
  console.log("Schedules to be saved:", schedules);
  schedules.forEach((schedule, index) => {
    console.log(`Schedule ${index + 1}:`, {
      day: schedule.days_of_week,
      start: schedule.startTime,
      end: schedule.endTime,
      id: schedule.scheduleID || "new",
    });
  });
}

function loadSchedules() {
  fetch(`${ROOT}/public/WorkingSchedule/getSchedule`)
    .then((response) => {
      if (!response.ok) {
        throw new Error(`HTTP error! Status: ${response.status}`);
      }
      return response.json();
    })
    .then((schedules) => {
      const tableBody = document.querySelector(".schedule-table tbody");
      tableBody.innerHTML = "";
      if (!schedules || schedules.length === 0) {
        const row = document.createElement("tr");
        row.innerHTML = `
                    <td colspan="4" class="no-schedules">No schedules found</td>
                `;
        tableBody.appendChild(row);
        return;
      }
      schedules.forEach((schedule) => {
        const row = document.createElement("tr");
        row.dataset.scheduleId = schedule.scheduleID;

        const capitalizedDay =
          schedule.day_of_week.charAt(0).toUpperCase() +
          schedule.day_of_week.slice(1);

        row.innerHTML = `
                    <td>${capitalizedDay}</td>
                    <td>${schedule.start_time}</td>
                    <td>${schedule.end_time}</td>
                    <td class="action-column">
                        <i class="fas fa-edit update-btn" title="Edit Schedule"></i>
                        <i class="fas fa-trash-alt delete-btn" title="Delete Schedule"></i>
                    </td>
                `;
        tableBody.appendChild(row);
      });
    })
    .catch((error) => {
      console.error("Error loading schedules:", error);
      showAlert("Error loading schedules: " + error.message, "error");
    });
}

function makeTableEditable() {
  // Add edit mode class to table
  document.querySelector(".schedule-table").classList.add("table-edit-mode");

  // Show action buttons
  document.querySelector(".add-schedule-btn").style.display = "block";
  document.querySelector(".save-changes-btn").style.display = "block";

  // Hide edit schedule button
  document.querySelector(".edit-btn").style.display = "none";
}

// Add cancel function to remove edit mode
function cancelEdit() {
  // Remove edit mode class from table
  document.querySelector(".schedule-table").classList.remove("table-edit-mode");

  // Hide action buttons
  document.querySelector(".add-schedule-btn").style.display = "none";
  document.querySelector(".save-changes-btn").style.display = "none";

  // Show edit schedule button again
  document.querySelector(".edit-btn").style.display = "block";

  // Reload the original schedules
  loadSchedules();
}

// Add event listener for cancel button if it exists
const cancelButton = document.querySelector(".cancel-changes-btn");
if (cancelButton) {
  cancelButton.addEventListener("click", cancelEdit);
}

function handleDelete(event) {
  const row = event.target.closest("tr");
  const scheduleId = row.dataset.scheduleId;

  if (confirm("Are you sure you want to delete this schedule?")) {
    fetch(`${ROOT}/public/WorkingSchedule/deleteSchedule`, {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({ scheduleId: scheduleId }),
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          row.remove();
          showAlert("Schedule deleted successfully!", "success");
        } else {
          showAlert(
            "Error deleting schedule: " + (data.error || "Unknown error"),
          );
        }
      })
      .catch((error) => {
        console.error("Error:", error);
        showAlert("Error deleting schedule: " + error.message);
      });
  }
}

document
  .getElementById("scheduleForm")
  .addEventListener("submit", function (e) {
    e.preventDefault();
  });

function addTimeSlot(clickedRow) {
  const day = clickedRow.querySelector("td:first-child").textContent.trim();

  // Create new row
  const newRow = document.createElement("tr");
  newRow.innerHTML = `
        <td>${day}</td>
        <td><input type="time" class="time-input" value="09:00" required></td>
        <td><input type="time" class="time-input" value="17:00" required></td>
        <td class="action-column">
            <div class="remove-row-btn">
                <i class="fas fa-minus" title="Remove Time Slot"></i>
            </div>
        </td>
    `;

  // Insert new row directly after the clicked row
  clickedRow.parentNode.insertBefore(newRow, clickedRow.nextSibling);
}

function formatTimeForDisplay(timeStr) {
  const [hours, minutes] = timeStr.split(":");
  const hour = parseInt(hours, 10);
  const period = hour >= 12 ? "PM" : "AM";
  const displayHour = hour % 12 || 12;
  return `${displayHour}:${minutes} ${period}`;
}

// Update button click handler
document.addEventListener("click", function (e) {
  const updateBtn = e.target.closest(".update-btn");
  if (updateBtn) {
    const row = updateBtn.closest("tr");
    makeRowEditable(row);

    // Show save changes button
    const saveBtn = document.querySelector(".save-changes-btn");
    if (saveBtn) {
      saveBtn.style.display = "block";
    }
  }
});

function makeRowEditable(row) {
  // Only proceed if row isn't already being edited
  if (!row.classList.contains("editing")) {
    // Get the cells
    const dayCell = row.querySelector("td:first-child");
    const startTimeCell = row.querySelector("td:nth-child(2)");
    const endTimeCell = row.querySelector("td:nth-child(3)");

    // Store original values
    row.dataset.originalDay = dayCell.textContent.trim();
    row.dataset.originalStart = startTimeCell.textContent.trim();
    row.dataset.originalEnd = endTimeCell.textContent.trim();

    // Create day select with the current day selected
    const currentDay = dayCell.textContent.trim().toLowerCase();
    const daySelectHTML = `
      <select class="day-select" name="day_of_week" required>
        <option value="monday" ${currentDay === "monday" ? "selected" : ""}>Monday</option>
        <option value="tuesday" ${currentDay === "tuesday" ? "selected" : ""}>Tuesday</option>
        <option value="wednesday" ${currentDay === "wednesday" ? "selected" : ""}>Wednesday</option>
        <option value="thursday" ${currentDay === "thursday" ? "selected" : ""}>Thursday</option>
        <option value="friday" ${currentDay === "friday" ? "selected" : ""}>Friday</option>
        <option value="saturday" ${currentDay === "saturday" ? "selected" : ""}>Saturday</option>
        <option value="sunday" ${currentDay === "sunday" ? "selected" : ""}>Sunday</option>
      </select>
    `;
    dayCell.innerHTML = daySelectHTML;

    // Get current values and convert to 24-hour format
    const startTime = convertTo24Hour(startTimeCell.textContent.trim());
    const endTime = convertTo24Hour(endTimeCell.textContent.trim());

    // Convert to input fields
    startTimeCell.innerHTML = `<input type="time" class="time-input" value="${startTime}" required>`;
    endTimeCell.innerHTML = `<input type="time" class="time-input" value="${endTime}" required>`;

    // Add editing class
    row.classList.add("editing");

    // Show save changes button
    document.querySelector(".save-changes-btn").style.display = "block";
  }
}

function resetRowToNormal(row) {
  if (row.classList.contains("editing")) {
    const dayCell = row.querySelector("td:first-child");
    const startTimeCell = row.querySelector("td:nth-child(2)");
    const endTimeCell = row.querySelector("td:nth-child(3)");

    // Restore original values
    dayCell.textContent = row.dataset.originalDay;
    startTimeCell.textContent = row.dataset.originalStart;
    endTimeCell.textContent = row.dataset.originalEnd;

    // Remove editing class
    row.classList.remove("editing");
  }
}

function convertTo24Hour(timeStr) {
  // If already in 24-hour format, return as is
  if (timeStr.match(/^\d{2}:\d{2}$/)) {
    return timeStr;
  }

  const [time, modifier] = timeStr.split(" ");
  let [hours, minutes] = time.split(":");

  hours = parseInt(hours, 10);
  if (modifier === "PM" && hours < 12) hours += 12;
  if (modifier === "AM" && hours === 12) hours = 0;

  return `${hours.toString().padStart(2, "0")}:${minutes}`;
}

// Add this new function for real-time validation
function validateTimeSlots(changedRow) {
  const day = changedRow
    .querySelector("td:first-child")
    .textContent.trim()
    .toLowerCase();
  const rows = Array.from(
    document.querySelectorAll(".schedule-table tbody tr"),
  ).filter(
    (row) =>
      row.querySelector("td:first-child").textContent.trim().toLowerCase() ===
      day,
  );

  const timeSlots = rows.map((row) => {
    const startInput =
      row.querySelector("td:nth-child(2) input") ||
      row.querySelector("td:nth-child(2)");
    const endInput =
      row.querySelector("td:nth-child(3) input") ||
      row.querySelector("td:nth-child(3)");

    return {
      row: row,
      startTime: startInput.value || startInput.textContent.trim(),
      endTime: endInput.value || endInput.textContent.trim(),
      startMinutes: convertTimeToMinutes(
        startInput.value || startInput.textContent.trim(),
      ),
      endMinutes: convertTimeToMinutes(
        endInput.value || endInput.textContent.trim(),
      ),
    };
  });

  // Sort slots by start time
  timeSlots.sort((a, b) => a.startMinutes - b.startMinutes);

  // Check for overlaps
  for (let i = 0; i < timeSlots.length; i++) {
    const currentSlot = timeSlots[i];

    // Validate start time is before end time
    if (currentSlot.startMinutes >= currentSlot.endMinutes) {
      showAlert(
        `Invalid time range: End time must be after start time`,
        "error",
      );
      return false;
    }

    // Check overlap with next slot
    if (i < timeSlots.length - 1) {
      const nextSlot = timeSlots[i + 1];
      if (currentSlot.endMinutes > nextSlot.startMinutes) {
        showAlert(
          `Time overlap detected: ${formatTimeForDisplay(currentSlot.startTime)}-${formatTimeForDisplay(currentSlot.endTime)} ` +
            `overlaps with ${formatTimeForDisplay(nextSlot.startTime)}-${formatTimeForDisplay(nextSlot.endTime)}`,
          "error",
        );
        return false;
      }
    }
  }
  return true;
}

// Add this function to check for overlaps immediately
function checkForOverlaps(changedRow) {
  const day = changedRow
    .querySelector("td:first-child")
    .textContent.trim()
    .toLowerCase();
  const allRowsForDay = Array.from(
    document.querySelectorAll(".schedule-table tbody tr"),
  ).filter((row) => {
    const rowDay = row
      .querySelector("td:first-child")
      .textContent.trim()
      .toLowerCase();
    return rowDay === day && !row.querySelector(".no-schedules");
  });

  // Get all time slots for this day
  const timeSlots = allRowsForDay.map((row) => {
    const startTimeCell = row.querySelector("td:nth-child(2)");
    const endTimeCell = row.querySelector("td:nth-child(3)");

    const startTime = startTimeCell.querySelector("input")
      ? startTimeCell.querySelector("input").value
      : startTimeCell.textContent.trim();

    const endTime = endTimeCell.querySelector("input")
      ? endTimeCell.querySelector("input").value
      : endTimeCell.textContent.trim();

    return {
      row: row,
      startTime,
      endTime,
      startMinutes: convertTimeToMinutes(startTime),
      endMinutes: convertTimeToMinutes(endTime),
    };
  });

  // Sort by start time
  timeSlots.sort((a, b) => a.startMinutes - b.startMinutes);

  // Check each slot
  for (let i = 0; i < timeSlots.length; i++) {
    const currentSlot = timeSlots[i];

    // Check if end time is before start time
    if (currentSlot.startMinutes >= currentSlot.endMinutes) {
      showAlert(
        `Invalid time range: End time must be after start time`,
        "error",
      );
      return false;
    }

    // Check for overlap with next slot
    if (i < timeSlots.length - 1) {
      const nextSlot = timeSlots[i + 1];
      if (currentSlot.endMinutes > nextSlot.startMinutes) {
        showAlert(
          `Time overlap detected for ${day.charAt(0).toUpperCase() + day.slice(1)}:\n` +
            `${formatTime(currentSlot.startTime)} - ${formatTime(currentSlot.endTime)}\n` +
            `overlaps with\n` +
            `${formatTime(nextSlot.startTime)} - ${formatTime(nextSlot.endTime)}`,
          "error",
        );
        return false;
      }
    }
  }
  return true;
}

// Add event listeners for immediate validation
document.addEventListener("DOMContentLoaded", function () {
  const scheduleTable = document.querySelector(".schedule-table");

  if (scheduleTable) {
    scheduleTable.addEventListener("input", function (e) {
      if (e.target.classList.contains("time-input")) {
        const row = e.target.closest("tr");
        checkForOverlaps(row);
      }
    });

    // Also check on change event for browsers that don't trigger input events for time inputs
    scheduleTable.addEventListener("change", function (e) {
      if (e.target.classList.contains("time-input")) {
        const row = e.target.closest("tr");
        checkForOverlaps(row);
      }
    });
  }
});

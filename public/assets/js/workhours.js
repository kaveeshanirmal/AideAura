document.addEventListener("DOMContentLoaded", function () {
  // Function to get exact total hours including minutes
  function getExactTotalHours() {
    if (window.globalValues && window.globalValues.totalHours) {
      const [hours, minutes] = window.globalValues.totalHours
        .split(":")
        .map(Number);
      return hours + minutes / 60;
    }
    return 2;
  }

  // Initialize variables
  let TOTAL_HOURS = getExactTotalHours();
  const SHIFT_BREAK = 2.5;
  let remainingHours = TOTAL_HOURS;

  // Counter elements
  const minusBtn = document.querySelector(".minus-btn");
  const plusBtn = document.querySelector(".plus-btn");
  const shiftCount = document.querySelector(".shift-count");
  const secondShiftRow = document.querySelector(".second-shift");
  const firstDurationSelect = document.getElementById("firstShiftDuration");
  const firstStartTime = document.getElementById("firstStartTime");
  const firstEndTime = document.getElementById("firstEndTime");

  // Function to format hours and minutes
  function formatDurationDisplay(hours) {
    const wholeHours = Math.floor(hours);
    const minutes = Math.round((hours - wholeHours) * 60);
    let displayText = wholeHours + (wholeHours === 1 ? " hour" : " hours");
    if (minutes > 0) {
      displayText += " " + minutes + " minutes";
    }
    return displayText;
  }

  // Function to handle shift count changes
  function handleShiftCountChange(count) {
    shiftCount.textContent = count.toString();

    if (count === 1) {
      // Single shift mode
      if (firstDurationSelect) {
        firstDurationSelect.style.display = "none";

        // Create or update duration display as span
        let durationDisplay = document.getElementById(
          "firstShiftDurationDisplay",
        );
        if (!durationDisplay) {
          durationDisplay = document.createElement("span");
          durationDisplay.id = "firstShiftDurationDisplay";
          durationDisplay.className = "end-time";
          firstDurationSelect.parentNode.insertBefore(
            durationDisplay,
            firstDurationSelect,
          );
        }
        durationDisplay.style.display = "";
        durationDisplay.textContent = formatDurationDisplay(TOTAL_HOURS);
        firstDurationSelect.value = TOTAL_HOURS;
      }

      // Hide second shift
      if (secondShiftRow) {
        secondShiftRow.style.display = "none";
      }

      // Set button states for single shift mode
      plusBtn.disabled = false;
      minusBtn.disabled = false; // Changed: Don't disable minus button
    } else if (count === 2) {
      // Two shifts mode
      if (firstDurationSelect) {
        // Show duration select and hide display input
        const durationDisplay = document.getElementById(
          "firstShiftDurationDisplay",
        );
        if (durationDisplay) {
          durationDisplay.style.display = "none";
        }
        firstDurationSelect.style.display = "";
        firstDurationSelect.value = "";
        updateFirstShiftDurationOptions();
      }

      // Hide second shift until duration is selected
      if (secondShiftRow) {
        secondShiftRow.style.display = "none";
      }

      // Set button states for two shifts mode
      plusBtn.disabled = true;
      minusBtn.disabled = false;
    }
  }

  // Minus button handler
  minusBtn.addEventListener("click", function () {
    const currentCount = parseInt(shiftCount.textContent);
    if (currentCount > 1) {
      // Changed: Allow decreasing if count is greater than 1
      // Reset fields when switching to single shift
      if (firstStartTime) firstStartTime.value = "";
      if (firstEndTime) firstEndTime.textContent = "--:--";

      handleShiftCountChange(1);
    }
  });

  // Plus button handler
  plusBtn.addEventListener("click", function () {
    const currentCount = parseInt(shiftCount.textContent);
    if (currentCount === 1) {
      // Reset fields when switching to two shifts
      if (firstStartTime) firstStartTime.value = "";
      if (firstEndTime) firstEndTime.textContent = "--:--";
      if (firstDurationSelect) firstDurationSelect.value = "";

      handleShiftCountChange(2);
    }
  });

  // Function to handle first shift duration change
  function handleFirstShiftDurationChange(event) {
    const selectedDuration = parseFloat(event.target.value);
    remainingHours = TOTAL_HOURS - selectedDuration;

    // Update second shift duration display
    const secondShiftDuration = document.getElementById("secondShiftDuration");
    if (secondShiftDuration && remainingHours >= 0.5) {
      secondShiftDuration.value = formatDurationDisplay(remainingHours);
    }

    // Only update end time and show second shift if start time is selected
    if (firstStartTime.value) {
      const endTime = calculateEndTime(firstStartTime.value, selectedDuration);
      if (firstEndTime) {
        firstEndTime.textContent = endTime;
        // Now that we have end time, populate second shift options
        populateSecondShiftStartTimes(endTime);
        secondShiftRow.style.display = "flex";
      }
    }
  }

  // Function to populate second shift start times
  function populateSecondShiftStartTimes(firstEndTime) {
    const secondStartTime = document.getElementById("secondStartTime");
    if (!secondStartTime) return;

    // Clear existing options
    secondStartTime.innerHTML =
      '<option value="" disabled selected>Select Time</option>';

    // Parse first shift end time
    const [timeStr, period] = firstEndTime.split(" ");
    let [hours, minutes] = timeStr.split(":").map(Number);

    // Convert to 24-hour format for calculations
    if (period === "PM" && hours !== 12) hours += 12;
    if (period === "AM" && hours === 12) hours = 0;

    // Add SHIFT_BREAK (2.5 hours)
    hours += Math.floor(SHIFT_BREAK);
    minutes += (SHIFT_BREAK % 1) * 60;

    // Handle minute overflow
    if (minutes >= 60) {
      hours += Math.floor(minutes / 60);
      minutes = minutes % 60;
    }

    // Calculate end limit (7 PM = 19:00)
    const endHour = 19;

    // Generate time slots every 30 minutes until 7 PM
    while (hours < endHour || (hours === endHour && minutes === 0)) {
      const displayHours = hours > 12 ? hours - 12 : hours === 0 ? 12 : hours;
      const displayPeriod = hours >= 12 ? "PM" : "AM";
      const formattedMinutes = minutes.toString().padStart(2, "0");
      const timeString = `${displayHours}:${formattedMinutes}`;

      secondStartTime.add(
        new Option(`${timeString} ${displayPeriod}`, timeString),
      );

      // Increment by 30 minutes
      minutes += 30;
      if (minutes >= 60) {
        hours += 1;
        minutes -= 60;
      }
    }

    console.log(
      "Populated second shift times from",
      firstEndTime,
      "+",
      SHIFT_BREAK,
      "hours until 7 PM",
    );
  }

  // Handle first shift start time change
  function handleFirstStartTimeChange() {
    const currentCount = parseInt(shiftCount.textContent);
    const duration =
      currentCount === 1 ? TOTAL_HOURS : parseFloat(firstDurationSelect.value);

    if (firstStartTime.value && duration) {
      const endTime = calculateEndTime(firstStartTime.value, duration);
      if (firstEndTime) {
        firstEndTime.textContent = endTime;

        // Only show second shift in two-shift mode with valid duration
        if (currentCount === 2 && firstDurationSelect.value) {
          populateSecondShiftStartTimes(endTime);
          secondShiftRow.style.display = "flex";
        }
      }
    }
  }

  // Initialize event listeners
  if (firstStartTime) {
    firstStartTime.addEventListener("change", handleFirstStartTimeChange);
  }

  if (firstDurationSelect) {
    firstDurationSelect.addEventListener(
      "change",
      handleFirstShiftDurationChange,
    );
  }

  // Update when total hours change
  window.addEventListener("totalHoursUpdated", function () {
    TOTAL_HOURS = getExactTotalHours();
    remainingHours = TOTAL_HOURS;
    const currentCount = parseInt(shiftCount.textContent);
    handleShiftCountChange(currentCount);
  });

  // Initial setup - start with single shift mode
  handleShiftCountChange(1);

  // Add event listener for second shift start time
  const secondStartTime = document.getElementById("secondStartTime");
  const secondEndTime = document.getElementById("secondEndTime");
  if (secondStartTime && secondEndTime) {
    secondStartTime.addEventListener("change", function () {
      if (this.value && remainingHours) {
        const endTime = calculateEndTime(this.value, remainingHours);
        secondEndTime.textContent = endTime;
      }
    });
  }
});

let step = 1;
const headerTitle = document.getElementById("header-title");
const backArrow = document.getElementById("back-arrow");
const progressBar = document.getElementById("progress-bar");

// Update the header based on the step
function updateHeader() {
    if (step === 1) {
        headerTitle.textContent = "Select a Service";
        backArrow.style.visibility = "hidden"; // Hide the arrow on the first step
        progressBar.style.width = "33%"; // Update the progress bar to 1/3
    } else if (step === 2) {
        headerTitle.textContent = "Select the Working Hours & Starting Date";
        backArrow.style.visibility = "visible"; // Show the arrow
        progressBar.style.width = "66%"; // Update the progress bar to 2/3
    } else if (step === 3) {
        headerTitle.textContent = "Booking Summary";
        backArrow.style.visibility = "visible";
        progressBar.style.width = "100%"; // Complete the progress bar
    }
}

// Go to the previous step when the arrow is clicked
backArrow.addEventListener("click", function () {
    if (step > 1) {
        step--;
        updateHeader();
    }
});

// Initialize the header state
updateHeader();

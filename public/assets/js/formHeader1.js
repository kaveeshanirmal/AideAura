document.addEventListener("DOMContentLoaded", function () {
  const nextButton = document.getElementById("next-button");
  const backArrow = document.getElementById("back-arrow");
  const headerTitle = document.getElementById("header-title");
  const progressBar = document.getElementById("progress-bar");
  let step = 1;

  // Get content elements
  const step1Content = document.getElementById("step1-content");
  const step2Content = document.getElementById("step2-content");
  const step3Content = document.getElementById("step3-content");

  // Next button click handler
  nextButton.addEventListener("click", function (e) {
    // Prevent default action
    e.preventDefault();
    e.stopPropagation();

    if (step === 1) {
      // Get the home style food card and its button
      const homeStyleCard = document.querySelector(
        '.service-card[data-service="home-style-food"]',
      );
      const addButton = homeStyleCard.querySelector(".add-button");

      // Check if service is not selected (button still shows '+')
      if (addButton.textContent === "+") {
        alert("Home Style Food service is mandatory!");
        return;
      }
    }

    // Only increment step if validation passed
    if (step < 3) {
      step++;
      updateUI();
    }
  });

  // Back arrow click handler
  backArrow.addEventListener("click", function () {
    if (step > 1) {
      step--;
      updateUI();
    }
  });

  function updateUI() {
    // Hide all content first
    if (step1Content) step1Content.style.display = "none";
    if (step2Content) step2Content.style.display = "none";
    if (step3Content) step3Content.style.display = "none";

    // Show current step
    switch (step) {
      case 1:
        if (step1Content) step1Content.style.display = "block";
        headerTitle.textContent = "Select a Service";
        backArrow.style.visibility = "hidden";
        progressBar.style.width = "33%";
        nextButton.textContent = "Next";
        break;
      case 2:
        if (step2Content) step2Content.style.display = "block";
        headerTitle.textContent = "Select the Working Hours & Starting Date";
        backArrow.style.visibility = "visible";
        progressBar.style.width = "66%";
        nextButton.textContent = "Next";
        break;
      case 3:
        if (step3Content) step3Content.style.display = "block";
        headerTitle.textContent = "Booking Summary";
        backArrow.style.visibility = "visible";
        progressBar.style.width = "100%";
        nextButton.textContent = "Confirm";
        break;
    }
  }

  // Initialize UI
  updateUI();
});

document.addEventListener('DOMContentLoaded', function() {
    // Toggle password visibility
    document.querySelectorAll(".toggle-password").forEach((item) => {
        item.addEventListener("click", function () {
            const passwordField = this.previousElementSibling;
            const type =
                passwordField.getAttribute("type") === "password"
                    ? "text"
                    : "password";
            passwordField.setAttribute("type", type);
            this.querySelector("i").classList.toggle("fa-eye");
            this.querySelector("i").classList.toggle("fa-eye-slash");
        });
    });

    // Password validation
    function validatepassword() {
        const password = document.getElementById("password").value;
        const confirmPassword = document.getElementById("confirm-password").value;
        if (password !== confirmPassword) {
            alert("Passwords do not match");
        }
    }

    // Role buttons (Worker or Customer)
    const workerBtn = document.querySelector(".worker-btn");
    const customerBtn = document.querySelector(".customer-btn");

    workerBtn.addEventListener("click", () => {
        renderForm("worker");
    });

    customerBtn.addEventListener("click", () => {
        renderForm("customer");
    });

    // Render the form dynamically based on role
    function getForm(role, selectedServices = "") {
        const serviceInput = selectedServices
            ? `<input type="hidden" name="serviceType" value="${selectedServices}">`
            : "";

        const form = `
        <div class="signup-form">
            <h2 class="greeting">Get Started Now</h2>
            <form id="signup-form">
                <input type="hidden" id="selected-role" name="role" value="${role}">
                ${serviceInput} <!-- Conditionally include serviceType input -->
                <div class="input-group">
                    <label for="firstName">First Name</label>
                    <input type="text" name="firstName" placeholder="Enter your first name" required>
                </div>
                <div class="input-group">
                    <label for="lastName">Last Name</label>
                    <input type="text" name="lastName" placeholder="Enter your last name" required>
                </div>
                <div class="input-group">
                    <label for="username">Username</label>
                    <input type="text" name="username" placeholder="Enter your username" required>
                </div>
                <div class="input-group">
                    <label for="address">Address</label>
                    <input type="text" name="address" placeholder="Enter your address" required>
                </div>
                <div class="input-group">
                    <label for="phone">Phone Number</label>
                    <input type="tel" name="phone" placeholder="07x xxxx xxx" required>
                </div>
                <div class="input-group">
                    <label for="email">Email Address</label>
                    <input type="email" name="email" placeholder="Enter your email address" required>
                </div>
                <div class="input-group">
                    <div class="label-wrapper">
                        <label for="password">Password</label>
                    </div>
                    <div class="password-wrapper">
                        <input type="password" name="password" placeholder="Enter your password" required>
                        <span class="toggle-password"><i class="fas fa-eye"></i></span>
                    </div>
                </div>
                <div class="input-group">
                    <div class="label-wrapper">
                        <label for="confirm-password">Confirm Password</label>
                    </div>
                    <div class="password-wrapper">
                        <input type="password" name="confirm-password" placeholder="Confirm your password" required>
                        <span class="toggle-password"><i class="fas fa-eye"></i></span>
                    </div>
                </div>
                <div class="checkbox-group">
                    <input type="checkbox" name="terms" required>
                    <label for="terms">Accept all <a href="#">Terms of Use & Privacy Policy</a>.</label>
                </div>
                <button type="submit" class="signup-button">Sign up</button>
            </form>
            <div class="signin-link">
                Have an account? <a href="login">Sign in</a>
            </div>
        </div>`;

        return form;
    }

    // Add event listener for form submission
    document.addEventListener('submit', function(event) {
        const form = document.getElementById('signup-form');
        if (form) {
            form.addEventListener('submit', function(event) {
                event.preventDefault();

                // Create an object to hold form data
                const formData = {
                    role: document.querySelector('input[name="role"]').value,
                    firstName: document.querySelector('input[name="firstName"]').value,
                    lastName: document.querySelector('input[name="lastName"]').value,
                    username: document.querySelector('input[name="username"]').value,
                    address: document.querySelector('input[name="address"]').value,
                    phone: document.querySelector('input[name="phone"]').value,
                    email: document.querySelector('input[name="email"]').value,
                    password: document.querySelector('input[name="password"]').value,
                    confirmPassword: document.querySelector('input[name="confirm-password"]').value,
                    terms: document.querySelector('input[name="terms"]').checked,
                };

                // Log the form data to the console
                console.log("Form data being submitted:", formData);

                // Optionally submit the form after logging
                // event.target.submit(); // Uncomment this to allow submission after logging
            });
        }
    });

    // Render the form based on role (customer or worker)
    function renderForm(role) {
        if (role === "customer") {
            const formContainer = document.querySelector(".signup-form-container");
            formContainer.innerHTML = getForm("customer");
        } else {
            const container = document.querySelector(".signup-form-container");
            container.innerHTML = getServiceTypes();

            const imageLabels = document.querySelectorAll(".image-label");

            // Set up the click listener for the image labels to toggle selection
            imageLabels.forEach((label) => {
                label.addEventListener("click", function () {
                    const isSelected =
                        this.getAttribute("data-selected") === "true";
                    this.setAttribute("data-selected", !isSelected);
                    this.querySelector(".service-btn").classList.toggle("selected");
                });
            });

            const nextBtn = document.getElementById("next-btn");
            nextBtn.addEventListener("click", () => {
                // Gather all selected services inside the button click event
                const selectedServices = [];
                imageLabels.forEach((label) => {
                    if (label.getAttribute("data-selected") === "true") {
                        const serviceName =
                            label.querySelector(".label-text").innerText;
                        selectedServices.push(serviceName);
                    }
                });

                // Create a comma-separated string of selected services
                const selectedServicesString = selectedServices.join(",");
                const container = document.querySelector(".signup-form-container");
                container.innerHTML = getForm("worker", selectedServicesString);
            });
        }
    }

    // Get the service selection form
    function getServiceTypes() {
        const serviceTypes = `
        <h2>I Offer</h2>
        <div class="signup-form">
            <h3>Select one or more</h3>
            <p>You can always change this later</p>
            </br>
            <div class="signup-form-btn">
                <div class="image-label" data-selected="false" id="service1">
                    <img src="assets/images/service_cook.png" alt="Service 1" class="service-btn">
                    <span class="label-text">Cook</span>
                </div>
                <div class="image-label" data-selected="false" id="service2">
                    <img src="assets/images/service_cook24.png" alt="Service 2" class="service-btn">
                    <span class="label-text">Cook 24-hour Live in</span>
                </div>
                <div class="image-label" data-selected="false" id="service3">
                    <img src="assets/images/service_maid.png" alt="Service 3" class="service-btn">
                    <span class="label-text">Maid</span>
                </div>
                <div class="image-label" data-selected="false" id="service4">
                    <img src="assets/images/service_nanny.png" alt="Service 4" class="service-btn">
                    <span class="label-text">Nanny</span>
                </div>
                <div class="image-label" data-selected="false" id="service5">
                    <img src="assets/images/service_allrounder.png" alt="Service 4" class="service-btn">
                    <span class="label-text">All rounder</span>
                </div>
            </div>
            <button class="signup-button" id="next-btn">Next</button>
        </div>
    `;
        return serviceTypes;
    }
});

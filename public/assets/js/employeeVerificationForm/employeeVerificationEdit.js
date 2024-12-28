// Dummy initial data for the form
const initialFormData = {
    fullName: "John Doe",
    userName: "johndoe123",
    email: "john.doe@example.com",
    telephone: "1234567890",
    gender: "male",
    hometown: "New York",
    age: "26-35",
    service: "cleaning",
    experience: "intermediate",
    description:
        "Experienced cleaning professional with 5 years of experience.",
    workingWeekdays: "7-9",
    workingWeekends: "4-6",
    notes: "Flexible and reliable service provider.",
};

// Function to populate form with initial data
function populateForm(data) {
    // Basic details
    document.getElementById("fullName").value = data.fullName;
    document.getElementById("fullName").disabled = true;
    document.getElementById("userName").value = data.userName;
    document.getElementById("userName").disabled = true;
    document.getElementById("email").value = data.email;
    document.getElementById("email").disabled = true;
    document.getElementById("telephone").value = data.telephone;
    document.getElementById("telephone").disabled = true;

    // Gender
    document.querySelector(
        `input[name="gender"][value="${data.gender}"]`
    ).checked = true;
    const genderInputs = document.querySelectorAll('input[name="gender"]');
    genderInputs.forEach((input) => (input.disabled = true));

    // Additional details
    document.getElementById("hometown").value = data.hometown;
    document.getElementById("hometown").disabled = true;
    document.getElementById("age").value = data.age;
    document.getElementById("age").disabled = true;
    document.getElementById("service").value = data.service;
    document.getElementById("service").disabled = true;
    document.getElementById("experience").value = data.experience;
    document.getElementById("experience").disabled = true;
    document.getElementById("description").value = data.description;
    document.getElementById("description").disabled = true;

    // Work preferences
    document.getElementById("workingWeekdays").value = data.workingWeekdays;
    document.getElementById("workingWeekdays").disabled = true;
    document.getElementById("workingWeekends").value = data.workingWeekends;
    document.getElementById("workingWeekends").disabled = true;
    document.getElementById("notes").value = data.notes;
    document.getElementById("notes").disabled = true;

    // Hide submit button initially
    document.getElementById("submit").style.display = "none";

    // Create edit button
    const editButton = document.createElement("button");
    editButton.textContent = "Edit";
    editButton.classList.add("edit_button");
    editButton.addEventListener("click", enableEditMode);
    document.querySelector(".user_buttons").appendChild(editButton);
}

// Function to enable edit mode
function enableEditMode() {
    // Enable all form fields
    const inputs = document.querySelectorAll("input, select, textarea");
    inputs.forEach((input) => {
        input.disabled = false;
    });

    // Show submit button
    document.getElementById("submit").style.display = "block";

    // Remove edit button
    const editButton = document.querySelector(".edit_button");
    if (editButton) editButton.remove();
}

// Function to handle form submission
function handleSubmit(event) {
    event.preventDefault();

    // Perform validation
    const form = event.target.closest("form");
    if (!form.checkValidity()) {
        alert("Please fill out all required fields correctly.");
        return;
    }

    // Collect form data
    const formData = {
        fullName: document.getElementById("fullName").value,
        userName: document.getElementById("userName").value,
        email: document.getElementById("email").value,
        telephone: document.getElementById("telephone").value,
        gender: document.querySelector('input[name="gender"]:checked').value,
        hometown: document.getElementById("hometown").value,
        age: document.getElementById("age").value,
        service: document.getElementById("service").value,
        experience: document.getElementById("experience").value,
        description: document.getElementById("description").value,
        workingWeekdays: document.getElementById("workingWeekdays").value,
        workingWeekends: document.getElementById("workingWeekends").value,
        notes: document.getElementById("notes").value,
    };

    // Here you would typically send data to backend
    console.log("Submitted Data:", formData);
    alert("Form submitted successfully!");

    // Disable fields after submission
    const inputs = document.querySelectorAll("input, select, textarea");
    inputs.forEach((input) => {
        input.disabled = true;
    });

    // Hide submit button
    document.getElementById("submit").style.display = "none";

    // Recreate edit button
    const editButton = document.createElement("button");
    editButton.textContent = "Edit";
    editButton.classList.add("edit_button");
    editButton.addEventListener("click", enableEditMode);
    document.querySelector(".user_buttons").appendChild(editButton);
}

// Initialize form when page loads
document.addEventListener("DOMContentLoaded", () => {
    populateForm(initialFormData);

    // Add submit event listener
    document.querySelector("form").addEventListener("submit", handleSubmit);
});

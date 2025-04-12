// Dummy initial data for the form
const initialFormData = {
  fullName: "John Doe",
  userName: "johndoe123",
  email: "john.doe@example.com",
  telephone: "1234567890",
  gender: "male",
  spokenLanguages: ["Sinhala", "English"],
  nic: "123456789V",
  nationality: "Sri Lankan",
  age: "26-35",
  service: "cleaning",
  experience: "intermediate",
  description: "Experienced cleaner.",
  workLocations: ["Colombo", "Gampaha"],
  certificates: ["birth", "police"],
  medical: ["hepatitis", "covid"],
  bankNameCode: "BOC001",
  accountNumber: "123456789012",
  workingWeekdays: "7-9",
  workingWeekends: "4-6",
  allergies: "None",
  notes: "Flexible schedule.",
};

// Populate the form fields with initial data
function populateForm(data) {
  const setInputValue = (id, value) => {
    const el = document.getElementById(id);
    if (el) {
      el.value = value;
      el.disabled = true;
    }
  };

  setInputValue("fullName", data.fullName);
  setInputValue("userName", data.userName);
  setInputValue("email", data.email);
  setInputValue("telephone", data.telephone);

  // Gender radio
  if (data.gender) {
    const genderRadio = document.querySelector(`input[name="gender"][value="${data.gender}"]`);
    if (genderRadio) {
      genderRadio.checked = true;
      genderRadio.disabled = true;
    }
  }

  // Language checkboxes
  const langs = data.spokenLanguages || [];
  langs.forEach(lang => {
    const checkbox = document.querySelector(`input[type="checkbox"][value="${lang}"]`);
    if (checkbox) {
      checkbox.checked = true;
      checkbox.disabled = true;
    }
  });

  setInputValue("idnumber", data.nic);
  setInputValue("nationality", data.nationality);
  setInputValue("hometown", data.hometown);
  setInputValue("age", data.age);
  setInputValue("service", data.service);
  setInputValue("experience", data.experience);
  setInputValue("description", data.description);

  // Work locations
  const workLocationsSelect = document.getElementById("work-locations");
  if (workLocationsSelect) {
    Array.from(workLocationsSelect.options).forEach(option => {
      option.selected = data.workLocations?.includes(option.value);
    });
    workLocationsSelect.disabled = true;
  }

  // Certificates & Medicals
  const checkCheckboxGroup = (name, values) => {
    values.forEach(val => {
      const cb = document.querySelector(`input[name="${name}"][value="${val}"]`);
      if (cb) {
        cb.checked = true;
        cb.disabled = true;
      }
    });
  };

  checkCheckboxGroup("certificates", data.certificates || []);
  checkCheckboxGroup("medical", data.medical || []);

  setInputValue("bankNameCode", data.bankNameCode);
  setInputValue("accountNumber", data.accountNumber);
  setInputValue("workingWeekdays", data.workingWeekdays);
  setInputValue("workingWeekends", data.workingWeekends);
  setInputValue("allergies", data.allergies);
  setInputValue("notes", data.notes);

  // Hide submit and show edit
  document.getElementById("submit").style.display = "none";
  const editBtn = document.createElement("button");
  editBtn.textContent = "Edit";
  editBtn.classList.add("edit_button");
  editBtn.addEventListener("click", enableEditMode);
  document.querySelector(".user_buttons")?.appendChild(editBtn);
}

function enableEditMode() {
  const inputs = document.querySelectorAll("input, select, textarea");
  inputs.forEach(input => {
    if (input.type !== "radio" && input.type !== "checkbox") {
      input.disabled = false;
    } else {
      input.disabled = false;
      input.removeAttribute("readonly");
    }
  });

  document.getElementById("submit").style.display = "block";
  const editBtn = document.querySelector(".edit_button");
  if (editBtn) editBtn.remove();
}

function handleSubmit(event) {
  event.preventDefault();

  const form = event.target.closest("form");
  if (!form.checkValidity()) {
    alert("Please fill out all required fields.");
    return;
  }

  const getValues = (name) => {
    return Array.from(document.querySelectorAll(`input[name="${name}"]:checked`)).map(el => el.value);
  };

  const formData = {
    fullName: document.getElementById("fullName")?.value || "",
    userName: document.getElementById("userName")?.value || "",
    email: document.getElementById("email")?.value || "",
    telephone: document.getElementById("telephone")?.value || "",
    gender: document.querySelector('input[name="gender"]:checked')?.value || "",
    spokenLanguages: getValues("spokenLanguages[]"),
    nic: document.getElementById("idnumber")?.value || "",
    nationality: document.getElementById("nationality")?.value || "",
    hometown: document.getElementById("hometown")?.value || "",
    age: document.getElementById("age")?.value || "",
    service: document.getElementById("service")?.value || "",
    experience: document.getElementById("experience")?.value || "",
    description: document.getElementById("description")?.value || "",
    workLocations: Array.from(document.getElementById("work-locations")?.selectedOptions || []).map(option => option.value),
    certificates: getValues("certificates"),
    medical: getValues("medical"),
    bankNameCode: document.getElementById("bankNameCode")?.value || "",
    accountNumber: document.getElementById("accountNumber")?.value || "",
    workingWeekdays: document.getElementById("workingWeekdays")?.value || "",
    workingWeekends: document.getElementById("workingWeekends")?.value || "",
    allergies: document.getElementById("allergies")?.value || "",
    notes: document.getElementById("notes")?.value || "",
  };

  console.log("Submitted Data:", formData);
  alert("Form submitted successfully!");

  // Disable everything again
  const inputs = document.querySelectorAll("input, select, textarea");
  inputs.forEach(input => input.disabled = true);
  document.getElementById("submit").style.display = "none";

  const editBtn = document.createElement("button");
  editBtn.textContent = "Edit";
  editBtn.classList.add("edit_button");
  editBtn.addEventListener("click", enableEditMode);
  document.querySelector(".user_buttons")?.appendChild(editBtn);
}

// Initialize everything
document.addEventListener("DOMContentLoaded", () => {
  populateForm(initialFormData);
  document.querySelector("form")?.addEventListener("submit", handleSubmit);
});

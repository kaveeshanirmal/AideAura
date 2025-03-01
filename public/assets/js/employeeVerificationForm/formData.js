const formDataKey = "verificationFormData";

function saveFormData(page) {
  const formData = JSON.parse(localStorage.getItem(formDataKey) || "{}");

  if (page === "page1") {
    formData.page1 = {
      fullName: document.getElementById("fullName")?.value || "",
      userName: document.getElementById("userName")?.value || "",
      email: document.getElementById("email")?.value || "",
      telephone: document.getElementById("telephone")?.value || "",
      gender:
        document.querySelector("input[name='gender']:checked")?.value || "",
    };
  } else if (page === "page2") {
    formData.page2 = {
      hometown: document.getElementById("hometown")?.value || "",
      age: document.getElementById("age")?.value || "",
      service: document.getElementById("service")?.value || "",
      experience: document.getElementById("experience")?.value || "",
      description: document.getElementById("description")?.value || "",
    };
  } else if (page === "page3") {
    formData.page3 = {
      workingWeekdays: document.getElementById("workingWeekdays")?.value || "",
      workingWeekends: document.getElementById("workingWeekends")?.value || "",
      notes: document.getElementById("notes")?.value || "",
    };
  }

  localStorage.setItem(formDataKey, JSON.stringify(formData));
}

function restoreFormData(page) {
  const formData = JSON.parse(localStorage.getItem(formDataKey) || "{}");

  if (page === "page1" && formData.page1) {
    const { fullName, userName, email, telephone, gender } = formData.page1;

    const fullNameField = document.getElementById("fullName");
    if (fullNameField) fullNameField.value = fullName || "";

    const userNameField = document.getElementById("userName");
    if (userNameField) userNameField.value = userName || "";

    const emailField = document.getElementById("email");
    if (emailField) emailField.value = email || "";

    const telephoneField = document.getElementById("telephone");
    if (telephoneField) telephoneField.value = telephone || "";

    const genderField = document.querySelector(
      `input[name='gender'][value='${gender}']`,
    );
    if (genderField) genderField.click();
  } else if (page === "page2" && formData.page2) {
    const { hometown, age, service, experience, description } = formData.page2;

    const hometownField = document.getElementById("hometown");
    if (hometownField) hometownField.value = hometown || "";

    const ageField = document.getElementById("age");
    if (ageField) ageField.value = age || "";

    const serviceField = document.getElementById("service");
    if (serviceField) serviceField.value = service || "";

    const experienceField = document.getElementById("experience");
    if (experienceField) experienceField.value = experience || "";

    const descriptionField = document.getElementById("description");
    if (descriptionField) descriptionField.value = description || "";
  } else if (page === "page3" && formData.page3) {
    const { workingWeekdays, workingWeekends, notes } = formData.page3;

    const workingWeekdaysField = document.getElementById("workingWeekdays");
    if (workingWeekdaysField)
      workingWeekdaysField.value = workingWeekdays || "";

    const workingWeekendsField = document.getElementById("workingWeekends");
    if (workingWeekendsField)
      workingWeekendsField.value = workingWeekends || "";

    const notesField = document.getElementById("notes");
    if (notesField) notesField.value = notes || "";
  }
}

function clearFormData() {
  localStorage.removeItem(formDataKey);
}

function submitForm() {
  // save form 3 data before submitting
  saveFormData("page3");
  // Retrieve all saved form data from localStorage
  const formData = JSON.parse(localStorage.getItem(formDataKey) || "{}");

  // Combine all pages' data into a single object
  const combinedData = {
    ...formData.page1,
    ...formData.page2,
    ...formData.page3,
  };

  console.log("Submitting combined form data:", combinedData);

  // Send the data to the backend via fetch
  fetch(`${ROOT}/public/workerVerification/submitVerificationForm`, {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify(combinedData),
  })
    .then((response) => {
      if (!response.ok) {
        throw new Error(`Error in submission: ${response.statusText}`);
      }
      return response.json();
    })
    .then((result) => {
      console.log("Form submitted successfully:", result);
      window.location.href = `${ROOT}/public/workerVerification/verificationStatus`;

      // clearFormData();
    })
    .catch((error) => {
      console.error("Error submitting form:", error);
      window.location.href = `${ROOT}/public/workerVerification/verificationStatus`;
    });
}

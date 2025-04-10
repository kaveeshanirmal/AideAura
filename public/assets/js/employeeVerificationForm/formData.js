const formDataKey = "verificationFormData";

function saveFormData(page) {
  const formData = JSON.parse(localStorage.getItem(formDataKey) || "{}");

  if (page === "page1") {
    const languages = Array.from(document.querySelectorAll("input[type='checkbox']:checked"))
    .map(cb => cb.value);

  formData.page1 = {
    fullName: document.getElementById("fullName")?.value || "",
    userName: document.getElementById("userName")?.value || "",
    email: document.getElementById("email")?.value || "",
    telephone: document.getElementById("telephone")?.value || "",
    gender: document.querySelector("input[name='gender']:checked")?.value || "",
    languages: languages,
    };
  } else if (page === "page2") {

    const workLocationsSelect = document.getElementById("work-locations");
    const workLocations = workLocationsSelect ? 
      Array.from(workLocationsSelect.selectedOptions).map(option => option.value) : 
      [];

    formData.page2 = {
      hometown: document.getElementById("hometown")?.value || "",
      nic: document.getElementById("idnumber")?.value || "",
      nationality: document.getElementById("nationality")?.value || "",
      age: document.getElementById("age")?.value || "",
      service: document.getElementById("service")?.value || "",
      experience: document.getElementById("experience")?.value || "",
      workLocations: workLocations,
      certificates: Array.from(document.querySelectorAll("input[name='certificates']:checked"))
      .map(cb => cb.value),
      medical: Array.from(document.querySelectorAll("input[name='medical']:checked"))
      .map(cb => cb.value),
      description: document.getElementById("description")?.value || "",
      bankNameCode: document.getElementById("bankNameCode")?.value || "",
      accountNumber: document.getElementById("accountNumber")?.value || "",
    };
  } else if (page === "page3") {
    formData.page3 = {
      workingWeekdays: document.getElementById("workingWeekdays")?.value || "",
      workingWeekends: document.getElementById("workingWeekends")?.value || "",
      allergies: document.getElementById("allergies")?.value || "",
      notes: document.getElementById("notes")?.value || "",
    };
  }

  localStorage.setItem(formDataKey, JSON.stringify(formData));
}

function restoreFormData(page) {
  const formData = JSON.parse(localStorage.getItem(formDataKey) || "{}");

  if (page === "page1" && formData.page1) {
    const { fullName, userName, email, telephone, gender, languages } = formData.page1;

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

    if (Array.isArray(languages)) {
      languages.forEach(lang => {
        const checkbox = document.querySelector(`input[type='checkbox'][value='${lang}']`);
        if (checkbox) checkbox.checked = true;
      });
    }

  } else if (page === "page2" && formData.page2) {
    const { hometown, nic, nationality, age, service, experience, workLocations , certificates , medical , description } = formData.page2;

    const hometownField = document.getElementById("hometown");
    if (hometownField) hometownField.value = hometown || "";

    const nicField = document.getElementById("idnumber");
    if (nicField) nicField.value = nic || "";

    const nationalityField = document.getElementById("nationality");
    if (nationalityField) nationalityField.value = nationality || "";

    const ageField = document.getElementById("age");
    if (ageField) ageField.value = age || "";;

    const serviceField = document.getElementById("service");
    if (serviceField) serviceField.value = service || "";

    const experienceField = document.getElementById("experience");
    if (experienceField) experienceField.value = experience || "";

    const workLocationsField = document.getElementById("work-locations");
    if (workLocationsField && Array.isArray(workLocations)) {
      // Clear any existing selections
      for (let i = 0; i < workLocationsField.options.length; i++) {
        workLocationsField.options[i].selected = false;
      }
      
      // Set the selected options based on stored values
      workLocations.forEach(location => {
        for (let i = 0; i < workLocationsField.options.length; i++) {
          if (workLocationsField.options[i].value === location) {
            workLocationsField.options[i].selected = true;
          }
        }
      });
    }

    const certificatesField = document.getElementById("certificates");
    if (certificatesField && Array.isArray(formData.page2?.certificates)) {
      formData.page2.certificates.forEach(certificate => {
        const checkbox = document.querySelector(`input[name='certificates'][value='${certificate}']`);
        if (checkbox) checkbox.checked = true;
      });
    }

    const medicalField = document.getElementById("medical");
    if (medicalField && Array.isArray(formData.page2?.medical)) {
      formData.page2.medical.forEach(medicalItem => {
        const checkbox = document.querySelector(`input[name='medical'][value='${medicalItem}']`);
        if (checkbox) checkbox.checked = true;
      });
    }

    const descriptionField = document.getElementById("description");
    if (descriptionField) descriptionField.value = description || "";

    const bankNameCodeField = document.getElementById("bankNameCode");
    if (bankNameCodeField) bankNameCodeField.value = formData.page2?.bankNameCode || "";

    const accountNumberField = document.getElementById("accountNumber");
    if (accountNumberField) accountNumberField.value = formData.page2?.accountNumber || "";

  } else if (page === "page3" && formData.page3) {
    const { workingWeekdays, workingWeekends, allergies, notes } = formData.page3;

    const workingWeekdaysField = document.getElementById("workingWeekdays");
    if (workingWeekdaysField)
      workingWeekdaysField.value = workingWeekdays || "";

    const workingWeekendsField = document.getElementById("workingWeekends");
    if (workingWeekendsField)
      workingWeekendsField.value = workingWeekends || "";

    const allergiesField = document.getElementById("allergies");
    if (allergiesField)
       allergiesField.value = allergies || "";

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

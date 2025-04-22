// Define the key for storing form data in localStorage
const formDataKey = "employeeVerificationFormData";

// Updated saveFormData function to handle the renamed file inputs
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

    // Note: We don't store file input values in localStorage as they can't be serialized
    // But we keep the other form data processing the same
    formData.page2 = {
      hometown: document.getElementById("hometown")?.value || "",
      nic: document.getElementById("idnumber")?.value || "",
      nationality: document.getElementById("nationality")?.value || "",
      age: document.getElementById("age")?.value || "",
      service: document.getElementById("service")?.value || "",
      experience: document.getElementById("experience")?.value || "",
      workLocations: workLocations,
      certificateFilename: document.getElementById("certificateFile")?.files[0]?.name || "",
      medicalFilename: document.getElementById("medicalFile")?.files[0]?.name || "",
      description: document.getElementById("description")?.value || "",
      bankNameCode: document.getElementById("bankNameCode")?.value || "",
      accountNumber: document.getElementById("accountNumber")?.value || "",
    };

    // File inputs are handled separately at submission time
    // Just log the presence of files for debugging
    const certificateFile = document.querySelector("input[name='certificateFile']");
    const medicalFile = document.querySelector("input[name='medicalFile']");
    
    if (certificateFile && certificateFile.files.length > 0) {
      console.log("Certificate file selected:", certificateFile.files[0].name);
    }
    
    if (medicalFile && medicalFile.files.length > 0) {
      console.log("Medical file selected:", medicalFile.files[0].name);
    }
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

// Updated restoreFormData function
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
    const { hometown, nic, nationality, age, service, experience, workLocations, certificates, medical, description } = formData.page2;

    const hometownField = document.getElementById("hometown");
    if (hometownField) hometownField.value = hometown || "";

    const nicField = document.getElementById("idnumber");
    if (nicField) nicField.value = nic || "";

    const nationalityField = document.getElementById("nationality");
    if (nationalityField) nationalityField.value = nationality || "";

    const ageField = document.getElementById("age");
    if (ageField) ageField.value = age || "";

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

    // Handle certificates checkboxes (not related to the file upload)
    if (Array.isArray(certificates)) {
      certificates.forEach(certificate => {
        const checkbox = document.querySelector(`input[name='certificates'][value='${certificate}']`);
        if (checkbox) checkbox.checked = true;
      });
    }

    // Handle medical checkboxes (not related to the file upload)
    if (Array.isArray(medical)) {
      medical.forEach(medicalItem => {
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

    // Note: We don't restore file inputs as they can't be stored in localStorage
    // File inputs will be handled at submission time

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

// In the JavaScript submitForm function, replace the existing file handling code with this:
function submitForm() {
  saveFormData("page3");

  const formData = JSON.parse(localStorage.getItem(formDataKey) || "{}");
  const combinedData = {
    ...formData.page1,
    ...formData.page2,
    ...formData.page3,
  };

  // Create a FormData object to include files and other data
  const submissionData = new FormData();

  // Append non-file fields
  for (const key in combinedData) {
    if (Array.isArray(combinedData[key])) {
      combinedData[key].forEach(item => submissionData.append(`${key}[]`, item));
    } else {
      submissionData.append(key, combinedData[key]);
    }
  }

  // Get file inputs - ensure we're looking for the actual file input elements
  const certificateFileInput = document.querySelector("input[type='file'][name='certificateFile']");
  const medicalFileInput = document.querySelector("input[type='file'][name='medicalFile']");

  // Add files to form data if they exist
  if (certificateFileInput && certificateFileInput.files.length > 0) {
    submissionData.append("certificateFile", certificateFileInput.files[0]);
    console.log("Certificate file added:", certificateFileInput.files[0].name);
  }

  if (medicalFileInput && medicalFileInput.files.length > 0) {
    submissionData.append("medicalFile", medicalFileInput.files[0]);
    console.log("Medical file added:", medicalFileInput.files[0].name);
  }

  // Debug what's being sent
  console.log("Form data being submitted:");
  for (let pair of submissionData.entries()) {
    console.log(pair[0] + ': ' + (pair[1] instanceof File ? pair[1].name : pair[1]));
  }

  fetch(`${ROOT}/public/workerVerification/submitVerificationForm`, {
    method: "POST",
    body: submissionData, // FormData handles the correct Content-Type automatically
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
    })
    .catch((error) => {
      console.error("Error submitting form:", error);
      window.location.href = `${ROOT}/public/workerVerification/verificationStatus`;
    });
}


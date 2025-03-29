document.addEventListener("DOMContentLoaded", () => {
  const overlay = document.getElementById("replyOverlay");
  const form = document.getElementById("replyForm");
  const responseMessage = document.getElementById("responseMessage");
  const closeOverlayButton = document.getElementById("closeOverlayButton");
  const closeMessageButton = document.getElementById("closeMessageButton");
  const complaintIdInput = document.getElementById("complaintIdInput");
  const solutionText = document.getElementById("solutionText");

  // Show overlay on reply button click
  document.querySelectorAll(".reply-button").forEach((button) => {
    button.addEventListener("click", () => {
      const complaintId = button.getAttribute("data-complaint-id");
      complaintIdInput.value = complaintId;
      solutionText.value = ""; // Clear previous input
      responseMessage.style.display = "none";
      closeMessageButton.style.display = "none";
      overlay.style.display = "flex";
    });
  });

  // Close overlay
  closeOverlayButton.addEventListener("click", () => {
    overlay.style.display = "none";
  });

  closeMessageButton.addEventListener("click", () => {
    overlay.style.display = "none";
    // reload header to update the number of complaints
    location.reload();
  });

  form.addEventListener("submit", (e) => {
    e.preventDefault();
    const formData = Object.fromEntries(new FormData(form));

    // Send data to the server
    fetch(`${ROOT}/public/admin/replyComplaint`, {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify(formData),
    })
      .then((response) => {
        if (!response.ok) {
          throw new Error("Network response was not ok");
        }
        return response.json();
      })
      .then((result) => {
        if (result.success) {
          responseMessage.textContent = result.message;
          responseMessage.style.color = "green";
        } else {
          responseMessage.textContent = result.message;
          responseMessage.style.color = "red";
        }
        responseMessage.style.display = "block";
        closeMessageButton.style.display = "block";
      })
      .catch((error) => {
        console.error("Error:", error);
        responseMessage.textContent = error.message;
        responseMessage.style.color = "red";
        responseMessage.style.display = "block";
        closeMessageButton.style.display = "block";
      });
  });
});

// JavaScript to handle dynamic filtering and sorting
const filterSelect = document.getElementById("issueFilter");
const sortSelect = document.getElementById("prioritySort");
const tableBody = document.getElementById("complaintsTableBody");

filterSelect.addEventListener("change", () => {
  const filterValue = filterSelect.value;
  const rows = Array.from(tableBody.querySelectorAll("tr"));
  rows.forEach((row) => {
    const issueType = row.cells[1]?.textContent.trim();
    if (filterValue === "all" || issueType === filterValue) {
      row.style.display = "";
    } else {
      row.style.display = "none";
    }
  });
});

sortSelect.addEventListener("change", () => {
  const sortValue = sortSelect.value;
  const rows = Array.from(tableBody.querySelectorAll("tr")).filter(
    (row) => row.style.display !== "none",
  );
  rows.sort((a, b) => {
    const priorityA = a.cells[3]?.textContent.trim();
    const priorityB = b.cells[3]?.textContent.trim();
    const priorityOrder = { Critical: 3, High: 2, Medium: 1, Low: 0 };
    const diff =
      (priorityOrder[priorityA] || 0) - (priorityOrder[priorityB] || 0);
    return sortValue === "high-to-low" ? -diff : diff;
  });
  tableBody.innerHTML = "";
  rows.forEach((row) => tableBody.appendChild(row));
});

document.addEventListener("DOMContentLoaded", () => {
  const deleteOverlay = document.getElementById("deleteOverlay");
  const deleteComplaintIdInput = document.getElementById("deleteComplaintId");
  const deleteResponseMessage = document.getElementById(
    "deleteResponseMessage",
  );
  const cancelDeleteButton = document.getElementById("cancelDeleteButton");
  const confirmDeleteButton = document.getElementById("confirmDeleteButton");
  const closeDeleteMessageButton = document.getElementById(
    "closeDeleteMessageButton",
  );
  const replyOverlay = document.getElementById("replyOverlay");

  // Show delete confirmation overlay
  document.querySelectorAll(".delete-button").forEach((button) => {
    button.addEventListener("click", (event) => {
      // Prevent event from bubbling up
      event.stopPropagation();

      console.log("Delete button clicked");
      const complaintId = button.getAttribute("data-complaint-id");
      deleteComplaintIdInput.value = complaintId;
      deleteResponseMessage.style.display = "none";
      closeDeleteMessageButton.style.display = "none";

      // Ensure the delete overlay is outside of the reply overlay in the DOM
      document.body.appendChild(deleteOverlay);
      deleteOverlay.style.display = "flex";
    });
  });

  // Cancel deletion
  cancelDeleteButton.addEventListener("click", () => {
    deleteOverlay.style.display = "none";
    // Move the overlay back to its original container if needed
    replyOverlay.appendChild(deleteOverlay);
  });

  closeDeleteMessageButton.addEventListener("click", () => {
    deleteOverlay.style.display = "none";
    // Move the overlay back to its original container if needed
    replyOverlay.appendChild(deleteOverlay);
    location.reload();
  });

  // Confirm deletion
  confirmDeleteButton.addEventListener("click", () => {
    const complaintId = deleteComplaintIdInput.value;

    // Send delete request to server
    fetch(`${ROOT}/public/admin/deleteComplaint`, {
      method: "DELETE",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({ complaintId }),
    })
      .then((response) => {
        if (!response.ok) {
          throw new Error("Network response was not ok");
        }
        return response.json();
      })
      .then((result) => {
        if (result.success) {
          deleteResponseMessage.textContent =
            result.message || "Complaint deleted successfully!";
          deleteResponseMessage.style.color = "green";
          document
            .querySelector(`button[data-complaint-id="${complaintId}"]`)
            .closest("tr")
            .remove();
        } else {
          deleteResponseMessage.textContent =
            result.message || "Failed to delete the complaint.";
          deleteResponseMessage.style.color = "red";
        }
        deleteResponseMessage.style.display = "block";
        closeDeleteMessageButton.style.display = "block";
      })
      .catch((error) => {
        console.error("Error:", error);
        deleteResponseMessage.textContent =
          error.message || "An error occurred while deleting the complaint.";
        deleteResponseMessage.style.color = "red";
        deleteResponseMessage.style.display = "block";
        closeDeleteMessageButton.style.display = "block";
      });
  });
});

document.addEventListener("DOMContentLoaded", function () {
  const descriptionCells = document.querySelectorAll(".description-tooltip");

  descriptionCells.forEach((cell) => {
    cell.addEventListener("click", function () {
      const fullDescription = this.getAttribute("data-full-description");
      // Optional: You could show a modal with full description
      alert(fullDescription);
    });
  });
});

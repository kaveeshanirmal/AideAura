//Notification Functionality
const notification = document.getElementById('notification');
const showNotification = (message, type) => {
    notification.textContent = message;
    notification.className = `notification ${type} show`;
    setTimeout(() => notification.className = 'notification hidden',2000);
};

function viewAvailabilitySchedule(userID) {
    console.log("Viewing availability schedule for userID:", userID);
    
    // Redirect to the availability schedule page with the userID as parameter
    // window.location.href = `${ROOT}/public/HrManager/getAvailabilitySchedule?userID=${userID}`;
    
    // Alternative approach: Submit a form to use POST method
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = `${ROOT}/public/Admin/getAvailabilitySchedule`;
    
    const userIDInput = document.createElement('input');
    userIDInput.type = 'hidden';
    userIDInput.name = 'userID';
    userIDInput.value = userID;
    
    form.appendChild(userIDInput);
    document.body.appendChild(form);
    form.submit();
}

function updateStatus(status) {
    const requestID = document.getElementById('requestID').value;
    
    // Check if requestID is valid
    if (requestID === 'N/A' || !requestID) {
        showNotification('Invalid request ID. Cannot update status.', 'error');
        return;
    }
    
    const userID = document.getElementById('userID').value;
    console.log(requestID, userID, status);
    
    fetch(`${ROOT}/public/Admin/updateVerificationStatus`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `requestID=${requestID}&status=${status}`
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            showNotification('Status updated successfully!', 'success');
            
            // Reload the page after a short delay
            setTimeout(() => {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `${ROOT}/public/Admin/workerDetails`;
                
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'workerData';
                input.value = userID;
                
                form.appendChild(input);
                document.body.appendChild(form);
                form.submit();
            }, 2000);
        } else {
            showNotification(data.message || 'Failed to update status.', 'error');
        }
    })
    .catch(err => {
        console.error('Error:', err);
        showNotification('An error occurred while updating.', 'error');
    });
}
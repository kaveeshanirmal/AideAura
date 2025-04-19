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
    form.action = `${ROOT}/public/HrManager/getAvailabilitySchedule`;
    
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
    //Get the current source from the hidden input
    const currentSourceEl = document.getElementById('currentSource');
    const currentSource = currentSourceEl ? currentSourceEl.value : 'workerProfiles';

    console.log(requestID, userID, status);
    
    fetch(`${ROOT}/public/HrManager/updateVerificationStatus`, {
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
                form.action = `${ROOT}/public/HrManager/workerDetails`;
                
                // Add workerData input (userID)
                const workerInput = document.createElement('input');
                workerInput.type = 'hidden';
                workerInput.name = 'workerData';
                workerInput.value = userID;
                
                // Add source input with the current source
                const sourceInput = document.createElement('input');
                sourceInput.type = 'hidden';
                sourceInput.name = 'source';
                sourceInput.value = currentSource;
                
                form.appendChild(workerInput);
                form.appendChild(sourceInput);
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


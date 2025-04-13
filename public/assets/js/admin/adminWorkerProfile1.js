function openUpdateModal() {
    document.getElementById('updateModal').style.display = 'flex';
}

function closeUpdateModal() {
    document.getElementById('updateModal').style.display = 'none';
}

console.log(worker);
// Form submission handler
document.getElementById('workerUpdateForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Collect form data
    const formData = new FormData(this);
    const data = Object.fromEntries(formData.entries());

    // Send update request
    fetch('<?=ROOT?>/public/adminWorkerProfile/update', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(result => {
        if (result.success) {
            alert('Profile updated successfully');
            closeUpdateModal();
            // Optionally refresh the page or update UI
            location.reload();
        } else {
            alert('Update failed: ' + result.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while updating the profile');
    });
});
document.addEventListener('DOMContentLoaded', function() {
    // Initialize notification element
    const notification = document.getElementById('notification');
    
    // Define notification function in global scope
    window.showNotification = function(message, type) {
        if (notification) {
            notification.textContent = message;
            notification.className = `notification ${type} show`;
            setTimeout(() => notification.className = 'notification hidden', 2000);
        } else {
            console.error('Notification element not found');
        }
    };
});

function showUpdateModal(button) {
    const row = button.closest('tr');
    const serviceID = row.cells[0].textContent;
    const basePrice = row.cells[2].textContent;
    const baseHours = row.cells[3].textContent;

    document.getElementById('serviceIdInput').value = serviceID;
    document.getElementById('basePriceInput').value = basePrice;
    document.getElementById('baseHoursInput').value = baseHours;

    document.getElementById('updateModal').style.display = 'block';
}

function closeUpdateModal() {
    document.getElementById('updateModal').style.display = 'none';
}

function updateEmployee() {
    // Get input elements
    const serviceIdInput = document.getElementById('serviceIdInput');
    const basePriceInput = document.getElementById('basePriceInput');
    const baseHoursInput = document.getElementById('baseHoursInput');
    
    // Validate inputs exist
    if (!serviceIdInput || !basePriceInput || !baseHoursInput) {
        showNotification("Error: One or more input fields are missing.", 'error');
        return;
    }
    
    // Validate input values
    const ServiceID = serviceIdInput.value.trim();
    const BasePrice = parseFloat(document.getElementById('basePriceInput').value).toFixed(2);
    const BaseHours = parseFloat(document.getElementById('baseHoursInput').value).toFixed(2);
    
    if (!ServiceID || isNaN(BasePrice) || isNaN(BaseHours)) {
        showNotification("Please fill in all fields with valid numbers.", 'error');
        return;
    }
    
    const data = { ServiceID, BasePrice, BaseHours };
    
    // Show loading state
    showNotification('Updating payment rates...', 'info');
    
    const rootUrl = document.getElementById('rootUrl').value; // Ensure this value is set in your HTML
    fetch(`${rootUrl}/public/FinanceManager/updatePaymentRates`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify(data)
    })
    .then(async response => {
        const result = await response.json();
        if (!response.ok) {
            throw new Error(result.message || 'Server error');
        }
        return result;
    })
    .then(result => {
        if (result.success) {
            showNotification('Payment rate updated successfully', 'success');
            setTimeout(() => location.reload(), 2000);
            closeUpdateModal();
        } else {
            showNotification('Payment Rates update failed', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('An unexpected error occurred', 'error');
    });
}
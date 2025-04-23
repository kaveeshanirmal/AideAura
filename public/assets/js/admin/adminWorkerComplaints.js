document.addEventListener('DOMContentLoaded', function() {
    // DOM Elements
    const complaintsList = document.getElementById('complaints-list');
    const noChatSelected = document.getElementById('no-chat-selected');
    const chatInterface = document.getElementById('chat-interface');
    const chatMessages = document.getElementById('chat-messages');
    const chatForm = document.getElementById('chat-form');
    const messageInput = document.getElementById('message-input');
    const workerName = document.getElementById('worker-name');
    const workerId = document.getElementById('worker-id');
    const issueType = document.getElementById('issue-type');
    const issuePriority = document.getElementById('issue-priority');
    const resolveButton = document.getElementById('resolveButton');
    const deleteButton = document.getElementById('deleteButton');
    const resolveModal = document.getElementById('resolveModal');
    const deleteModal = document.getElementById('deleteModal');
    const confirmResolve = document.getElementById('confirmResolve');
    const cancelResolve = document.getElementById('cancelResolve');
    const confirmDelete = document.getElementById('confirmDelete');
    const cancelDelete = document.getElementById('cancelDelete');
    const issueFilter = document.getElementById('issueFilter');
    const prioritySort = document.getElementById('prioritySort');
    const statusFilter = document.getElementById('statusFilter');
  
    // Current complaint ID
    let currentComplaintId = null;
  
    // Event Listeners
    if (complaintsList) {
        // Delegate event listener for complaint items
        complaintsList.addEventListener('click', function(e) {
            const complaintItem = e.target.closest('.complaint-item');
            if (complaintItem) {
                // Remove active class from all complaints
                document.querySelectorAll('.complaint-item').forEach(item => {
                    item.classList.remove('active');
                });
                
                // Add active class to selected complaint
                complaintItem.classList.add('active');
                
                // Get complaint ID
                const complaintId = complaintItem.dataset.complaintId;
                loadComplaintDetails(complaintId);
            }
        });
    }
  
    // Filters change event listeners
    if (issueFilter) {
        issueFilter.addEventListener('change', applyFilters);
    }
  
    if (prioritySort) {
        prioritySort.addEventListener('change', applyFilters);
    }
  
    if (statusFilter) {
        statusFilter.addEventListener('change', applyFilters);
    }
  
    // Chat form submission
    if (chatForm) {
        chatForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            if (!currentComplaintId) {
                showToast('Please select a complaint first', 'error');
                return;
            }
            
            const message = messageInput.value.trim();
            if (message) {
                sendReply(message);
            }
        });
    }
  
    // Resolve button click
    if (resolveButton) {
        resolveButton.addEventListener('click', function() {
            if (!currentComplaintId) {
                showToast('Please select a complaint first', 'error');
                return;
            }
            
            resolveModal.style.display = 'flex';
        });
    }
  
    // Delete button click
    if (deleteButton) {
        deleteButton.addEventListener('click', function() {
            if (!currentComplaintId) {
                showToast('Please select a complaint first', 'error');
                return;
            }
            
            deleteModal.style.display = 'flex';
        });
    }
  
    // Confirm resolve
    if (confirmResolve) {
        confirmResolve.addEventListener('click', function() {
            resolveComplaint();
            resolveModal.style.display = 'none';
        });
    }
  
    // Cancel resolve
    if (cancelResolve) {
        cancelResolve.addEventListener('click', function() {
            resolveModal.style.display = 'none';
        });
    }
  
    // Confirm delete
    if (confirmDelete) {
        confirmDelete.addEventListener('click', function() {
            deleteComplaint();
            deleteModal.style.display = 'none';
        });
    }
  
    // Cancel delete
    if (cancelDelete) {
        cancelDelete.addEventListener('click', function() {
            deleteModal.style.display = 'none';
        });
    }
  
    // Functions
  
    /**
     * Load complaint details
     * @param {string} complaintId - The ID of the complaint
     */
    function loadComplaintDetails(complaintId) {
        currentComplaintId = complaintId;
        
        // Show chat interface and hide empty state
        noChatSelected.style.display = 'none';
        chatInterface.style.display = 'flex';
        
        // Clear previous messages
        chatMessages.innerHTML = '';
        
        // Add loading spinner
        chatMessages.innerHTML = '<div class="loading-spinner"><div></div><div></div><div></div></div>';
        
        // Fetch complaint details
        fetch(`${ROOT}/public/WorkerComplaint/details/${complaintId}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`Server responded with status: ${response.status}`);
                }
                return response.text().then(text => {
                    console.log('Raw response:', text);
                    try {
                        return JSON.parse(text);
                    } catch (e) {
                        console.error('Failed to parse JSON response:', e);
                        throw new Error('Invalid JSON response from server');
                    }
                });
            })
            .then(data => {
                if (data.success) {
                    const complaint = data.complaint;
  
                    // Update worker info
                    workerName.textContent = data.workerName;
                    workerId.textContent = `Worker ID: ${complaint.workerID}`;
                    issueType.textContent = `Issue: ${complaint.issue}`;
                    issuePriority.textContent = complaint.priority;
                    issuePriority.className = `priority-badge priority-${complaint.priority.toLowerCase()}`;
                    
                    // Load chat history
                    loadChatHistory(complaintId);
                    
                    // Check if complaint is already resolved
                    if (complaint.status === 'Resolved') {
                        resolveButton.disabled = true;
                        resolveButton.classList.add('disabled');
                        messageInput.disabled = true;
                        chatForm.querySelector('button[type="submit"]').disabled = true;
                    } else {
                        resolveButton.disabled = false;
                        resolveButton.classList.remove('disabled');
                        messageInput.disabled = false;
                        chatForm.querySelector('button[type="submit"]').disabled = false;
                    }
                } else {
                    showToast(data.message || 'Failed to load complaint details', 'error');
                }
            })
            .catch(error => {
                console.error('Error fetching complaint details:', error);
                showToast('Error loading complaint details. Check console for details.', 'error');
            });
    }
  
    /**
     * Load chat history for a complaint
     * @param {string} complaintId - The ID of the complaint
     */
    function loadChatHistory(complaintId) {
        fetch(`${ROOT}/public/WorkerComplaint/chat/${complaintId}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`Server responded with status: ${response.status}`);
                }
                return response.text().then(text => {
                    console.log('Raw chat response:', text);
                    try {
                        return JSON.parse(text);
                    } catch (e) {
                        console.error('Failed to parse JSON response:', e);
                        throw new Error('Invalid JSON response from server');
                    }
                });
            })
            .then(data => {
                // Clear loading spinner
                chatMessages.innerHTML = '';
  
                if (data.success) {
                    // Add initial complaint as first message
                    fetch(`${ROOT}/public/WorkerComplaint/details/${complaintId}`)
                        .then(response => {
                            if (!response.ok) {
                                throw new Error(`Server responded with status: ${response.status}`);
                            }
                            return response.text().then(text => {
                                console.log('Raw details response within chat:', text);
                                try {
                                    return JSON.parse(text);
                                } catch (e) {
                                    console.error('Failed to parse JSON response:', e);
                                    throw new Error('Invalid JSON response from server');
                                }
                            });
                        })
                        .then(detailsData => {
                            if (detailsData.success) {
                                const complaint = detailsData.complaint;
  
                                // Add initial complaint message
                                const initialMessageDiv = document.createElement('div');
                                initialMessageDiv.className = 'message worker-message';
                                initialMessageDiv.innerHTML = `
                                    <div class="message-content">
                                        <div class="message-header">
                                            <span class="sender">${detailsData.workerName}</span>
                                            <span class="timestamp">${formatDate(complaint.submitted_at)}</span>
                                        </div>
                                        <div class="message-body">
                                            <p>${complaint.description}</p>
                                        </div>
                                    </div>
                                `;
                                chatMessages.appendChild(initialMessageDiv);
  
                                // Add all updates/replies
                                const updates = data.updates || [];
                                updates.forEach(update => {
                                    // Create the message div
                                    const messageDiv = document.createElement('div');
  
                                    // Determine role-based message styling
                                    const role = update.role || 'worker';  // Default to worker if no role provided
  
                                    // Set appropriate class for different roles
                                    messageDiv.className = `message ${role}-message`;
  
                                    // Display the sender name based on role
                                    const senderName = role === 'admin' ? 'Admin' : 
                                                        role === 'opManager' ? 'Operational Manager' : 
                                                        role === 'financeManager' ? 'Finance Manager' :
                                                        role === 'hrManager' ? 'HR Manager' :
                                                        detailsData.workerName;
  
                                    messageDiv.innerHTML = `
                                        <div class="message-content">
                                            <div class="message-header">
                                                <span class="sender">${senderName}</span>
                                                <span class="timestamp">${formatDate(update.updated_at)}</span>
                                            </div>
                                            <div class="message-body">
                                                <p>${update.comments}</p>
                                            </div>
                                            ${update.status ? `<div class="status-update">Status changed to: <span class="status-badge status-${update.status.toLowerCase().replace(' ', '-')}">${update.status}</span></div>` : ''}
                                        </div>
                                    `;
  
                                    chatMessages.appendChild(messageDiv);
                                });
  
                                // Scroll to bottom
                                chatMessages.scrollTop = chatMessages.scrollHeight;
                            }
                        })
                        .catch(error => {
                            console.error('Error fetching complaint details:', error);
                            showToast('Error loading complaint details for chat history', 'error');
                        });
                } else {
                    showToast(data.message || 'Failed to load chat history', 'error');
                }
            })
            .catch(error => {
                console.error('Error fetching chat history:', error);
                showToast('Error loading chat history. Check console for details.', 'error');
            });
    }
  
    /**
     * Send a reply to the complaint
     * @param {string} message - The message to send
     */
    function sendReply(message) {
        // Disable form while sending
        messageInput.disabled = true;
        chatForm.querySelector('button[type="submit"]').disabled = true;
        
        // Create payload
        const payload = {
            complaintID: currentComplaintId,
            comments: message,
            status: 'In Progress'
            // Note: userID and role will be set by the server from $_SESSION
        };
        
        // Send request
        fetch(`${ROOT}/public/WorkerComplaint/respond`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(payload)
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`Server responded with status: ${response.status}`);
                }
                return response.text().then(text => {
                    console.log('Raw response from respond:', text);
                    try {
                        return JSON.parse(text);
                    } catch (e) {
                        console.error('Failed to parse JSON response:', e);
                        throw new Error('Invalid JSON response from server');
                    }
                });
            })
            .then(data => {
                if (data.success) {
                    // Clear input field
                    messageInput.value = '';
                    
                    // Reload chat history
                    loadChatHistory(currentComplaintId);
                    
                    // Update the complaint status in the list
                    const complaintItem = document.querySelector(`.complaint-item[data-complaint-id="${currentComplaintId}"]`);
                    if (complaintItem) {
                        const statusBadge = complaintItem.querySelector('.status-badge');
                        if (statusBadge) {
                            statusBadge.textContent = 'In Progress';
                            statusBadge.className = 'status-badge status-in-progress';
                        }
                    }
                    
                    showToast('Reply sent successfully', 'success');
                } else {
                    showToast(data.message || 'Failed to send reply', 'error');
                }
                
                // Re-enable form
                messageInput.disabled = false;
                chatForm.querySelector('button[type="submit"]').disabled = false;
                messageInput.focus();
            })
            .catch(error => {
                console.error('Error sending reply:', error);
                showToast('Error sending reply. Check console for details.', 'error');
                
                // Re-enable form
                messageInput.disabled = false;
                chatForm.querySelector('button[type="submit"]').disabled = false;
            });
    }
  
    /**
     * Resolve a complaint
     */
    function resolveComplaint() {
        // Create payload
        const payload = {
            complaintID: currentComplaintId,
            solution: 'This complaint has been marked as resolved.'
            // Note: userID and role will be set by the server from $_SESSION
        };
        
        // Send request
        fetch(`${ROOT}/public/WorkerComplaint/resolve`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(payload)
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`Server responded with status: ${response.status}`);
                }
                return response.text().then(text => {
                    console.log('Raw response from resolve:', text);
                    try {
                        return JSON.parse(text);
                    } catch (e) {
                        console.error('Failed to parse JSON response:', e);
                        throw new Error('Invalid JSON response from server');
                    }
                });
            })
            .then(data => {
                if (data.success) {
                    // Update UI
                    resolveButton.disabled = true;
                    resolveButton.classList.add('disabled');
                    messageInput.disabled = true;
                    chatForm.querySelector('button[type="submit"]').disabled = true;
                    
                    // Update the complaint status in the list
                    const complaintItem = document.querySelector(`.complaint-item[data-complaint-id="${currentComplaintId}"]`);
                    if (complaintItem) {
                        const statusBadge = complaintItem.querySelector('.status-badge');
                        if (statusBadge) {
                            statusBadge.textContent = 'Resolved';
                            statusBadge.className = 'status-badge status-resolved';
                        }
                    }
                    
                    // Reload chat history
                    loadChatHistory(currentComplaintId);
                    
                    showToast('Complaint resolved successfully', 'success');
                } else {
                    showToast(data.message || 'Failed to resolve complaint', 'error');
                }
            })
            .catch(error => {
                console.error('Error resolving complaint:', error);
                showToast('Error resolving complaint. Check console for details.', 'error');
            });
    }
  
    /**
     * Delete a complaint
     */
    function deleteComplaint() {
        // Send request
        fetch(`${ROOT}/public/WorkerComplaint/delete`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                complaintId: currentComplaintId
            })
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`Server responded with status: ${response.status}`);
                }
                return response.text().then(text => {
                    console.log('Raw response from delete:', text);
                    try {
                        return JSON.parse(text);
                    } catch (e) {
                        console.error('Failed to parse JSON response:', e);
                        throw new Error('Invalid JSON response from server');
                    }
                });
            })
            .then(data => {
                if (data.success) {
                    // Remove complaint from list
                    const complaintItem = document.querySelector(`.complaint-item[data-complaint-id="${currentComplaintId}"]`);
                    if (complaintItem) {
                        complaintItem.remove();
                    }
                    
                    // Reset current complaint
                    currentComplaintId = null;
                    
                    // Show empty state
                    noChatSelected.style.display = 'flex';
                    chatInterface.style.display = 'none';
                    
                    showToast('Complaint deleted successfully', 'success');
                } else {
                    showToast(data.message || 'Failed to delete complaint', 'error');
                }
            })
            .catch(error => {
                console.error('Error deleting complaint:', error);
                showToast('Error deleting complaint. Check console for details.', 'error');
            });
    }
  
    /**
     * Apply filters to the complaints list
     */
    function applyFilters() {
        // Get filter values
        const issueValue = issueFilter.value;
        const priorityValue = prioritySort.value;
        const statusValue = statusFilter.value;
        
        // Create payload
        const payload = {
            issueType: issueValue,
            priority: priorityValue === 'high-to-low' ? 'high' : (priorityValue === 'low-to-high' ? 'low' : 'all'),
            status: statusValue
        };
        
        // Send request
        fetch(`${ROOT}/public/WorkerComplaint/filter`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(payload)
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`Server responded with status: ${response.status}`);
                }
                return response.text().then(text => {
                    console.log('Raw response from filter:', text);
                    try {
                        return JSON.parse(text);
                    } catch (e) {
                        console.error('Failed to parse JSON response:', e);
                        throw new Error('Invalid JSON response from server');
                    }
                });
            })
            .then(data => {
                if (data.success) {
                    // Clear current list
                    complaintsList.innerHTML = '';
                    
                    // Check if there are complaints
                    if (data.complaints.length === 0) {
                        complaintsList.innerHTML = `
                            <div class="empty-state">
                                <p>No complaints found.</p>
                            </div>
                        `;
                        return;
                    }
                    
                    // Add complaints to list
                    data.complaints.forEach(complaint => {
                        const complaintItem = document.createElement('div');
                        complaintItem.className = 'complaint-item';
                        complaintItem.dataset.complaintId = complaint.complaintID;
                        complaintItem.dataset.workerId = complaint.workerID;
                        
                        // Check if this is the currently selected complaint
                        if (currentComplaintId && complaint.complaintID === currentComplaintId) {
                            complaintItem.classList.add('active');
                        }
                        
                        complaintItem.innerHTML = `
                            <div class="complaint-header">
                                <span class="worker-id">Worker #${complaint.workerID}</span>
                                <span class="complaint-date">${formatDate(complaint.submitted_at)}</span>
                            </div>
                            <div class="complaint-issue">
                                ${complaint.issue}
                                <span class="priority-badge priority-${complaint.priority.toLowerCase()}">
                                    ${complaint.priority}
                                </span>
                            </div>
                            <div class="complaint-preview">
                                ${complaint.description.length > 60 ? complaint.description.substring(0, 60) + '...' : complaint.description}
                            </div>
                            <span class="status-badge status-${complaint.status.toLowerCase().replace(' ', '-')}">
                                ${complaint.status}
                            </span>
                        `;
                        
                        complaintsList.appendChild(complaintItem);
                    });
                } else {
                    showToast(data.message || 'Failed to apply filters', 'error');
                }
            })
            .catch(error => {
                console.error('Error applying filters:', error);
                showToast('Error applying filters. Check console for details.', 'error');
            });
    }
  
    /**
     * Format date for display
     * @param {string} dateStr - The date string to format
     * @returns {string} - The formatted date
     */
    function formatDate(dateStr) {
        const date = new Date(dateStr);
        return date.toLocaleString('en-US', {
            month: 'short',
            day: 'numeric',
            year: 'numeric',
            hour: 'numeric',
            minute: '2-digit',
            hour12: true
        });
    }
  
    /**
     * Show a toast notification
     * @param {string} message - The message to show
     * @param {string} type - The type of toast (success, error, info)
     */
    function showToast(message, type = 'info') {
        // Check if toast container exists
        let toastContainer = document.querySelector('.toast-container');
        
        if (!toastContainer) {
            // Create toast container
            toastContainer = document.createElement('div');
            toastContainer.className = 'toast-container';
            document.body.appendChild(toastContainer);
        }
        
        // Create toast
        const toast = document.createElement('div');
        toast.className = `toast toast-${type}`;
        toast.textContent = message;
        
        // Add toast to container
        toastContainer.appendChild(toast);
        
        // Show toast
        setTimeout(() => {
            toast.classList.add('show');
        }, 10);
        
        // Remove toast after 3 seconds
        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => {
                toast.remove();
            }, 300);
        }, 3000);
    }
  
    // Add CSS for toast notifications if not already added
    if (!document.querySelector('#toast-styles')) {
        const style = document.createElement('style');
        style.id = 'toast-styles';
        style.textContent = `
            .toast-container {
                position: fixed;
                top: 20px;
                right: 20px;
                z-index: 9999;
            }
            
            .toast {
                background-color: #333;
                color: white;
                padding: 12px 20px;
                border-radius: 4px;
                margin-bottom: 10px;
                opacity: 0;
                transform: translateX(100%);
                transition: all 0.3s ease;
                box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            }
            
            .toast.show {
                opacity: 1;
                transform: translateX(0);
            }
            
            .toast-success {
                background-color: #4CAF50;
            }
            
            .toast-error {
                background-color: #F44336;
            }
            
            .toast-info {
                background-color: #2196F3;
            }
            
            .loading-spinner {
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100px;
            }
            
            .loading-spinner div {
                width: 12px;
                height: 12px;
                background-color: #3498db;
                border-radius: 50%;
                margin: 0 5px;
                animation: bounce 1.4s infinite ease-in-out both;
            }
            
            .loading-spinner div:nth-child(1) {
                animation-delay: -0.32s;
            }
            
            .loading-spinner div:nth-child(2) {
                animation-delay: -0.16s;
            }
            
            @keyframes bounce {
                0%, 80%, 100% {
                    transform: scale(0);
                } 40% {
                    transform: scale(1);
                }
            }
        `;
        document.head.appendChild(style);
    }
  
    // Check if there's a complaint ID in the URL
    const urlParams = new URLSearchParams(window.location.search);
    const complaintIdParam = urlParams.get('id');
    
    if (complaintIdParam) {
        // Find the complaint item
        const complaintItem = document.querySelector(`.complaint-item[data-complaint-id="${complaintIdParam}"]`);
        if (complaintItem) {
            // Simulate click
            complaintItem.click();
            
            // Scroll to the complaint item
            complaintItem.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    }
});
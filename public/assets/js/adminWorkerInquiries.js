document.addEventListener('DOMContentLoaded', function() {
  // Global variables
  let currentComplaintId = null;
  let currentCustomerId = null;
  const chatMessages = document.getElementById('chat-messages');
  const chatForm = document.getElementById('chat-form');
  const messageInput = document.getElementById('message-input');
  const chatInterface = document.getElementById('chat-interface');
  const noChat = document.getElementById('no-chat-selected');
  const complaintsList = document.getElementById('complaints-list');
  
  // Modal elements
  const resolveModal = document.getElementById('resolveModal');
  const deleteModal = document.getElementById('deleteModal');
  const resolveButton = document.getElementById('resolveButton');
  const deleteButton = document.getElementById('deleteButton');
  const confirmResolve = document.getElementById('confirmResolve');
  const cancelResolve = document.getElementById('cancelResolve');
  const confirmDelete = document.getElementById('confirmDelete');
  const cancelDelete = document.getElementById('cancelDelete');
  
  // Filter and sort elements
  const issueFilter = document.getElementById('issueFilter');
  const prioritySort = document.getElementById('prioritySort');
  const statusFilter = document.getElementById('statusFilter');
  
  // Debug logging function
  function logDebug(message, data) {
      console.log(`DEBUG: ${message}`, data || '');
  }
  
  logDebug('DOM Content Loaded');
  
  // Add event listeners to complaint items
  function addComplaintItemListeners() {
      const complaintItems = document.querySelectorAll('.complaint-item');
      logDebug(`Found ${complaintItems.length} complaint items`);
      
      complaintItems.forEach(item => {
          item.addEventListener('click', function() {
              logDebug('Complaint item clicked', this.dataset);
              
              // Remove active class from all items
              complaintItems.forEach(i => i.classList.remove('active'));
              
              // Add active class to clicked item
              this.classList.add('active');
              
              // Get complaint ID and customer ID
              currentComplaintId = this.dataset.complaintId;
              currentCustomerId = this.dataset.customerId;
              
              logDebug(`Selected complaint ID: ${currentComplaintId}, customer ID: ${currentCustomerId}`);
              
              // Load complaint details and chat history
              loadComplaintDetails(currentComplaintId);
              
              // Show chat interface, hide no-chat message
              noChat.style.display = 'none';
              chatInterface.style.display = 'flex';
          });
      });
  }
  
  // Load complaint details
  function loadComplaintDetails(complaintId) {
      logDebug(`Loading details for complaint ID: ${complaintId}`);
      
      // First fetch the complaint details directly from the DOM for immediate display
      const selectedComplaint = document.querySelector(`.complaint-item[data-complaint-id="${complaintId}"]`);
      if (selectedComplaint) {
          logDebug('Found complaint item in DOM', selectedComplaint);
          
          // Extract basic information from the selected complaint item
          const customerIdText = selectedComplaint.querySelector('.customer-id').textContent;
          const issueText = selectedComplaint.querySelector('.complaint-issue').textContent.trim();
          const priorityBadge = selectedComplaint.querySelector('.priority-badge');
          const priority = priorityBadge ? priorityBadge.textContent.trim() : '';
          const status = selectedComplaint.querySelector('.status-badge').textContent.trim();
          const description = selectedComplaint.querySelector('.complaint-preview').textContent.trim();
          
          // Update customer info in header
          document.getElementById('customer-name').textContent = customerIdText;
          document.getElementById('customer-id').textContent = `Customer ID: ${currentCustomerId}`;
          document.getElementById('issue-type').textContent = `Issue Type: ${issueText.replace(priority, '').trim()}`;
          
          // Update priority badge
          const headerPriorityBadge = document.getElementById('issue-priority');
          headerPriorityBadge.textContent = priority;
          headerPriorityBadge.className = `priority-badge priority-${priority.toLowerCase()}`;
          
          // Update resolve button based on status
          if (status === 'Resolved') {
              resolveButton.textContent = 'Resolved';
              resolveButton.disabled = true;
              resolveButton.classList.add('disabled');
          } else {
              resolveButton.textContent = 'Mark as Resolved';
              resolveButton.disabled = false;
              resolveButton.classList.remove('disabled');
          }
          
          // Display initial complaint
          displayInitialComplaint({
              customerID: currentCustomerId,
              description: description,
              status: status,
              issue: issueText.replace(priority, '').trim(),
              priority: priority,
              submitted_at: new Date().toISOString() // This is a placeholder; we should get the actual date from the server
          });
      }
      
      // Now fetch the complete details from the server
      fetch(`${ROOT}/admin/complaints/details/${complaintId}`)
          .then(response => {
              logDebug('Complaint details response status:', response.status);
              return response.json();
          })
          .then(data => {
              logDebug('Complaint details response data:', data);
              
              if (data.success) {
                  // Update customer info with more complete data from the server
                  document.getElementById('customer-name').textContent = data.customerName || `Customer #${data.complaint.customerID}`;
                  document.getElementById('customer-id').textContent = `Customer ID: ${data.complaint.customerID}`;
                  document.getElementById('issue-type').textContent = `Issue Type: ${data.complaint.issue}`;
                  
                  const priorityBadge = document.getElementById('issue-priority');
                  priorityBadge.textContent = data.complaint.priority;
                  priorityBadge.className = `priority-badge priority-${data.complaint.priority.toLowerCase()}`;
                  
                  // Redisplay initial complaint with complete data
                  displayInitialComplaint(data.complaint);
                  
                  // Load chat history
                  loadChatHistory(complaintId);
              } else {
                  console.error('Failed to load complaint details');
              }
          })
          .catch(error => {
              console.error('Error loading complaint details:', error);
              // Even if the API call fails, still try to load chat history
              loadChatHistory(complaintId);
          });
  }
  
  // Display the initial complaint message
  function displayInitialComplaint(complaint) {
      logDebug('Displaying initial complaint', complaint);
      
      chatMessages.innerHTML = ''; // Clear previous messages
      
      // Create and append the initial complaint message
      const complaintMessage = document.createElement('div');
      complaintMessage.className = 'message customer-message';
      complaintMessage.innerHTML = `
          <div class="message-header">
              <span class="sender">Customer #${complaint.customerID}</span>
              <span class="timestamp">${formatDate(complaint.submitted_at)}</span>
          </div>
          <div class="message-content">
              <p>${complaint.description}</p>
          </div>
      `;
      chatMessages.appendChild(complaintMessage);
  }
  
  // Load chat history from updates table
  function loadChatHistory(complaintId) {
      logDebug(`Loading chat history for complaint ID: ${complaintId}`);
      
      fetch(`${ROOT}/admin/complaints/chat/${complaintId}`)
          .then(response => {
              logDebug('Chat history response status:', response.status);
              return response.json();
          })
          .then(data => {
              logDebug('Chat history response data:', data);
              
              // Add all chat updates (don't clear previous messages, as initial complaint is already displayed)
              if (data.updates && data.updates.length > 0) {
                  data.updates.forEach(update => {
                      const messageDiv = document.createElement('div');
                      // Determine if message is from admin or customer
                      // Check if isAdmin property exists, otherwise look for an adminID or similar field
                      const isAdmin = update.isAdmin || update.adminID || false;
                      
                      messageDiv.className = isAdmin ? 'message admin-message' : 'message customer-message';
                      messageDiv.innerHTML = `
                          <div class="message-header">
                              <span class="sender">${isAdmin ? 'Admin' : `Customer #${currentCustomerId}`}</span>
                              <span class="timestamp">${formatDate(update.updated_at)}</span>
                          </div>
                          <div class="message-content">
                              <p>${update.comments}</p>
                          </div>
                          ${update.status ? `<div class="status-update">Status updated to: <span class="status-badge status-${update.status.toLowerCase().replace(' ', '-')}">${update.status}</span></div>` : ''}
                      `;
                      chatMessages.appendChild(messageDiv);
                  });
              } else {
                  logDebug('No chat updates found');
              }
              
              // Scroll to bottom of chat
              chatMessages.scrollTop = chatMessages.scrollHeight;
          })
          .catch(error => {
              console.error('Error loading chat history:', error);
              // Add a message explaining that chat history couldn't be loaded
              const errorMessage = document.createElement('div');
              errorMessage.className = 'system-message';
              errorMessage.innerHTML = `
                  <p>Couldn't load message history. Please try again later.</p>
              `;
              chatMessages.appendChild(errorMessage);
          });
  }
  
  // Format date to readable format
  function formatDate(dateString) {
      try {
          const date = new Date(dateString);
          return date.toLocaleDateString('en-US', { 
              month: 'short', 
              day: 'numeric', 
              year: 'numeric',
              hour: '2-digit',
              minute: '2-digit'
          });
      } catch (e) {
          console.error('Error formatting date:', e);
          return 'Unknown date';
      }
  }
  
  // Submit new message
  if (chatForm) {
      chatForm.addEventListener('submit', function(e) {
          e.preventDefault();
          
          if (!currentComplaintId) {
              alert('Please select a complaint first');
              return;
          }
          
          const message = messageInput.value.trim();
          if (message === '') return;
          
          logDebug(`Sending message for complaint ID: ${currentComplaintId}`, message);
          
          // Create new message object
          const newMessage = {
              complaintID: currentComplaintId,
              comments: message,
              status: 'In Progress' // Default status for new admin messages
          };
          
          // Add admin message to chat immediately for better UX
          const adminMessage = document.createElement('div');
          adminMessage.className = 'message admin-message';
          adminMessage.innerHTML = `
              <div class="message-header">
                  <span class="sender">Admin</span>
                  <span class="timestamp">${formatDate(new Date())}</span>
              </div>
              <div class="message-content">
                  <p>${message}</p>
              </div>
              <div class="status-update">Status updated to: <span class="status-badge status-in-progress">In Progress</span></div>
          `;
          chatMessages.appendChild(adminMessage);
          
          // Scroll to bottom of chat
          chatMessages.scrollTop = chatMessages.scrollHeight;
          
          // Clear input field
          messageInput.value = '';
          
          // Update complaint item status in the list
          updateComplaintItemStatus(currentComplaintId, 'In Progress');
          
          // Send message to server
          fetch(`${ROOT}/admin/complaints/respond`, {
              method: 'POST',
              headers: {
                  'Content-Type': 'application/json',
                  'X-Requested-With': 'XMLHttpRequest'
              },
              body: JSON.stringify(newMessage)
          })
          .then(response => {
              logDebug('Send message response status:', response.status);
              return response.json();
          })
          .then(data => {
              logDebug('Send message response data:', data);
              
              if (!data.success) {
                  alert('Failed to send message. Please try again.');
              }
          })
          .catch(error => {
              console.error('Error sending message:', error);
              alert('Failed to send message. Please try again.');
          });
      });
  }
  
  // Handle resolve button click
  if (resolveButton) {
      resolveButton.addEventListener('click', function() {
          if (!currentComplaintId) return;
          
          logDebug('Resolve button clicked');
          
          // Show resolve confirmation modal
          resolveModal.style.display = 'block';
      });
  }
  
  // Handle delete button click
  if (deleteButton) {
      deleteButton.addEventListener('click', function() {
          if (!currentComplaintId) return;
          
          logDebug('Delete button clicked');
          
          // Show delete confirmation modal
          deleteModal.style.display = 'block';
      });
  }
  
  // Modal confirm and cancel buttons
  if (confirmResolve) {
      confirmResolve.addEventListener('click', function() {
          logDebug('Confirm resolve clicked');
          
          // Create resolve message object
          const resolveMessage = {
              complaintID: currentComplaintId,
              comments: "This complaint has been marked as resolved.",
              status: 'Resolved'
          };
          
          // Update UI immediately
          resolveButton.textContent = 'Resolved';
          resolveButton.disabled = true;
          resolveButton.classList.add('disabled');
          
          // Update complaint status in the list
          updateComplaintItemStatus(currentComplaintId, 'Resolved');
          
          // Add status update message to chat
          const statusUpdate = document.createElement('div');
          statusUpdate.className = 'message admin-message';
          statusUpdate.innerHTML = `
              <div class="message-header">
                  <span class="sender">Admin</span>
                  <span class="timestamp">${formatDate(new Date())}</span>
              </div>
              <div class="message-content">
                  <p>This complaint has been marked as resolved.</p>
              </div>
              <div class="status-update">Status updated to: <span class="status-badge status-resolved">Resolved</span></div>
          `;
          chatMessages.appendChild(statusUpdate);
          
          // Scroll to bottom of chat
          chatMessages.scrollTop = chatMessages.scrollHeight;
          
          // Close modal
          resolveModal.style.display = 'none';
          
          // Send resolve request to server
          fetch(`${ROOT}/admin/complaints/resolve/${currentComplaintId}`, {
              method: 'POST',
              headers: {
                  'Content-Type': 'application/json',
                  'X-Requested-With': 'XMLHttpRequest'
              },
              body: JSON.stringify(resolveMessage)
          })
          .then(response => {
              logDebug('Resolve complaint response status:', response.status);
              return response.json();
          })
          .then(data => {
              logDebug('Resolve complaint response data:', data);
              
              if (!data.success) {
                  alert('Failed to resolve complaint on the server. The UI has been updated, but the change may not be saved.');
              }
          })
          .catch(error => {
              console.error('Error resolving complaint:', error);
              alert('Failed to resolve complaint on the server. The UI has been updated, but the change may not be saved.');
          });
      });
  }
  
  if (cancelResolve) {
      cancelResolve.addEventListener('click', function() {
          resolveModal.style.display = 'none';
      });
  }
  
  if (confirmDelete) {
      confirmDelete.addEventListener('click', function() {
          logDebug('Confirm delete clicked');
          
          // Close modal
          deleteModal.style.display = 'none';
          
          // Send delete request to server
          fetch(`${ROOT}/admin/complaints/delete/${currentComplaintId}`, {
              method: 'POST',
              headers: {
                  'Content-Type': 'application/json',
                  'X-Requested-With': 'XMLHttpRequest'
              }
          })
          .then(response => {
              logDebug('Delete complaint response status:', response.status);
              return response.json();
          })
          .then(data => {
              logDebug('Delete complaint response data:', data);
              
              if (data.success) {
                  // Remove complaint from list
                  const complaintItem = document.querySelector(`.complaint-item[data-complaint-id="${currentComplaintId}"]`);
                  if (complaintItem) {
                      complaintItem.remove();
                  }
                  
                  // Reset current complaint
                  currentComplaintId = null;
                  currentCustomerId = null;
                  
                  // Hide chat interface, show no-chat message
                  chatInterface.style.display = 'none';
                  noChat.style.display = 'flex';
              } else {
                  alert('Failed to delete complaint. Please try again.');
              }
          })
          .catch(error => {
              console.error('Error deleting complaint:', error);
              alert('Failed to delete complaint. Please try again.');
          });
      });
  }
  
  if (cancelDelete) {
      cancelDelete.addEventListener('click', function() {
          deleteModal.style.display = 'none';
      });
  }
  
  // Update complaint item status in the list
  function updateComplaintItemStatus(complaintId, status) {
      const complaintItem = document.querySelector(`.complaint-item[data-complaint-id="${complaintId}"]`);
      if (complaintItem) {
          const statusBadge = complaintItem.querySelector('.status-badge');
          if (statusBadge) {
              statusBadge.className = `status-badge status-${status.toLowerCase().replace(' ', '-')}`;
              statusBadge.textContent = status;
          }
      }
  }
  
  // Filter and sort functionality
  function applyFiltersAndSort() {
      const issueType = issueFilter.value;
      const priority = prioritySort.value;
      const status = statusFilter.value;
      
      logDebug('Applying filters', { issueType, priority, status });
      
      fetch(`${ROOT}/admin/complaints/filter`, {
          method: 'POST',
          headers: {
              'Content-Type': 'application/json',
              'X-Requested-With': 'XMLHttpRequest'
          },
          body: JSON.stringify({
              issueType: issueType,
              priority: priority,
              status: status
          })
      })
      .then(response => {
          logDebug('Filter complaints response status:', response.status);
          return response.json();
      })
      .then(data => {
          logDebug('Filter complaints response data:', data);
          
          if (data.success) {
              // Update complaints list
              complaintsList.innerHTML = '';
              
              if (data.complaints.length > 0) {
                  data.complaints.forEach(complaint => {
                      const complaintItem = document.createElement('div');
                      complaintItem.className = 'complaint-item';
                      complaintItem.dataset.complaintId = complaint.complaintID;
                      complaintItem.dataset.customerId = complaint.customerID;
                      
                      complaintItem.innerHTML = `
                          <div class="complaint-header">
                              <span class="customer-id">Customer #${complaint.customerID}</span>
                              <span class="complaint-date">${formatDate(complaint.submitted_at).split(',')[0]}</span>
                          </div>
                          <div class="complaint-issue">
                              ${complaint.issue}
                              <span class="priority-badge priority-${complaint.priority.toLowerCase()}">
                                  ${complaint.priority}
                              </span>
                          </div>
                          <div class="complaint-preview">
                              ${complaint.description.substring(0, 60)}${complaint.description.length > 60 ? '...' : ''}
                          </div>
                          <span class="status-badge status-${complaint.status.toLowerCase().replace(' ', '-')}">
                              ${complaint.status}
                          </span>
                      `;
                      
                      complaintsList.appendChild(complaintItem);
                  });
                  
                  // Add event listeners to new complaint items
                  addComplaintItemListeners();
              } else {
                  complaintsList.innerHTML = `
                      <div class="empty-state">
                          <p>No complaints found.</p>
                      </div>
                  `;
              }
          } else {
              console.error('Failed to filter complaints');
          }
      })
      .catch(error => {
          console.error('Error filtering complaints:', error);
      });
  }
  
  // Add event listeners to filter and sort controls
  if (issueFilter) {
      issueFilter.addEventListener('change', applyFiltersAndSort);
  }
  
  if (prioritySort) {
      prioritySort.addEventListener('change', applyFiltersAndSort);
  }
  
  if (statusFilter) {
      statusFilter.addEventListener('change', applyFiltersAndSort);
  }
  
  // Close modals when clicking outside
  window.addEventListener('click', function(event) {
      if (event.target === resolveModal) {
          resolveModal.style.display = 'none';
      }
      if (event.target === deleteModal) {
          deleteModal.style.display = 'none';
      }
  });
  
  // Initialize page
  addComplaintItemListeners();
  
  // Add a fallback strategy if API endpoints aren't working yet
  // This makes the interface work even without the backend API endpoints
  const complaintItems = document.querySelectorAll('.complaint-item');
  if (complaintItems.length > 0) {
      logDebug('Adding fallback click handler to first complaint');
      // Add a special handler for development/testing
      complaintItems[0].addEventListener('dblclick', function() {
          logDebug('Fallback: Double-clicked first complaint');
          
          // Force display of chat interface with mock data
          noChat.style.display = 'none';
          chatInterface.style.display = 'flex';
          
          // Extract data from DOM
          const customerIdText = this.querySelector('.customer-id').textContent;
          const issueText = this.querySelector('.complaint-issue').textContent.trim();
          const priorityBadge = this.querySelector('.priority-badge');
          const priority = priorityBadge ? priorityBadge.textContent.trim() : '';
          const status = this.querySelector('.status-badge').textContent.trim();
          const description = this.querySelector('.complaint-preview').textContent.trim();
          
          // Update header info
          document.getElementById('customer-name').textContent = customerIdText;
          document.getElementById('customer-id').textContent = customerIdText;
          document.getElementById('issue-type').textContent = `Issue Type: ${issueText.replace(priority, '').trim()}`;
          
          const headerPriorityBadge = document.getElementById('issue-priority');
          headerPriorityBadge.textContent = priority;
          headerPriorityBadge.className = `priority-badge priority-${priority.toLowerCase()}`;
          
          // Display chat messages
          chatMessages.innerHTML = '';
          
          // Add initial complaint
          const complaintMessage = document.createElement('div');
          complaintMessage.className = 'message customer-message';
          complaintMessage.innerHTML = `
              <div class="message-header">
                  <span class="sender">${customerIdText}</span>
                  <span class="timestamp">${formatDate(new Date())}</span>
              </div>
              <div class="message-content">
                  <p>${description}</p>
              </div>
          `;
          chatMessages.appendChild(complaintMessage);
          
          // Add a mock response
          const mockResponse = document.createElement('div');
          mockResponse.className = 'message admin-message';
          mockResponse.innerHTML = `
              <div class="message-header">
                  <span class="sender">Admin</span>
                  <span class="timestamp">${formatDate(new Date())}</span>
              </div>
              <div class="message-content">
                  <p>Thank you for your complaint. We are looking into this issue.</p>
              </div>
              <div class="status-update">Status updated to: <span class="status-badge status-in-progress">In Progress</span></div>
          `;
          chatMessages.appendChild(mockResponse);
      });
  }
});
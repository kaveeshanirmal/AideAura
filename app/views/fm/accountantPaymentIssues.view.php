<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Worker Inquiries</title>
    <link rel="stylesheet" href="<?=ROOT?>/public/assets/css/adminpaymentIssues.css">
</head>
<body>
    <div class="dashboard-container">
        <?php include(ROOT_PATH . '/app/views/components/accountant_navbar.view.php'); ?> 
    <div class="container">
        <div class="main-content">
            <!-- Navbar Component -->
            <div class="dashboard-container">                
                <!-- Tabs Section -->
                <div class="tabs-container">
                    <button class="tab-button active" data-tab="unreplied">Unreplied</button>
                    <button class="tab-button" data-tab="replied">Replied</button>
                </div>

                <!-- Inquiries List -->
                <div class="inquiries-container">
                    <div class="inquiry-card">
                        <div class="inquiry-header">
                            <div class="user-info">
                                <img src="<?=ROOT?>/public/assets/images/user_icon.png" alt="Profile" class="profile-image">
                                <div class="user-details">
                                    <h3 class="sender" >MR. Kamal Rupasinghe</h3>
                                    <div class="timestamp">
                                        <span>24 September 2024</span>
                                        <span class="time">20:34 pm</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="inquiry-content">
                            <h4> Payment Method : </h4>
                            <p class="message"> Paypal </p>
                            <h4>Transaction ID : </h4>
                            <p class="message"> 12589 </p>
                            <h4>Issue : </h4>
                            <p class="message">I was face some threatened from my current employee. I think my life is in a danger. Please can you get immidiate actions for this situation.</p>
                            
                            <a href="#" class="reply-link">reply</a>
                        </div>
                    </div>

                <!-- Inquiries List -->
                <div class="inquiries-container">
                    <div class="inquiry-card">
                        <div class="inquiry-header">
                            <div class="user-info">
                                <img src="<?=ROOT?>/public/assets/images/user_icon.png" alt="Profile" class="profile-image">
                                <div class="user-details">
                                    <h3 class="sender">MR. Kamal Rupasinghe</h3>
                                    <div class="timestamp">
                                        <span>24 September 2024</span>
                                        <span class="time">20:34 pm</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="inquiry-content">
                            <h4> Payment Method : </h4>
                            <p class="message"> Paypal </p>
                            <h4>Transaction ID : </h4>
                            <p class="message"> 12589 </p>
                            <h4>Issue : </h4>
                            <p class="message">I was face some threatened from my current employee. I think my life is in a danger. Please can you get immidiate actions for this situation.</p>
                            
                            <a href="#" class="reply-link">reply</a>
                        </div>
                    </div>

                    <!-- Repeat similar inquiry-card structure for other messages -->
                    <!-- You can dynamically generate these cards from your database -->
                </div>
            </div>
        </div>
   


    <!-- Reply Modal -->
    <div id="replyModal" class="reply-modal">
        <div class="reply-modal-content">
            <div class="reply-modal-header">
                <h3>Reply to Inquiry</h3>
                <button class="close-reply-modal">&times;</button>
            </div>
            <div class="original-inquiry">
                <h4>Name of the Customer</h4>
                <p class="original-message"></p>
            </div>
            <form id="replyForm" class="reply-form">
                <input type="hidden" id="inquiryId" name="inquiryId">
                <div class="form-group">
                    <label for="replyMessage">Your Response:</label>
                    <textarea id="replyMessage" name="replyMessage" rows="6" placeholder="Write your response here..." required></textarea>
                </div>
                <div class="form-actions">
                    <button type="button" class="btn-cancel close-reply-modal">Cancel</button>
                    <button type="submit" class="btn-send">Send Reply</button>
                </div>
            </form>
        </div>
    </div>
    </div>

    <script>
        // Reply Modal Functionality
        document.addEventListener('DOMContentLoaded', () => {
            const replyModal = document.getElementById('replyModal');
            const replyLinks = document.querySelectorAll('.reply-link');
            const closeModalButtons = document.querySelectorAll('.close-reply-modal');
            const originalMessage = document.querySelector('.original-message');
            const inquiryIdInput = document.getElementById('inquiryId');
            const replyForm = document.getElementById('replyForm');

            // Open Reply Modal
            replyLinks.forEach(link => {
                link.addEventListener('click', (e) => {
                    e.preventDefault();
                    const inquiryCard = link.closest('.inquiry-card');
                    const messageElement = inquiryCard.querySelector('.sender');
                    const inquiryId = link.getAttribute('data-inquiry-id');

                    // Populate original message
                    originalMessage.textContent = messageElement.textContent;
                    inquiryIdInput.value = inquiryId;

                    // Show modal
                    replyModal.style.display = 'block';
                });
            });

            // Close Modal
            closeModalButtons.forEach(button => {
                button.addEventListener('click', () => {
                    replyModal.style.display = 'none';
                    replyForm.reset();
                });
            });

            // Handle Form Submission
            replyForm.addEventListener('submit', (e) => {
                e.preventDefault();
                const formData = new FormData(replyForm);
                
                // Here you would typically send the form data to your server
                fetch('<?=ROOT?>/public/adminWorkerInquiries/reply', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(result => {
                    if (result.success) {
                        alert('Reply sent successfully');
                        replyModal.style.display = 'none';
                        replyForm.reset();
                        // Optionally update the UI to mark inquiry as replied
                    } else {
                        alert('Failed to send reply');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred');
                });
            });

            // Close modal if clicking outside
            window.addEventListener('click', (e) => {
                if (e.target === replyModal) {
                    replyModal.style.display = 'none';
                    replyForm.reset();
                }
            });
        });
    </script>

    <script>
        // Tab switching functionality
        document.querySelectorAll('.tab-button').forEach(button => {
            button.addEventListener('click', () => {
                // Remove active class from all buttons
                document.querySelectorAll('.tab-button').forEach(btn => {
                    btn.classList.remove('active');
                });
                // Add active class to clicked button
                button.classList.add('active');
                
                // Handle tab content switching here
                const tabName = button.getAttribute('data-tab');
                // Add your logic to show/hide appropriate inquiries
            });
        });
    </script>
</body>
</html>
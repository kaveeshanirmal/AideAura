<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HR Dashboard - Worker Inquiries</title>
    <link rel="stylesheet" href="<?=ROOT?>/public/assets/css/hrWorkerInquiries.css">
</head>
<body>
    <div class="container">
        <!-- Navbar Component -->
        <?php include(ROOT_PATH . '/app/views/components/employeeNavbar.view.php'); ?>
        <div class="main-content">
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
                                    <h3>MR. Kamal Rupasinghe</h3>
                                    <div class="timestamp">
                                        <span>24 September 2024</span>
                                        <span class="time">20:34 pm</span>
                                        
                                    </div>
                                    <div class="timestamp">
                                        <span>WorkerID : 221048</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="inquiry-content">
                            <p class="salutation">Sir,</p>
                            <p class="message">I was face some threatened from my current employer. I think my life is in a danger. Please can you get immidiate actions for this situation.</p>
                            <p class="signature">Thank you</p>
                            <a href="#" class="reply-link">reply</a>
                        </div>
                    </div>
                    </div>

                               <!-- Inquiries List -->
                <div class="inquiries-container">
                    <div class="inquiry-card">
                        <div class="inquiry-header">
                            <div class="user-info">
                                <img src="<?=ROOT?>/public/assets/images/user_icon.png" alt="Profile" class="profile-image">
                                <div class="user-details">
                                    <h3>MR. Kamal Rupasinghe</h3>
                                    <div class="timestamp">
                                        <span>24 September 2024</span>
                                        <span class="time">20:34 pm</span>
                                        
                                    </div>
                                    <div class="timestamp">
                                        <span>WorkerID : 221048</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="inquiry-content">
                            <p class="salutation">Sir,</p>
                            <p class="message">I was face some threatened from my current employer. I think my life is in a danger. Please can you get immidiate actions for this situation.</p>
                            <p class="signature">Thank you</p>
                            <a href="#" class="reply-link">reply</a>
                        </div>
                    </div>
                    </div>

                               <!-- Inquiries List -->
                <div class="inquiries-container">
                    <div class="inquiry-card">
                        <div class="inquiry-header">
                            <div class="user-info">
                                <img src="<?=ROOT?>/public/assets/images/user_icon.png" alt="Profile" class="profile-image">
                                <div class="user-details">
                                    <h3>MR. Kamal Rupasinghe</h3>
                                    <div class="timestamp">
                                        <span>24 September 2024</span>
                                        <span class="time">20:34 pm</span>
                                        
                                    </div>
                                    <div class="timestamp">
                                        <span>WorkerID : 221048</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="inquiry-content">
                            <p class="salutation">Sir,</p>
                            <p class="message">I was face some threatened from my current employer. I think my life is in a danger. Please can you get immidiate actions for this situation.</p>
                            <p class="signature">Thank you</p>
                            <a href="#" class="reply-link">reply</a>
                        </div>
                    </div>
                    </div>

                               <!-- Inquiries List -->
                <div class="inquiries-container">
                    <div class="inquiry-card">
                        <div class="inquiry-header">
                            <div class="user-info">
                                <img src="<?=ROOT?>/public/assets/images/user_icon.png" alt="Profile" class="profile-image">
                                <div class="user-details">
                                    <h3>MR. Kamal Rupasinghe</h3>
                                    <div class="timestamp">
                                        <span>24 September 2024</span>
                                        <span class="time">20:34 pm</span>
                                        
                                    </div>
                                    <div class="timestamp">
                                        <span>WorkerID : 221048</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="inquiry-content">
                            <p class="salutation">Sir,</p>
                            <p class="message">I was face some threatened from my current employer. I think my life is in a danger. Please can you get immidiate actions for this situation.</p>
                            <p class="signature">Thank you</p>
                            <a href="#" class="reply-link">reply</a>
                        </div>
                    </div>
                    </div>

                               <!-- Inquiries List -->
                <div class="inquiries-container">
                    <div class="inquiry-card">
                        <div class="inquiry-header">
                            <div class="user-info">
                                <img src="<?=ROOT?>/public/assets/images/user_icon.png" alt="Profile" class="profile-image">
                                <div class="user-details">
                                    <h3>MR. Kamal Rupasinghe</h3>
                                    <div class="timestamp">
                                        <span>24 September 2024</span>
                                        <span class="time">20:34 pm</span>
                                        
                                    </div>
                                    <div class="timestamp">
                                        <span>WorkerID : 221048</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="inquiry-content">
                            <p class="salutation">Sir,</p>
                            <p class="message">I was face some threatened from my current employer. I think my life is in a danger. Please can you get immidiate actions for this situation.</p>
                            <p class="signature">Thank you</p>
                            <a href="#" class="reply-link">reply</a>
                        </div>
                    </div>
                    </div>

                    


                    <!-- Repeat similar inquiry-card structure for other messages -->
                    <!-- You can dynamically generate these cards from your database -->
                </div>
            </div>
        </div>
    </div>

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
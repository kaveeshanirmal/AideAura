<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>fm - Payment Issues</title>
    <link rel="stylesheet" href="<?=ROOT?>/public/assets/css/accountantPaymentIssues.css">

</head>
<body>
    <div class="dashboard-container">
        <?php include(ROOT_PATH . '/app/views/components/employeeNavbar.view.php'); ?>
        <div class="container">
            <div class="main-content">
                <div class="tabs-container">
                    <button class="tab-button active" data-tab="unreplied">Unreplied</button>
                    <button class="tab-button" data-tab="replied">Replied</button>
                </div>

                <div class="inquiries-container">
                    <div class="inquiry-card">
                        <div class="inquiry-header">
                            <div class="user-info">
                                <img src="<?=ROOT?>/public/assets/images/user_icon.png" alt="Profile" class="profile-image">
                                <div class="user-details">
                                    <h3 class="sender">MR. Kamal Rupasinghe</h3>
                                </div>
                                <div class="timestamp">
                                        <span>24 September 2024</span>
                                        <span class="time">20:34 pm</span>
                                    </div>
                            </div>
                        </div>
                        <div class="issue-preview"></div>
                        <div class="inquiry-content">
                            <h4>Payment Method:</h4>
                            <p class="message">Paypal</p>
                            <h4>Transaction ID:</h4>
                            <p class="message">12589</p>
                            <h4>Issue:</h4>
                            <p class="message">I was face some threatened from my current employee. I think my life is in a danger. Please can you get immidiate actions for this situation.</p>
                            <a href="#" class="reply-link">reply</a>
                        </div>
                    </div>
                    <!-- Additional cards would go here -->
                </div>
            </div>
        </div>
    </div>

    <!-- Keep your existing modal code here -->

    <script>         
     // Card expansion functionality
            document.addEventListener('DOMContentLoaded', () => {
            // Set up issue previews
            document.querySelectorAll('.inquiry-card').forEach(card => {
                const messageElement = card.querySelector('.inquiry-content .message:last-of-type');
                const previewElement = card.querySelector('.issue-preview');
                
                // Get the first sentence of the message
                const fullMessage = messageElement.textContent;
                const firstSentence = fullMessage.split('.')[0] + '...';
                
                // Set the preview text
                previewElement.textContent = firstSentence;

                // Card expansion functionality
                card.addEventListener('click', (e) => {
                    // Don't toggle if clicking reply link
                    if (e.target.classList.contains('reply-link')) {
                        return;
                    }
                    
                    const content = card.querySelector('.inquiry-content');
                    const preview = card.querySelector('.issue-preview');
                    
                    content.classList.toggle('expanded');
                    preview.classList.toggle('hidden');
                });
            });


            // Tab switching functionality
            document.querySelectorAll('.tab-button').forEach(button => {
                button.addEventListener('click', () => {
                    document.querySelectorAll('.tab-button').forEach(btn => {
                        btn.classList.remove('active');
                    });
                    button.classList.add('active');
                    
                    const tabName = button.getAttribute('data-tab');
                    // Add your logic to show/hide appropriate inquiries
                });
            });
        });
    </script>
</body>
</html>
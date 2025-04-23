<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Operational Help Desk | AideAura</title>
        <link rel="stylesheet" href="<?=ROOT?>/public/assets/css/customerHelpDesk.css" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />

        <style>
        body {
            background-image: url('<?=ROOT?>/public/assets/images/booking_bg.jpg');
        }
    </style>

    </head>
    <body>
        <?php include(ROOT_PATH . '/app/views/components/navbar.view.php'); ?>

        <div class="helpdesk-container">
            <h1>Operational Help Desk</h1>

            <!-- Contact Section -->
            <section class="contact-section">
                <h2><i class="fas fa-headset"></i> Contact Us</h2>
                <div class="contact-methods">
                    <div class="contact-item">
                        <i class="fas fa-phone-alt fa-lg"></i>
                        <p>Support Hotline: +94 123 456 789</p>
                    </div>
                    <div class="contact-item">
                        <i class="fas fa-envelope fa-lg"></i>
                        <p>Email: workersupport@AideAura.com</p>
                    </div>
                </div>
            </section>

            <!-- Submit Issue Form -->
            <section class="submit-issue">
                <h2><i class="fas fa-file-alt"></i> Submit an Issue</h2>
                <form class="issue-form" action="<?=ROOT?>/public/workerHelpDesk/submitComplaint" method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="issue-type">Issue Type</label>
                        <select id="issue-type" name="issue" required>
                            <option value="">Select an Issue Type</option>
                            <optgroup label="General Issues">
                                <option value="general-inquiry">General Inquiry</option>
                                <option value="feedback">Feedback/Suggestions</option>
                            </optgroup>
                            <optgroup label="Service Issues">
                                <option value="customer-misconduct">Customer Misconduct</option>
                                <option value="job-assignment-issues">Job Assignment Issues</option>
                                <option value="job-scheduling">Job Scheduling Issues</option>
                            </optgroup>
                            <optgroup label="Job Issues">
                                <option value="unable-to-accept-jobs">Unable to Accept Jobs</option>
                                <option value="incorrect-details">Incorrect Job Details</option>
                                <option value="cancellation">Job Cancellations/Rescheduling</option>
                            </optgroup>
                            <optgroup label="Payment Issues">
                                <option value="failed-payment">Failed Payment</option>
                                <option value="underpaid">Underpaid</option>
                                <option value="payment-request">Payment Request</option>
                                <option value="payment-verification">Payment Verification</option>
                            </optgroup>
                            <optgroup label="Technical Issues">
                                <option value="website-loading">Website Not Loading</option>
                                <option value="bug-report">App/Website Bug</option>
                                <option value="login-issues">Account Login Issues</option>
                                <option value="profile-update">Profile Update Issues</option>
                            </optgroup>
                            <optgroup label="Account Issues">
                                <option value="forgot-password">Forgot Password</option>
                                <option value="deactivation">Account Deactivation</option>
                                <option value="unauthorized-access">Unauthorized Access</option>
                                <option value="role-permission">Role/Permission Issues</option>
                            </optgroup>
                            <optgroup label="Complaint/Feedback">
                                <option value="service-complaint">Support Service Complaint</option>
                                <option value="customer-complaint">Customer Complaint</option>
                                <option value="general-feedback">General Feedback</option>
                            </optgroup>
                            <optgroup label="Help Requests">
                                <option value="service-guidance">Service Guidance</option>
                                <option value="operational-help">Operational Help</option>
                                <option value="policy-clarification">Policy Clarification</option>
                            </optgroup>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="issue-description">Description</label>
                        <textarea
                            id="issue-description"
                            name="description"
                            placeholder="Describe your issue here in detail..."
                            required
                        ></textarea>
                    </div>
                    <button type="submit" class="submit-btn">
                        <i class="fas fa-paper-plane"></i> Submit Issue
                    </button>
                </form>
            </section>

            <!-- Your complaints section -->
            <section class="complaint-status">
                <h2><i class="fas fa-clipboard-list"></i> Your Complaints</h2>
                
                <!-- Status Filter Buttons -->
                <div class="status-filter">
                    <button data-filter="all" class="active">All</button>
                    <button data-filter="Pending">Pending</button>
                    <button data-filter="In Progress">In Progress</button>
                    <button data-filter="Resolved">Resolved</button>
                </div>
                
                <div class="complaint-cards-container">
                    <?php if (!empty($complaints)): ?>
                        <?php foreach ($complaints as $complaint): ?>
                            <div class="complaint-card" data-status="<?= htmlspecialchars($complaint->status) ?>">
                                <h3>Complaint #<?= htmlspecialchars($complaint->complaintID) ?></h3>
                                <p><strong>Issue:</strong> <?= htmlspecialchars($complaint->issue) ?></p>
                                <p><strong>Submitted:</strong> <?= date('F j, Y', strtotime($complaint->submitted_at)) ?></p>
                                
                                <?php if ($complaint->status === 'Pending'): ?>
                                    <p class="pending-message">
                                        <i class="fas fa-clock"></i> Your complaint has been received and will be processed soon.
                                    </p>
                                    <button class="view-solution-btn" 
                                            data-complaint-id="<?= htmlspecialchars($complaint->complaintID) ?>"
                                            onclick="toggleSolution('<?= htmlspecialchars($complaint->complaintID) ?>')">
                                        <i class="fas fa-eye"></i> View Updates
                                    </button>
                                    <div class="solution" 
                                        id="solution-<?= htmlspecialchars($complaint->complaintID) ?>" 
                                        style="display: none; transition: max-height 0.3s ease, opacity 0.3s ease; max-height: 0; opacity: 0;">
                                        <!-- Conversation will be loaded here via JavaScript -->
                                    </div>
                                <?php elseif ($complaint->status === 'In Progress'): ?>
                                    <p class="pending-message">
                                        <i class="fas fa-hourglass-half"></i> Your complaint is being reviewed by our team.
                                    </p>
                                    
                                    <button class="view-solution-btn" 
                                            data-complaint-id="<?= htmlspecialchars($complaint->complaintID) ?>"
                                            data-status="In Progress"
                                            onclick="toggleSolution('<?= htmlspecialchars($complaint->complaintID) ?>')">
                                        <i class="fas fa-eye"></i> View Updates
                                    </button>
                                    <div class="solution" 
                                        id="solution-<?= htmlspecialchars($complaint->complaintID) ?>" 
                                        style="display: none; transition: max-height 0.3s ease, opacity 0.3s ease; max-height: 0; opacity: 0;">
                                        <!-- Conversation will be loaded here via JavaScript -->
                                        <div class="reply-container" style="display: none; margin-top: 15px;">
                                            <button class="reply-btn"
                                                    onclick="openReplyModal('<?= htmlspecialchars($complaint->complaintID) ?>')">
                                                <i class="fas fa-reply"></i> Reply to this complaint
                                            </button>
                                        </div>
                                    </div>
                                <?php else: /* Resolved */ ?>
                                    <p class="resolved-message">
                                        <i class="fas fa-check-circle"></i> This complaint has been resolved.
                                    </p>
                                    <button class="view-solution-btn" 
                                            data-complaint-id="<?= htmlspecialchars($complaint->complaintID) ?>"
                                            onclick="toggleSolution('<?= htmlspecialchars($complaint->complaintID) ?>')">
                                        <i class="fas fa-eye"></i> View Updates
                                    </button>
                                    <div class="solution" 
                                        id="solution-<?= htmlspecialchars($complaint->complaintID) ?>" 
                                        style="display: none; transition: max-height 0.3s ease, opacity 0.3s ease; max-height: 0; opacity: 0;">
                                        <!-- Conversation will be loaded here via JavaScript -->
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="no-complaints">
                            <i class="fas fa-inbox fa-3x"></i>
                            <p>You haven't submitted any complaints yet.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </section>
        </div>

        <?php include(ROOT_PATH . '/app/views/components/footer.view.php'); ?>

        <!-- Overlay Message for Submission Results -->
        <?php if (isset($_SESSION['complaint_message'])): ?>
            <div id="overlay-message" class="overlay hidden">
                <div class="overlay-content">
                    <i class="fas fa-check-circle fa-3x" style="color: var(--success-color); margin-bottom: 15px;"></i>
                    <p id="overlay-text">
                        <?= isset($_SESSION['complaint_message']) ? htmlspecialchars($_SESSION['complaint_message']) : ''; ?>
                    </p>
                    <button id="overlay-close-btn">Okay</button>
                </div>
            </div>
        <?php endif; ?>

        <!-- Reply Modal -->
        <div id="reply-modal" class="modal hidden">
            <div class="modal-content">
                <span id="reply-modal-close" class="close-btn">&times;</span>
                <h3>Add Your Feedback</h3>
                <form id="reply-form" action="<?=ROOT?>/public/workerHelpDesk/submitReply" method="POST">
                    <input type="hidden" id="reply-complaint-id" name="complaint_id" value="">
                    <div class="form-group">
                        <label for="reply-message">Your Message</label>
                        <textarea id="reply-message" name="comments" placeholder="Type your feedback here..." required></textarea>
                    </div>
                    <div class="modal-actions">
                        <button type="button" onclick="closeReplyModal()" class="cancel-btn">Cancel</button>
                        <button type="submit" class="submit-btn">
                            <i class="fas fa-paper-plane"></i> Send Feedback
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- JavaScript -->
        <script>
            // Pass PHP root URL to JavaScript
            const ROOT_URL = "<?=ROOT?>";
        </script>
        <script src="<?=ROOT?>/public/assets/js/workerHelpDesk.js"></script>
    </body>
</html>
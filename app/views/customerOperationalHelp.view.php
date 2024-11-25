<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Operational Help Desk</title>
        <link
            rel="stylesheet"
            href="<?=ROOT?>/public/assets/css/customerHelpDesk.css"
        />
    </head>
    <body>
        <?php include(ROOT_PATH . '/app/views/components/navbar.view.php'); ?>

        <div class="helpdesk-container">
            <h1>Operational Help Desk</h1>

            <!-- Contact Section -->
            <section class="contact-section">
                <h2>Contact Us</h2>
                <div class="contact-methods">
                    <div class="contact-item">
                        <img
                            src="<?=ROOT?>/public/assets/images/phone-icon.png"
                            alt="Phone Icon"
                        />
                        <p>Support Hotline: +94 123 456 789</p>
                    </div>
                    <div class="contact-item">
                        <img
                            src="<?=ROOT?>/public/assets/images/email-icon.png"
                            alt="Email Icon"
                        />
                        <p>Email: customersupport@AideAura.com</p>
                    </div>
                </div>
            </section>

            <!-- Submit Issue Form -->
            <section class="submit-issue">
                <h2>Submit an Issue</h2>
                <form action="<?=ROOT?>/submit-issue" method="POST">
                    <div class="form-group">
                        <label for="issue-type">Issue Type</label>
                        <select id="issue-type" name="issue_type" required>
                            <option value="">Select an Issue Type</option>
                            <optgroup label="General Issues">
                                <option value="general-inquiry">
                                    General Inquiry
                                </option>
                                <option value="feedback">
                                    Feedback/Suggestions
                                </option>
                            </optgroup>
                            <optgroup label="Service Issues">
                                <option value="worker-unavailability">
                                    Worker Unavailability
                                </option>
                                <option value="worker-misconduct">
                                    Worker Misconduct
                                </option>
                                <option value="worker-scheduling">
                                    Worker Scheduling Issues
                                </option>
                            </optgroup>
                            <optgroup label="Booking Issues">
                                <option value="unable-to-book">
                                    Unable to Book
                                </option>
                                <option value="incorrect-details">
                                    Incorrect Booking Details
                                </option>
                                <option value="cancellation">
                                    Booking Cancellations/Rescheduling
                                </option>
                            </optgroup>
                            <optgroup label="Payment Issues">
                                <option value="failed-payment">
                                    Failed Payment
                                </option>
                                <option value="overcharged">Overcharged</option>
                                <option value="refund-request">
                                    Refund Request
                                </option>
                                <option value="payment-verification">
                                    Payment Verification
                                </option>
                            </optgroup>
                            <optgroup label="Technical Issues">
                                <option value="website-loading">
                                    Website Not Loading
                                </option>
                                <option value="bug-report">
                                    App/Website Bug
                                </option>
                                <option value="login-issues">
                                    Account Login Issues
                                </option>
                                <option value="profile-update">
                                    Profile Update Issues
                                </option>
                            </optgroup>
                            <optgroup label="Account Issues">
                                <option value="forgot-password">
                                    Forgot Password
                                </option>
                                <option value="deactivation">
                                    Account Deactivation
                                </option>
                                <option value="unauthorized-access">
                                    Unauthorized Access
                                </option>
                                <option value="role-permission">
                                    Role/Permission Issues
                                </option>
                            </optgroup>
                            <optgroup label="Complaint/Feedback">
                                <option value="service-complaint">
                                    Customer Service Complaint
                                </option>
                                <option value="worker-complaint">
                                    Worker Complaint
                                </option>
                                <option value="general-feedback">
                                    General Feedback
                                </option>
                            </optgroup>
                            <optgroup label="Help Requests">
                                <option value="service-guidance">
                                    Service Guidance
                                </option>
                                <option value="operational-help">
                                    Operational Help
                                </option>
                                <option value="policy-clarification">
                                    Policy Clarification
                                </option>
                            </optgroup>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="issue-description">Description</label>
                        <textarea
                            id="issue-description"
                            name="description"
                            placeholder="Describe your issue here..."
                            required
                        ></textarea>
                    </div>
                    <button type="submit" class="submit-btn">
                        Submit Issue
                    </button>
                </form>
            </section>

            <!-- Knowledge Base Section -->
            <section class="knowledge-base">
                <h2>Knowledge Base</h2>
                <div class="kb-articles">
                    <div class="kb-article">
                        <h3>How to book a worker?</h3>
                        <p>
                            Learn the step-by-step process for booking a
                            worker...
                        </p>
                        <a href="#">Read More</a>
                    </div>
                    <div class="kb-article">
                        <h3>Payment issues and resolutions</h3>
                        <p>
                            Find answers to common payment-related problems...
                        </p>
                        <a href="#">Read More</a>
                    </div>
                </div>
            </section>
        </div>

        <?php include(ROOT_PATH . '/app/views/components/footer.view.php'); ?>

        <style>
            .helpdesk-container {
                padding: 20px;
                max-width: 1200px;
                margin: auto;
                font-family: Arial, sans-serif;
            }

            h1,
            h2 {
                color: #333;
            }

            .contact-section {
                margin-bottom: 30px;
            }

            .contact-methods {
                display: flex;
                gap: 20px;
            }

            .contact-item {
                display: flex;
                align-items: center;
                gap: 10px;
                background: #f9f9f9;
                padding: 10px 15px;
                border-radius: 5px;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            }

            .contact-item img {
                width: 30px;
            }

            .submit-issue {
                margin-bottom: 30px;
                background: #f4f4f4;
                padding: 20px;
                border-radius: 5px;
            }

            .submit-issue .form-group {
                margin-bottom: 15px;
            }

            .submit-issue label {
                display: block;
                margin-bottom: 5px;
            }

            .submit-issue input,
            .submit-issue textarea,
            .submit-issue select {
                width: 100%;
                padding: 10px;
                border: 1px solid #ccc;
                border-radius: 5px;
                font-size: 14px;
            }

            .submit-btn {
                background-color: #4caf50;
                color: white;
                border: none;
                padding: 10px 20px;
                border-radius: 5px;
                cursor: pointer;
            }

            .submit-btn:hover {
                background-color: #45a049;
            }

            .knowledge-base {
                background: #e7f3ff;
                padding: 20px;
                border-radius: 5px;
            }

            .kb-articles {
                display: flex;
                gap: 20px;
                flex-wrap: wrap;
            }

            .kb-article {
                background: white;
                padding: 15px;
                border: 1px solid #ddd;
                border-radius: 5px;
                flex: 1;
                min-width: 250px;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            }

            .kb-article h3 {
                margin-bottom: 10px;
                font-size: 18px;
            }

            .kb-article p {
                margin-bottom: 10px;
                font-size: 14px;
            }

            .kb-article a {
                color: #007bff;
                text-decoration: none;
            }

            .kb-article a:hover {
                text-decoration: underline;
            }
        </style>
    </body>
</html>

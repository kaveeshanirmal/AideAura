<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Help Desk</title>
    <link rel="stylesheet" href="<?=ROOT?>/public/assets/css/customerHelpDesk.css">
</head>
<body>
    <?php include(ROOT_PATH . '/app/views/components/navbar.view.php'); ?>
    
    <div class="helpdesk-container">
        <h1>Payment Help Desk</h1>

        <!-- Contact Section -->
        <section class="contact-section">
            <h2>Contact Payment Support</h2>
            <div class="contact-methods">
                <div class="contact-item">
                    <img src="<?=ROOT?>/public/assets/images/phone-icon.png" alt="Phone Icon">
                    <p>Payment Hotline: +94 987 654 321</p>
                </div>
                <div class="contact-item">
                    <img src="<?=ROOT?>/public/assets/images/email-icon.png" alt="Email Icon">
                    <p>Email: paymentsupport@AideAura.com</p>
                </div>
            </div>
        </section>

        <!-- Report Payment Issue -->
        <section class="report-payment-issue">
            <h2>Report a Payment Issue</h2>
            <form action="<?=ROOT?>/report-payment-issue" method="POST">
                <div class="form-group">
                    <label for="payment-method">Payment Method</label>
                    <select id="payment-method" name="payment_method" required>
                        <option value="credit_card">Credit Card</option>
                        <option value="paypal">PayPal</option>
                        <option value="bank_transfer">Bank Transfer</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="transaction-id">Transaction ID</label>
                    <input type="text" id="transaction-id" name="transaction_id" placeholder="Enter your transaction ID" required>
                </div>
                <div class="form-group">
                    <label for="issue-description">Issue Description</label>
                    <textarea id="issue-description" name="description" placeholder="Describe your payment issue here..." required></textarea>
                </div>
                <button type="submit" class="submit-btn">Submit Issue</button>
            </form>
        </section>

        <!-- Payment FAQs -->
        <section class="payment-faqs">
            <h2>Payment FAQs</h2>
            <div class="kb-articles">
                <div class="kb-article">
                    <h3>How to track a payment?</h3>
                    <p>Learn how to check the status of your payment...</p>
                    <a href="#">Read More</a>
                </div>
                <div class="kb-article">
                    <h3>What to do if a payment fails?</h3>
                    <p>Find solutions for failed transactions...</p>
                    <a href="#">Read More</a>
                </div>
                <div class="kb-article">
                    <h3>Refund process and timelines</h3>
                    <p>Understand how refunds are processed and the timelines...</p>
                    <a href="#">Read More</a>
                </div>
            </div>
        </section>
    </div>

    <?php include(ROOT_PATH . '/app/views/components/footer.view.php'); ?>
</body>
</html>

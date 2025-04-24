<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact</title>
    <link rel="stylesheet" href="assets/css/contact.css">
</head>
<body>
    <!-- Navbar -->
    <?php include(ROOT_PATH . '/app/views/components/navbar.view.php');?>

    <!-- Main Content -->
    <main>
        <section class="contact-form">
            <h1>Contact Us</h1>
            <form>
                <input type="text" placeholder="Your Name" required>
                <input type="email" placeholder="Your Email" required>
                <textarea placeholder="Message" required></textarea>
                <button type="submit">Send</button>
            </form>
        </section>
    </main>

    <!-- Footer -->
    <?php include(ROOT_PATH . '/app/views/components/footer.view.php'); ?>
</body>
</html>

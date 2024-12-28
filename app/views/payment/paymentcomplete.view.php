<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Complete</title>
    <link rel="stylesheet" href="assets/css/paymentcomplete.css">
</head>
<body>
    <!-- Navbar -->
    <?php include(ROOT_PATH . '/app/views/components/navbar.view.php');?>

    <!-- Main Content -->
    <main>
        <section class="contact-form">
            <h1>Thank You!</h1>
            <h1>Payment Was Successful</h1>
            <button onclick="window.location.href='index.php'">Go Back</button>
        </section>
    </main>

    <!-- Footer -->
    <?php include(ROOT_PATH . '/app/views/components/footer.view.php'); ?>
</body>
</html>

<!DOCTYPE html>
<html>
<head>
    <title>Contact</title>
    <link rel="stylesheet" href="assets/css/home.css">
    <style>
        body{
            height: 100%;
            overflow: hidden;
        }
    </style>
</head>

<body>
    <!-- Navbar -->
        <?php include './components/navbar.view.php'; ?>

    <!--Enter Content Here-->

    <main>
        <section class="contact-form">
            <h1>Contact With Us</h1>
            <input type="text" id="name" placeholder="Your Name">
            <input type="email" id="email" placeholder="Your Email">
            <textarea id="message" placeholder="Message"></textarea>
            <button type="submit" onclick="sendMessage()">Send</button>
        </section>
    </main>
        

    <!-- Footer -->
        <?php include './components/footer.view.php'; ?>
</body>
</html>

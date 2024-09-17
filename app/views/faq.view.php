
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FAQs and Reach Us</title>
    <link rel="stylesheet" href="<?=ROOT?>/public/assets/css/faq.css">
</head>
<body>
    <?php include(ROOT_PATH . '/app/views/components/navbar.view.php'); ?>
    
    <div class="faq-container">
        <h1>Frequently Asked Questions</h1>
        <div class="faq-list">
            <?php foreach ($faqs as $faq): ?>
                <div class="faq-item">
                    <div class="faq-question">
                        <span class="bullet">&#9654;</span>
                        <p><?= htmlspecialchars($faq['question']) ?></p>
                    </div>
                    <div class="faq-answer">
                        <p><?= htmlspecialchars($faq['answer']) ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <h2>Reach us</h2>
        <p class="contact-info">Contact us for more information.</p>
    </div>

    <?php include(ROOT_PATH . '/app/views/components/footer.view.php'); ?>

    <script>
        document.querySelectorAll('.faq-question').forEach(question => {
            question.addEventListener('click', () => {
                const answer = question.nextElementSibling;
                const bullet = question.querySelector('.bullet');
                
                if (answer.style.display === 'block') {
                    answer.style.display = 'none';
                    bullet.innerHTML = '&#9654;'; // Right-pointing triangle
                } else {
                    answer.style.display = 'block';
                    bullet.innerHTML = '&#9660;'; // Down-pointing triangle
                }
            });
        });
    </script>
</body>
</html>
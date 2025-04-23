<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Worker Listings</title>
    <link rel="stylesheet" href="<?=ROOT?>/public/assets/css/BrowseWorker.css" />
</head>
<body>
<?php include(ROOT_PATH . '/app/views/components/navbar.view.php'); ?>
<header>
    <h2>Don't worry! We've got you covered</h2>
    <h1>Try our recommended alternatives!</h1>
</header>

<section class="search-section">
    <input type="text" id="searchInput" placeholder="Search by Name or Location">
</section>

<main class="card-container" id="cardContainer">
    <!-- Visible Worker Cards -->
    <div class="worker-card show">
        <img src="../../public/assets/images/workerBrowsing/worker1.jpg" alt="Worker Image">
        <div class="worker-info">
            <h2>Ms. Shamila Liyanarachchi</h2>
            <p class="rating">⭐⭐⭐⭐☆</p>
            <p><strong>Role:</strong> Cleaner</p>
            <p><strong>Experience:</strong> 5 years</p>
            <p><strong>Location:</strong> Kalutara, Western</p>
        </div>
    </div>

    <div class="worker-card show">
        <img src="../../public/assets/images/workerBrowsing/worker2.jpg" alt="Worker Image">
        <div class="worker-info">
            <h2>Ms. Hayeshika Fernando</h2>
            <p class="rating">⭐⭐⭐⭐⭐</p>
            <p><strong>Role:</strong> Cleaner</p>
            <p><strong>Experience:</strong> 8 years</p>
            <p><strong>Location:</strong> Gampaha, Western</p>
        </div>
    </div>

    <div class="worker-card show">
        <img src="../../public/assets/images/workerBrowsing/worker3.jpg" alt="Worker Image">
        <div class="worker-info">
            <h2>Ms. Rebecca Ferguson</h2>
            <p class="rating">⭐⭐⭐⭐☆</p>
            <p><strong>Role:</strong> Cleaner</p>
            <p><strong>Experience:</strong> 6 years</p>
            <p><strong>Location:</strong> Gampaha, Western</p>
        </div>
    </div>

    <!-- Hidden Worker Cards (Shown on Load More) -->
    <div class="worker-card hidden">
        <img src="../../public/assets/images/workerBrowsing/worker4.jpg" alt="Worker Image">
        <div class="worker-info">
            <h2>Mr. Indika Thotawatta</h2>
            <p class="rating">⭐⭐⭐⭐⭐</p>
            <p><strong>Role:</strong> Cleaner</p>
            <p><strong>Experience:</strong> 7 years</p>
            <p><strong>Location:</strong> Jaffna, Northern</p>
        </div>
    </div>

    <div class="worker-card hidden">
        <img src="../../public/assets/images/workerBrowsing/worker5.jpg" alt="Worker Image">
        <div class="worker-info">
            <h2>Ms. Shashi Nishadi</h2>
            <p class="rating">⭐⭐⭐☆☆</p>
            <p><strong>Role:</strong> Cleaner</p>
            <p><strong>Experience:</strong> 2 years</p>
            <p><strong>Location:</strong> Mount Lavinia, Western</p>
        </div>
    </div>

    <div class="worker-card hidden">
        <img src="../../public/assets/images/workerBrowsing/worker6.jpg" alt="Worker Image">
        <div class="worker-info">
            <h2>Ms. Ivanthi Dias</h2>
            <p class="rating">⭐⭐⭐⭐⭐</p>
            <p><strong>Role:</strong> Cleaner</p>
            <p><strong>Experience:</strong> 10 years</p>
            <p><strong>Location:</strong> Malabe, Western</p>
        </div>
    </div>
</main>

<div class="load-more-container">
    <button id="loadMoreBtn">Load More</button>
</div>

<?php include(ROOT_PATH . '/app/views/components/footer.view.php'); ?>

<script>
    // Scroll reveal effect
    const cards = document.querySelectorAll('.worker-card');
    window.addEventListener('scroll', () => {
        cards.forEach(card => {
            const cardTop = card.getBoundingClientRect().top;
            if (cardTop < window.innerHeight - 50) {
                card.classList.add('show');
            }
        });
    });

    // Load More Functionality
    document.getElementById('loadMoreBtn').addEventListener('click', () => {
        const hiddenCards = document.querySelectorAll('.worker-card.hidden');
        hiddenCards.forEach(card => {
            card.classList.remove('hidden');
            card.classList.add('show');
        });
        document.getElementById('loadMoreBtn').style.display = 'none';
    });

    // Search Filtering
    document.getElementById('searchInput').addEventListener('keyup', function () {
        const searchValue = this.value.toLowerCase();
        const allCards = document.querySelectorAll('.worker-card');
        allCards.forEach(card => {
            const name = card.querySelector('h2').textContent.toLowerCase();
            const location = card.querySelector('p:nth-of-type(4)').textContent.toLowerCase();
            if (name.includes(searchValue) || location.includes(searchValue)) {
                card.style.display = 'flex';
            } else {
                card.style.display = 'none';
            }
        });
    });
</script>
</body>
</html>
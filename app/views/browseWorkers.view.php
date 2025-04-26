<?php
if (!isset($_SESSION['workers'] ) || empty($_SESSION['workers'])) {
    // No workers found, redirect to noWorkersPage
    header("Location: " . ROOT . "/public/searchForWorker/noWorkersFound");
    exit;
}
$workers = $_SESSION['workers'];
?>
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
    <?php if (!empty($workers) && count($workers) > 0): ?>
        <?php
        $visibleCount = 3; // Number of workers to show initially
        $count = 0;
        foreach ($workers as $worker):
            $isHidden = ($count >= $visibleCount) ? 'hidden' : 'show';
            $count++;
            ?>
            <div class="worker-card <?= $isHidden ?>">
                <img src="<?=ROOT?>/<?= htmlspecialchars($worker->profileImage) ?>" alt="Worker Image">
                <div class="worker-info">
                    <h2><?= htmlspecialchars($worker->firstName . ' ' . $worker->lastName) ?></h2>
                    <p class="rating">
                        <span class="stars-container">
                            <?php
                            $rating = floatval($worker->avg_rating ?? 0);
                            for ($i = 1; $i <= 5; $i++) {
                                if ($i <= $rating) {
                                    echo '<span class="star filled">★</span>';
                                } elseif ($i - 0.5 <= $rating) {
                                    echo '<span class="star half-filled">★</span>';
                                } else {
                                    echo '<span class="star">★</span>';
                                }
                            }
                            ?>
                        </span>
                        <span class="rating-text">
                            <?= htmlspecialchars(number_format($rating, 1)) ?>
                            (<?= htmlspecialchars($worker->total_reviews ?? 0) ?> reviews)
                        </span>
                    </p>
                    <p><strong>Role:</strong> <?= htmlspecialchars($worker->jobRole) ?></p>
                    <p><strong>Gender:</strong> <?= htmlspecialchars($worker->gender) ?></p>
                    <p><strong>Location:</strong> <?= htmlspecialchars($worker->location ?? 'Not specified') ?></p>
                    <p class="score-container">
                        <strong><span class="aide">Aide</span><span class="aura">Aura</span>Score: </strong>
                        <span class="score-value" data-score="<?= htmlspecialchars(round($worker->score)) ?>">
                            <?= htmlspecialchars(round($worker->score)).'%' ?>
                        </span>
                                <span class="score-tooltip">
                            Our smart matching score considers:<br>
                            • Rating quality (45%)<br>
                            • Completion rate (25%)<br>
                            • Review count (20%)<br>
                            • Recent activity (10%)
                        </span>
                    </p>
                    <button class="book-btn" data-worker-id="<?= htmlspecialchars($worker->workerID) ?>">Book Now</button>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p class="no-results">No workers found matching your criteria.</p>
    <?php endif; ?>
</main>

<?php if (!empty($workers) && count($workers) > $visibleCount): ?>
    <div class="load-more-container">
        <button id="loadMoreBtn">Load More</button>
    </div>
<?php endif; ?>

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
    document.getElementById('loadMoreBtn')?.addEventListener('click', () => {
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
            const location = card.querySelector('p:nth-of-type(3)').textContent.toLowerCase();
            if (name.includes(searchValue) || location.includes(searchValue)) {
                card.style.display = 'flex';
            } else {
                card.style.display = 'none';
            }
        });
    });

    // Book Now Button Functionality
    document.querySelectorAll('.book-btn').forEach(button => {
        button.addEventListener('click', function() {
            const workerID = this.getAttribute('data-worker-id');
            const formData = new FormData();
            formData.append('workerID', workerID);

            fetch("<?php echo ROOT; ?>/public/booking/bookWorker", {
                method: "POST",
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        window.location.href = "<?php echo ROOT; ?>/public/searchForWorker/waitingForResponse";
                    } else {
                        alert("Error: " + data.message);
                    }
                })
                .catch(error => {
                    console.error("Error:", error);
                    alert("An error occurred while booking the worker.");
                });
        });
    });
</script>
</body>
</html>
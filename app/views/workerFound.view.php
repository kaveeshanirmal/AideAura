<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Worker Found</title>
    <link rel="stylesheet" href="<?=ROOT?>/public/assets/css/workerFound.css">
</head>
<body>
<?php include(ROOT_PATH . '/app/views/components/navbar.view.php'); ?>
<div class="container">
    <h2 class="heading">We've found the BEST for you!</h2>
    <?php if (!empty($worker)): ?>
        <img src="<?=ROOT?>/<?= htmlspecialchars($worker->profileImage) ?>" alt="Worker Image" class="profile-image">
        <div class="worker-info">
            <p><strong>Name : </strong><?= htmlspecialchars($worker->firstName . ' ' . $worker->lastName) ?></p>
            <p><strong>Gender : </strong><?= htmlspecialchars($worker->gender) ?></p>
            <p><strong>Job Role : </strong><?= htmlspecialchars($worker->jobRole) ?></p>
            <p>
                <strong>Rating: </strong>
                <span class="rating-container">
                    <span class="stars-container">
                      <?php
                      $rating = floatval($worker->avg_rating);
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
                    <span class="rating-text"><?= htmlspecialchars(number_format($worker->avg_rating, 1)) ?> (<?= htmlspecialchars($worker->total_reviews) ?> reviews)</span>
                </span>
            </p>
        </div>
        <div class="buttons">
            <button class="accept-btn">Accept</button>
            <button class="reject-btn">Browse Other Options</button>
        </div>
    <?php else: ?>
        <p class="no-results">No workers found matching your criteria.</p>
    <?php endif; ?>
</div>
<?php include(ROOT_PATH . '/app/views/components/footer.view.php'); ?>
<script>
    document.querySelector('.accept-btn').addEventListener('click', function() {
        const workerID = "<?php echo htmlspecialchars($worker->workerID); ?>";
        console.log(workerID);

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
    document.querySelector('.reject-btn').addEventListener('click', function() {
        window.location.href = "<?php echo ROOT; ?>/public/searchForWorker/browseWorkers";
    });

</script>
</body>
</html>
<?php
// Define a dummy ROOT path if not already defined // Update to your real path
define('ROOT', 'http://localhost/aideaura');
// Manually inject a test worker
$workers = [
    (object)[
        'firstName' => 'Siri',
        'lastName' => 'Sena',
        'gender' => 'Male',
        'jobRole' => 'Gardener',
        'profileImage' => 'public\assets\images\workerFound/FoundWorker1.webp',
        'rating' => '0.1/10'
    ]
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Worker Found</title>
    <link rel="stylesheet" href="workerFound.css"/>
</head>
<body>

<div class="container">
    <h2 class="heading">We've found the BEST for you!</h2>
    <?php if (!empty($workers)): ?>
        <?php foreach ($workers as $worker): ?>
            <img src="<?=ROOT?>/<?= htmlspecialchars($worker->profileImage) ?>" alt="Worker Image" class="profile-image">
            <div class="worker-info">
                <p><strong>Name : </strong><?= htmlspecialchars($worker->firstName . ' ' . $worker->lastName) ?></p>
                <p><strong>Gender : </strong><?= htmlspecialchars($worker->gender) ?></p>
                <p><strong>Job Role : </strong><?= htmlspecialchars($worker->jobRole) ?></p>
                <p><strong>Rating : </strong><?= htmlspecialchars($worker->rating) ?></p>
            </div>
            <div class="buttons">
                <button class="accept-btn">Accept</button>
                <button class="reject-btn">Reject</button>
            </div>
            
        <?php endforeach; ?>
    <?php else: ?>
        <p class="no-results">No workers found matching your criteria.</p>
    <?php endif; ?>
</div>

</body>
</html>

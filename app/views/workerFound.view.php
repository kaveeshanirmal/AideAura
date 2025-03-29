<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Worker Found</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            width: 100%;
            text-align: center;
        }
        .profile-image {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 15px;
        }
        .worker-info {
            text-align: left;
            margin-top: 10px;
        }
        .worker-info p {
            margin: 5px 0;
            font-size: 16px;
        }
        .no-results {
            color: red;
            font-size: 18px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Worker Search Results</h2>

    <?php if (!empty($workers)): ?>
        <?php foreach ($workers as $worker): ?>
            <img src="<?=ROOT?>/<?= htmlspecialchars($worker->profileImage) ?>" alt="Worker Image" class="profile-image">
            <div class="worker-info">
                <p><strong>Name:</strong> <?= htmlspecialchars($worker->firstName . ' ' . $worker->lastName) ?></p>
                <p><strong>Username:</strong> <?= htmlspecialchars($worker->username) ?></p>
                <p><strong>Phone:</strong> <?= htmlspecialchars($worker->phone) ?></p>
                <p><strong>Email:</strong> <?= htmlspecialchars($worker->email) ?></p>
                <p><strong>Job Role:</strong> <?= htmlspecialchars($worker->jobRole) ?></p>
                <p><strong>Address:</strong> <?= htmlspecialchars($worker->address) ?></p>
            </div>
            <hr>
        <?php endforeach; ?>
    <?php else: ?>
        <p class="no-results">No workers found matching your criteria.</p>
    <?php endif; ?>
</div>

</body>
</html>

<?php

define('ROOT', 'http://localhost/aideaura');

$worker = [
    'name' => 'Sameera',
    'category' => 'Gardener',
    'rating' => 4.8,
    'image' => ROOT . '/public/assets/images/orderSummaryprofile.jpeg',
];
$booking = [
    'date' => '2025-04-20',
    'time' => '10:00 AM - 4:00 PM',
    'duration' => '6 hours',
    'location' => 'Horowpathana, Galenpidunuwawa, Apt 203',
    'payment_method' => 'Debit Card',
    'status' => 'Confirmed',
    'total' => 3250.00,
];

$statusClass = 'status-' . strtolower($booking['status']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order Summary</title>
    <link rel="stylesheet" href="<?=ROOT?>/public/assets/css/orderSummary.css">
</head>
<body>
    <div class="summary-container">
        <h1>Order Summary</h1>
        
        <div class="worker-card">
            <img src="<?php echo $worker['image']; ?>" alt="Worker Image">
            <div class="worker-details">
                <h2><?php echo $worker['name']; ?></h2>
                <p>Category: <?php echo $worker['category']; ?></p>
                <p>Rating: ⭐ <?php echo $worker['rating']; ?></p>
            </div>
        </div>

        <div class="booking-info">
            <p><strong>Booked Date:</strong> <?php echo $booking['date']; ?></p>
            <p><strong>Time Slot:</strong> <?php echo $booking['time']; ?></p>
            <p><strong>Duration:</strong> <?php echo $booking['duration']; ?></p>
            <p><strong>Location:</strong> <?php echo $booking['location']; ?></p>
            <p><strong>Payment Method:</strong> <?php echo $booking['payment_method']; ?></p>
            <p><strong>Status:</strong> <span class="status <?php echo $statusClass; ?>"><?php echo $booking['status']; ?></span></p>
        </div>

        <div class="total">
            <h3>Total Amount</h3>
            <p>LKR <?php echo number_format($booking['total'], 2); ?></p>
            <div class="text">TAX & VAT Inclusive*</div>
        </div>
        
    </div>
</body>
</html>

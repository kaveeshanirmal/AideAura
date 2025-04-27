<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking History</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-image: url("<?=ROOT?>/public/assets/images/booking_bg.jpg");
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            line-height: 1.6;
        }

        #booking-body {
            margin-top: 100px;
            display: flex;
            justify-content: center;
            padding: 20px;
        }

        .booking-container {
            width: 100%;
            max-width: 1200px;
            background-color: white;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            /* overflow: hidden; */
        }

        .booking-container > p {
            background: linear-gradient(135deg, #9d7d4a 0%, #3d0e07 100%);
            color: white;
            padding: 15px 20px;
            font-size: 20px;
            font-weight: bold;
        }

        .booking-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        .booking-table thead {
            background: linear-gradient(135deg, #9d7d4a 0%, #3d0e07 100%);
            color: white;
        }

        .booking-table th {
            padding: 12px 15px;
            text-align: left;
            font-weight: 200;
            text-transform: uppercase;
            font-size: 14px;
            letter-spacing: 0.5px;
            overflow: visible !important;
        }

        .booking-table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #e2e8f0;
            overflow-x: visible;
        }

        .booking-table tbody tr:nth-child(even) {
            background-color: #f8f4f0;
            overflow: visible;
        }

        .booking-table tbody tr:hover {
            background-color: #f0e6d8;
            transition: background-color 0.3s ease;
            overflow: visible;
        }

        .worker-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
        }

        .status {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 4px;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 12px;
        }

        .status.ongoing {
            background-color: #ffc107;
            color: #333;
        }

        .status.completed {
            background-color: #28a745;
            color: white;
        }

        .status.cancelled {
            background-color: #dc3545;
            color: white;
        }

        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 15px;
            background-color: #efe5dc;
        }

        .pagination span {
            margin: 0 10px;
            color: #3d0e07;
        }

        .pagination button {
            background: linear-gradient(135deg, #9d7d4a 0%, #3d0e07 100%);
            color: white;
            border: none;
            padding: 8px 15px;
            margin: 0 5px;
            border-radius: 4px;
            cursor: pointer;
            transition: opacity 0.3s ease;
        }

        .pagination button:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .pagination button:not(:disabled):hover {
            opacity: 0.9;
        } 

        .hidden-pin {
            position: relative;
            cursor: pointer;
            font-weight: bold;
            letter-spacing: 4px;
        }

        .hidden-pin .actual-pin {
            display: none;
            color: #000;
        }
        

        .hidden-pin::after {
            content: "Give this code to your worker upon job completion :)";
            position: absolute;
            white-space: pre-wrap;
            word-wrap: break-word;
            transition: opacity 0.5s ease, visibility 0.5s ease;
            top: -38px;
            left: 65px;
            background-color:rgba(89, 39, 9, 0.86);
            color: #fff;
            padding: 6px 10px;
            font-size: 14px;
            font-weight: 200;
            border-radius: 5px;
            display: none;
            text-align: left;
            width: 150px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.25);
            z-index: 1000;
            transition: 0.5s ease;   
        }

        

        .hidden-pin:hover .dots {
            display: none;
            cursor: pointer;
        }

        .hidden-pin:hover .actual-pin {
            display: inline;
        }

        .hidden-pin:hover::after {
            display: block;
        }

        .cancel-btn {
            margin-top: 5px;
            background-color: #dc3545; /* Bootstrap red */
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease, box-shadow 0.3s ease;
        }

        .cancel-btn:hover {
            background-color:rgb(159, 18, 32); /* Darker red on hover */
            box-shadow: 0 0 5px rgba(220, 53, 69, 0.8), 0 0 5px rgba(220, 53, 69, 0.8), 0 0 5px rgba(220, 53, 69, 0.8);
        }

        #alertBox {
            position: fixed;
            top: 80px;
            left: 50%;
            transform: translateX(-50%);
            background: rgba(82, 9, 9, 0.68); 
            border: 1px solid rgba(255, 0, 0, 0.4);
            padding: 15px 30px;
            border-radius: 8px;
            color:rgb(255, 255, 255); 
            font-size: 16px;
            font-weight: bold;
            z-index: 10000;
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
            animation: fadeIn 0.3s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translate(-50%, 0); }
            to { opacity: 1; transform: translate(-50%, 0); }
        }


    </style>
</head>
<body>
    <?php include(ROOT_PATH . '/app/views/components/navbar.view.php'); ?>
    <div id="alertBox" style="display: none;">
        <p id="alertMessage"></p>
    </div>
    <div id="booking-body">
        <div class="booking-container">
            <p>Booking History</p>
            <table class="booking-table">
                <thead>
                    <tr>
                        <th>Booking ID</th>
                        <th>Worker Image</th>
                        <th>Worker Name</th>
                        <th>Service Type</th>
                        <th>Booking date</th>
                        <th>Start Time</th>
                        <th>Status</th>
                        <th>Total</th>
                        <th>Created At</th>
                        <th>Completion Code</th>
                        <th>Action</th>
                        
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($bookings)): ?>
                        <?php foreach ($bookings as $booking): ?>
                            <tr>
                                <td>#<?= htmlspecialchars($booking->bookingID) ?></td>
                                <td><img src="<?= ROOT ?>/<?= htmlspecialchars($booking->workerImage) ?>" alt="Worker" class="worker-avatar"></td>
                                <td><?= htmlspecialchars($booking->workerName) ?></td>
                                <td><?= htmlspecialchars($booking->serviceType) ?></td>
                                <td><?= date("M j, Y", strtotime($booking->bookingDate)) ?></td>
                                <td><?= date("H:i", strtotime($booking->startTime)) ?></td>
                                <td>
                                    <?php
                                        $statusClass = strtolower($booking->status);
                                        $validStatuses = ['ongoing', 'completed', 'cancelled'];
                                        $statusClass = in_array($statusClass, $validStatuses) ? $statusClass : 'ongoing';
                                    ?>
                                    <span class="status <?= $statusClass ?>"><?= ucfirst($booking->status) ?></span>
                                </td>
                                <td><?= htmlspecialchars($booking->totalCost) ?></td>
                                <td><?= htmlspecialchars($booking->createdAt) ?></td>
                                <td>
                                <?php if ($booking->verificationCode): ?>
                                    <span class="hidden-pin" data-pin="<?= htmlspecialchars($booking->verificationCode) ?>">
                                        <span class="dots">••••</span>
                                        <span class="actual-pin"><?= htmlspecialchars($booking->verificationCode) ?></span>
                                    </span>
                                <?php else: ?>
                                    <span>N/A</span>
                                <?php endif; ?>
                                </td>
                                <td>
                                <?php if (in_array(strtolower($booking->status), ['pending', 'accepted', 'confirmed'])): ?>
                                    <div id="cancel-section-<?= $booking->bookingID ?>">
                                        <form method="POST" action="<?= ROOT ?>/public/customerProfile/cancellingBooking" onsubmit="handleCancel(event, <?= $booking->bookingID ?>)">
                                            <input type="hidden" name="bookingID" value="<?= $booking->bookingID ?>">
                                            <button type="submit" class="cancel-btn">Cancel</button>
                                        </form>
                                    </div>
                                <?php elseif (in_array(strtolower($booking->status), ['completed'])): ?>
                                    <span>Fulfilled</span>
                                <?php else: ?>
                                    <span>Inactive</span>
                                <?php endif; ?>

                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" style="text-align: center;">No bookings found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
            <div class="pagination">
                <span>10 per page</span>
                <span>1 of 1 pages</span>
                <button disabled>&lt;</button>
                <button disabled>&gt;</button>
            </div>
        </div>
    </div>
    <?php include(ROOT_PATH . '/app/views/components/footer.view.php'); ?>
</body>
<script>
    function handleCancel(event, bookingID) {
        event.preventDefault(); // stop form from submitting the traditional way

        const form = event.target;
        const formData = new FormData(form);

        fetch(form.action, {
            method: 'POST',
            body: formData,
        }).then(response => {
            if (response.ok) {
                
                document.getElementById(`cancel-section-${bookingID}`).innerHTML = '<span>Inactive</span>';
                showAlert('Your booking has been cancelled!');
            } else {
                alert('Failed to cancel booking!');
            }
        }).catch(err => {
            console.error(err);
            alert('Something went wrong!');
        });
    }

    function showAlert(message) {
        const alertBox = document.getElementById('alertBox');
        const alertMessage = document.getElementById('alertMessage');
        
        alertMessage.innerText = message;
        alertBox.style.display = 'block';

        setTimeout(() => {
            alertBox.style.display = 'none';
        }, 3000); 
    }
</script>

</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Physical Verifications</title>
    <style>
        body {
            background: linear-gradient(to bottom right, #f5f0eb, #fdf6ef);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #5c4033;
            padding: 0 20px 20px 20px;
        }

        h1 {
            color: #5c4033;
        }
        h2 {
            color:rgb(255, 255, 255);
            font-weight: 200;
        } 

        .sections-container {
            display: flex;
            gap: 20px;
            margin-top: 30px;
        }

        .sections-container > div {
            flex: 1;
        }

        .form-section, .records-section, .check-section {
            background: linear-gradient(to bottom right,rgb(108, 71, 25),rgb(57, 34, 6));
            padding: 20px;
            margin-bottom: 30px;
            border: 1px solid #d2b48c;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(139, 94, 60, 0.2);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .form-section:hover, .records-section:hover, .check-section:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(139, 94, 60, 0.3);
        }
        input[type="text"], input[type="email"], input[type="number"] {
            right: 5px;
            width: 96%;
            padding: 10px;
            margin-right: auto;
            margin-top: 5px;
            margin-bottom: 20px;
            display: block;
            border: 1px solid #c2a278;
            border-radius: 6px;
            background-color: #fff8f1;
            transition: border-color 0.3s;
        }
        input[type="text"]:focus, input[type="email"]:focus, input[type="number"]:focus {
            border-color: #a9745a;
            outline: none;
        }
        button {
            background: linear-gradient(to right, #a9745a, #8b5e3c);
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
            transition: background 0.3s, transform 0.2s;
            box-shadow: 0 8px 16px rgba(139, 94, 60, 0.3);
        }
        button:hover {
            background: linear-gradient(to right, #915f46, #75462e);
            transform: scale(1.05);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            background: #fff8f1;
            box-shadow: 0 2px 6px rgba(139, 94, 60, 0.1);
            border-radius: 8px;
            overflow: hidden;
        }
        th, td {
            padding: 12px;
            border-bottom: 1px solid #d2b48c;
            text-align: left;
        }
        th {
            background-color: #ffe7cd;
        }
        tr:hover {
            background-color: #fff0e0;
        }
        .message-box {
            padding: 15px;
            background: linear-gradient(to right, #fce6d3, #f9dbc1);
            border-left: 6px solid #a9745a;
            margin-bottom: 20px;
            border-radius: 6px;
            box-shadow: 0 3px 6px rgba(139, 94, 60, 0.2);
            animation: fadeIn 0.6s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>

    <!-- <?php include(ROOT_PATH . '/app/views/components/employeeNavbar.view.php'); ?> -->

    <h1>Physical Verification Management</h1>

    <?php if (!empty($message)): ?>
        <div class="message-box"><?php echo $message; ?></div>
    <?php endif; ?>

    <?php if (!empty($check_message)): ?>
        <div class="message-box"><?php echo $check_message; ?></div>
    <?php endif; ?>

    <?php if (!empty($delete_message)): ?>
        <div class="message-box"><?php echo $delete_message; ?></div>
    <?php endif; ?>

    <div class="records-section">
        <h2>Existing Records</h2>
        <table>
            <thead>
                <tr>
                    <th>NIC</th>
                    <th>Email</th>
                    <th>Verification Code</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($records as $record): ?>
                <tr>
                    <td><?php echo htmlspecialchars($record->nic); ?></td>
                    <td><?php echo htmlspecialchars($record->email); ?></td>
                    <td><?php echo htmlspecialchars($record->verification_code); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="sections-container">
        <div class="form-section">
            <h2>Add In-Location Reference Code</h2>
            <form method="POST">
                <input type="text" id="nic" name="nic" placeholder="Enter worker NIC here" required>

                <input type="email" id="email" name="email" placeholder="Enter worker email here" required>

                <button type="submit" name="add_record">Generate Code & Add</button>
            </form>
        </div>

        <div class="check-section">
            <h2>Check In-Location Reference Code</h2>
            <form method="POST">
                <input type="number" id="verification_code" name="verification_code" placeholder="Enter reference code here">

                <button type="submit" name="check_code">Check Code</button>
            </form>
        </div>

        <div class="check-section">
            <h2>Delete In-Location Reference Code</h2>
            <form method="POST" action="">
                <input type="text" name="nic_to_delete" placeholder="Enter NIC to delete" required>
                <button type="submit" name="delete_record">Delete Record</button>
            </form>
        </div>
    </div>
</body>
</html>

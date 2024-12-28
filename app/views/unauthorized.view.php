<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Access Denied</title>
    <style>
        body {
            font-family: 'Georgia', serif;
            background-image: linear-gradient(to bottom, #3d0505, #834d2bdc),
        url(../images/Loading_Screen_Background.png);
            background-size: cover;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            overflow: hidden;
        }
        .access-denied-container {
            background-color: rgba(139, 69, 19, 0.2);
            border-radius: 20px;
            padding: 40px;
            text-align: center;
            box-shadow: 0 15px 35px rgba(0,0,0,0.3);
            max-width: 500px;
            width: 90%;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(210, 140, 80, 0.3);
        }
        .lock-icon {
            font-size: 100px;
            color: #D2691E;
            margin-bottom: 20px;
            text-shadow: 0 0 20px rgba(0,0,0,0.2);
        }
        h1 {
            color: #FFE4B5;
            font-size: 2.5em;
            margin-bottom: 15px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }
        p {
            color: rgba(255, 228, 181, 0.9);
            line-height: 1.6;
            margin-bottom: 25px;
        }
        .btn {
            display: inline-block;
            background-color: rgba(210, 105, 30, 0.3);
            color: #FFE4B5;
            padding: 12px 25px;
            text-decoration: none;
            border-radius: 25px;
            transition: all 0.3s ease;
            border: 1px solid rgba(210, 105, 30, 0.5);
        }
        .btn:hover {
            background-color: rgba(210, 105, 30, 0.5);
            transform: translateY(-5px);
        }
    </style>
</head>
<body>
    <div class="access-denied-container">
        <div class="lock-icon">🔒</div>
        <h1>Unauthorized Access</h1>
        <p>You do not have permission to access this resource. Please contact the system administrator if you believe this is an error.</p>
        <a href="<?=ROOT?>/public/home" class="btn">Return to Home</a>
    </div>
</body>
</html>

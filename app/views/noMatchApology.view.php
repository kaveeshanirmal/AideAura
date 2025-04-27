<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apology Message</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Segoe+UI:wght@400;700&display=swap');

        body {
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            height: 100vh;
            overflow: hidden;
            display: flex;
            justify-content: center;
            align-items: center;
            background: url('<?=ROOT?>/public/assets/images/Hero_Slider_5.png') no-repeat center center fixed;
            background-size: cover;
            animation: bgZoom 20s ease-in-out infinite alternate;
        }

        @keyframes bgZoom {
            from { transform: scale(1); }
            to { transform: scale(1.05); }
        }

        .gradient-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(to bottom,rgb(140, 87, 71),rgb(71, 29, 14));
            opacity: 0.8;
            transition: opacity 2s ease-in-out;
            z-index: 1;
        }

        .message-box {
            position: relative;
            z-index: 2;
            text-align: center;
            color: white;
            background: rgba(255, 0, 0, 0.4);
            backdrop-filter: blur(10px);
            padding: 50px;
            border-radius: 15px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.5);
            animation: fadeIn 2s ease, float 4s ease-in-out infinite;
        }

        .message-box h1 {
            margin-top: 20px;
            font-size: 2.8rem;
            margin-bottom: 20px;
            font-weight: 200;
        }

        .message-box p {
            font-size: 1.3rem;
            max-width: 500px;
            margin: auto;
        }
        .message-box h4{
            font-size: 20px;
            font-weight: 100;

        }

        .countdown {
            margin-top: 20px;
            font-size: 1.5rem;
            font-weight: 100;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
    </style>
</head>
<body>
<div class="gradient-overlay"></div>
<div class="message-box">
    <h1>Sorry for the inconvenience : (</h1>
    <p>No matches were found for your requirement</p>
    <p><h4>Maybe try again within few minutes : )</h4></p>
            <div class="countdown">Back to home in <span id="timer">5</span> seconds...</div>
</div>
<script>
    // Smooth overlay pulse animation
    const overlay = document.querySelector('.gradient-overlay');
    let opacity = 0.8;

    setInterval(() => {
        opacity = opacity === 0.8 ? 0.9 : 0.8;
        overlay.style.opacity = opacity;
    }, 3000);

    // Countdown and redirect script
    let countdown = 5; // seconds
    const timerElement = document.getElementById('timer');
    const redirectURL = '<?= $redirectUrl ?? ROOT . "/public/" ?>';

    const countdownInterval = setInterval(() => {
        countdown--;
        timerElement.textContent = countdown.toString();

        if (countdown === 0) {
            clearInterval(countdownInterval);
            window.location.href = redirectURL; // Redirects to the specified URL
        }
    }, 1000);
</script>
</body>
</html>
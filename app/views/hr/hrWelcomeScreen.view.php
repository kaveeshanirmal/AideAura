<style>
    body {
        background: linear-gradient(to bottom right,rgb(65, 35, 13),#5b3a24cc,rgba(124, 70, 16, 0.83));
        font-family: 'Poppins', sans-serif;
        color: #ffffff;
        margin: 0;
        padding: 0;
        min-height: 100vh;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }
    .welcome-container {
        text-align: left;
        padding: 40px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 20px;
        box-shadow: 0px 15px 30px rgba(0, 0, 0, 0.3);
        backdrop-filter: blur(10px);
        animation: fadeSlide 1s ease forwards;
        max-width: 1000px;

        width: 100%;
    }
    .text {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        font-size: 40px;
        font-weight: 200;
        margin-bottom: 30px;
        animation: zoomIn 1s ease forwards;
    }
    .sub_text {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        font-size: 30px;
        font-weight: 100;
        margin-top: 30px;
        animation: zoomIn 1s ease forwards;
    }
    p {
        font-family: 'Segoe UI', Tahoma, sans-serif, Verdana, sans-serif;
        font-size: 1.3rem;
        margin-bottom: 30px;
        animation: fadeIn 2s ease forwards;
    }
    #live-clock {
        font-family:'Segoe UI', Tahoma, sans-serif, Verdana, sans-serif;
        margin-top: -10px;
        margin-bottom: 25px;
        font-size: 1.2rem;
        color: #f0e5de;
        letter-spacing: 1px;
        animation: fadeInClock 2s ease forwards;
    }
    .navigation-cards {
        display: flex;
        flex-wrap: wrap;
        gap: 48px;
        justify-content: space-evenly; /* ⭐ Spread evenly */
        /* align-items: center; */
        margin-top: 40px;
    }
    .nav-card {
        background: linear-gradient(135deg,#5b3a24cc, #8b5e3c);
        padding: 10px 10px;
        border-radius: 10px;
        text-decoration: none;
        color: white;
        font-weight: 300;
        font-size: 1.1rem;
        transition: transform 0.3s ease, background 0.3s ease;
        box-shadow: 1px 1px 1px 1px rgba(0,0,0,0.3);
        opacity: 0;
        transform: scale(0.7);
        animation: dissolveCenter 0.8s ease forwards;
    }

    /* Apply slight delay for each card if you want staggered effect */
    .nav-card:nth-child(1) {
        animation-delay: 0.2s;
    }
    .nav-card:nth-child(2) {
        animation-delay: 0.4s;
    }
    .nav-card:nth-child(3) {
        animation-delay: 0.6s;
    }
    .nav-card:nth-child(4) {
        animation-delay: 0.8s;
    }
    .nav-card:nth-child(5) {
        animation-delay: 1s;
    }
    .nav-card:hover {
        transform: translateY(-8px) scale(1.05);
        background: linear-gradient(135deg, #8b5e3c, #d2b48c);
    }

    @keyframes fadeSlide {
        from {
            opacity: 0;
            transform: translateY(50px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes dissolveCenter {
        0% {
            opacity: 0;
            transform: scale(0.7);
        }
        100% {
            opacity: 1;
            transform: scale(1);
        }
    }

    @keyframes zoomIn {
        from {
            opacity: 0;
            transform: scale(0.8);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
        }
        to {
            opacity: 1;
        }
    }

    @keyframes fadeInClock {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>

<div class="welcome-container">
    <div class="text"><?= $greeting ?>, <?= $_SESSION['user_name'] ?? 'HR Manager' ?>!</div>
    <div id="live-clock"></div>
    <p>Today is <?= date('l, F j, Y') ?></p>
</div>

<div class="sub_text">What made you log in today?</div>

<div class="navigation-cards">
        <a href="<?= ROOT ?>/public/HrManager/workerProfiles" class="nav-card">Worker Profiles</a>
        <a href="<?= ROOT ?>/public/HrManager/workerSchedules" class="nav-card">Worker Schedules</a>
        <a href="<?= ROOT ?>/public/HrManager/verificationRequests" class="nav-card">Verification Requests</a>
        <a href="<?= ROOT ?>/public/HrManager/managePhysicalVerifications" class="nav-card">Manage Physical Verifications</a>
        <a href="<?= ROOT ?>/public/HrManager/workerComplaints" class="nav-card">Worker Inquiries</a>
</div>

<script>
// Live clock with seconds
function updateClock() {
    const now = new Date();
    const timeString = now.toLocaleTimeString();
    document.getElementById('live-clock').textContent = timeString;
}
setInterval(updateClock, 1000);
updateClock(); // Initial call
</script>

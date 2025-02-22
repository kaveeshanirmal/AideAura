<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Worker Profiles</title>
    <link rel="stylesheet" href="<?= htmlspecialchars(ROOT) ?>/public/assets/css/adminWorkerProfile.css">
    <script>
   document.addEventListener('DOMContentLoaded', function () {
    const workerFieldDropdown = document.getElementById('worker-field');
    const workersList = document.getElementById('workers-list');

    const workersObj = <?= isset($workers) ? json_encode($workers, JSON_HEX_TAG) : '[]'; ?>;
    const workers = Object.values(workersObj);

    console.log(workers);

    function renderWorkers(filteredWorkers) {
        workersList.innerHTML = ''; // Clear existing list

        if (filteredWorkers.length > 0) {
            filteredWorkers.forEach(worker => {
                const workerCard = document.createElement('div');
                workerCard.classList.add('worker-card');

                // Store worker data as JSON in a hidden input
                const workerInput = document.createElement('input');
                workerInput.type = "hidden";
                workerInput.name = "worker";
                workerInput.value = JSON.stringify(worker);

                workerCard.innerHTML = `
                    <div class="worker-info">
                        <div class="worker-avatar">
                            <img src="<?= htmlspecialchars(ROOT) ?>/public/assets/images/user_icon.png" alt="Worker Avatar">
                        </div>
                        <div class="worker-details">
                            <h3>${worker.firstName} ${worker.lastName}</h3>
                            <p>${worker.role}</p>
                        </div>
                    </div>
                `;

                // Navigate to worker details on click
                workerCard.addEventListener('click', function () {
                    const form = document.createElement('form');
                    form.action = "<?= htmlspecialchars(ROOT) ?>/public/admin/workerDetails";
                    form.method = "POST";

                    const input = document.createElement('input');
                    input.type = "hidden";
                    input.name = "workerData";
                    input.value = JSON.stringify(worker);

                    form.appendChild(input);
                    document.body.appendChild(form);
                    form.submit();
                });

                workersList.appendChild(workerCard);
            });
        } else {
            workersList.innerHTML = '<p>No workers found.</p>';
        }
    }

    workerFieldDropdown.addEventListener('change', function () {
        const selectedField = this.value.toLowerCase();
        const filteredWorkers = selectedField === 'all'
            ? workers
            : workers.filter(worker => worker.role.toLowerCase() === selectedField);

        renderWorkers(filteredWorkers);
    });

    renderWorkers(workers);
});

    </script>
</head>
<body>
    <div class="dashboard-container">
        <?php include(ROOT_PATH . '/app/views/components/employeeNavbar.view.php'); ?>
        <main class="main-content">
            <div class="search-container">
                <label for="worker-field">Select the Worker Field:</label>
                <select id="worker-field" class="search-input">
                    <option value="All">All</option>
                    <option value="Cook">Cook</option>
                    <option value="Cook 24-hour Live in">Cook 24-hour Live in</option>
                    <option value="Nanny">Nanny</option>
                    <option value="All rounder">All rounder</option>
                    <option value="Maid">Maid</option>
                </select>
            </div>

            <div class="workers-list" id="workers-list">
                <!-- Workers will be rendered dynamically -->
            </div>
        </main>
    </div>
</body>
</html>

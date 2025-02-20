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

        // Ensure `$workers` exists and is properly encoded
        const workersObj = <?= isset($workers) ? json_encode($workers, JSON_HEX_TAG) : '[]'; ?>;
        const workers = Object.values(workersObj);

        console.log("Workers data:", workers); // Debugging output

        function renderWorkers(filteredWorkers) {
            workersList.innerHTML = ''; // Clear existing list

            if (filteredWorkers.length > 0) {
                filteredWorkers.forEach(worker => {
                    const workerCard = document.createElement('div');
                    workerCard.classList.add('worker-card');
                    workerCard.dataset.userid = worker.userID; // Correctly attach userID

                    workerCard.innerHTML = `
                        <a href="<?= htmlspecialchars(ROOT) ?>/public/admin/workerDetails?userID=${worker.userID}">
                            <div class="worker-info">
                                <div class="worker-avatar">
                                    <img src="<?= htmlspecialchars(ROOT) ?>/public/assets/images/user_icon.png" alt="Worker Avatar">
                                </div>
                                <div class="worker-details">
                                    <h3>${worker.firstName} ${worker.lastName}</h3>
                                    <p>${worker.role}</p>
                                </div>
                            </div>
                        </a>
                    `;

                    // Click event listener to redirect with correct userID
                    workerCard.addEventListener('click', function (event) {
                        event.preventDefault(); // Prevent default anchor behavior
                        const userID = this.dataset.userid;

                        // Redirect with userID
                        window.location.href = `<?= htmlspecialchars(ROOT) ?>/public/admin/workerDetails?userID=${userID}`;
                    });

                    workersList.appendChild(workerCard);
                });
            } else {
                workersList.innerHTML = '<p>No workers found.</p>';
            }
        }

        workerFieldDropdown.addEventListener('change', function () {
            const selectedField = this.value.toLowerCase();

            console.log("Selected field:", selectedField); // Debugging selected dropdown value

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

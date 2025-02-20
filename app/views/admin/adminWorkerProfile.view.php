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

        // PHP workers variable passed to JavaScript
        const workersObj = <?= json_encode($workers) ?>;

        // Convert the object with numerical keys into an array
        const workers = Object.values(workersObj);

        console.log("Workers data:", workers); // Debugging output to verify the conversion

        function renderWorkers(filteredWorkers) {
            workersList.innerHTML = ''; // Clear existing list

            if (filteredWorkers.length > 0) {
                filteredWorkers.forEach(worker => {
                    const workerCard = `
                        <div class="worker-card" data-firstname="${worker.firstName}" data-lastname="${worker.lastName}" data-role="${worker.role}">
                            <a href="<?=ROOT?>/public/admin/worker1">
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
                        </div>
                    `;
                    workersList.innerHTML += workerCard; 
                });

                document.querySelectorAll('.worker-card').forEach(card => {
            card.addEventListener('click', function (event) {
                event.preventDefault();
                const userID = this.dataset.userID;

                // Redirect to the URL with the userID as a query parameter
                window.location.href = '<?= htmlspecialchars(ROOT) ?>/public/admin/worker1';
            });
        });

            } else {
                workersList.innerHTML = '<p>No workers found.</p>';
            }
        }

        workerFieldDropdown.addEventListener('change', function () {
            const selectedField = this.value.toLowerCase(); // Convert to lowercase for case-insensitive comparison

            console.log("Selected field:", selectedField); // Debugging selected dropdown value

            // Filter workers based on selected role
            const filteredWorkers = selectedField === 'all'
                ? workers
                : workers.filter(worker => worker.role.toLowerCase() === selectedField.toLowerCase());

            renderWorkers(filteredWorkers);
        });

        // Render all workers on initial load
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

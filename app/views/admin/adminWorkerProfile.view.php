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
                            <a href="workerDetails">
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

                // Add click event listener to each worker card
                document.querySelectorAll('.worker-card').forEach(card => {
                    card.addEventListener('click', function (event) {
                        event.preventDefault();

                        // Create a form and submit it
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = '<?= htmlspecialchars(ROOT) ?>/public/admin/workerDetails';

                        const firstNameInput = document.createElement('input');
                        firstNameInput.type = 'hidden';
                        firstNameInput.name = 'firstName';
                        firstNameInput.value = this.dataset.firstname;

                        const lastNameInput = document.createElement('input');
                        lastNameInput.type = 'hidden';
                        lastNameInput.name = 'lastName';
                        lastNameInput.value = this.dataset.lastname;

                        const roleInput = document.createElement('input');
                        roleInput.type = 'hidden';
                        roleInput.name = 'role';
                        roleInput.value = this.dataset.role;

                        const imageInput = document.createElement('input');
                        imageInput.type = 'hidden';
                        imageInput.name = 'image';
                        imageInput.value = '<?= htmlspecialchars(ROOT) ?>/public/assets/images/user_icon.png';

                        form.appendChild(firstNameInput);
                        form.appendChild(lastNameInput);
                        form.appendChild(roleInput);
                        form.appendChild(imageInput);

                        document.body.appendChild(form);
                        form.submit();
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
                    <option value="all">All</option>
                    <option value="cook">Cook</option>
                    <option value="cook24">Cook 24-hour Live in</option>
                    <option value="nannies">Nanny</option>
                    <option value="all-rounder">All rounder</option>
                    <option value="cleaners">Maid</option>
                </select>
            </div>

            <div class="workers-list" id="workers-list">
                <!-- Workers will be rendered dynamically -->
            </div>
        </main>
    </div>
</body>
</html>

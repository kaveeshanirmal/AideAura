document.addEventListener('DOMContentLoaded', function () {
    const workerFieldDropdown = document.getElementById('worker-field');
    const workersList = document.getElementById('workers-list');

    const workers = Object.values(WORKERS_DATA);

    function renderWorkers(filteredWorkers) {
        workersList.innerHTML = '';

        if (filteredWorkers.length > 0) {
            filteredWorkers.forEach(worker => {
                const workerCard = document.createElement('div');
                workerCard.classList.add('worker-card');

                const workerInput = document.createElement('input');
                workerInput.type = "hidden";
                workerInput.name = "worker";
                workerInput.value = JSON.stringify(worker);

                workerCard.innerHTML = `
                    <div class="worker-info">
                        <div class="worker-avatar">
                            <img src="${ROOT_PATH}/public/assets/images/user_icon.png" alt="Worker Avatar">
                        </div>
                        <div class="worker-details">
                            <h3>${worker.firstName} ${worker.lastName}</h3>
                            <p>${worker.role}</p>
                        </div>
                    </div>
                `;

                workerCard.addEventListener('click', function () {
                    const form = document.createElement('form');
                    form.action = `${ROOT_PATH}/public/admin/workerDetails`;
                    form.method = "POST";

                    const input = document.createElement('input');
                    input.type = "hidden";
                    input.name = "workerData";
                    input.value = worker.userID;

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

document.addEventListener('DOMContentLoaded', function () {
    const workerFieldDropdown = document.getElementById('worker-field');
    const workersList = document.getElementById('workers-list');
    const paginationContainer = document.getElementById('pagination');
    
    // Pagination settings
    const workersPerPage = 4;
    let currentPage = 1;
    
    const workers = Object.values(WORKERS_DATA);
    let filteredWorkers = workers;
    
    function renderPagination(totalWorkers) {
        paginationContainer.innerHTML = '';
        
        // Calculate total pages
        const totalPages = Math.ceil(totalWorkers.length / workersPerPage);
        
        if (totalPages <= 1) {
            return; // Don't show pagination if only one page
        }
        
        // Add Previous button if not on first page
        if (currentPage > 1) {
            const prevButton = document.createElement('button');
            prevButton.textContent = 'Previous';
            prevButton.addEventListener('click', () => {
                currentPage--;
                renderWorkersList();
            });
            paginationContainer.appendChild(prevButton);
        }
        
        // Add page buttons
        for (let i = 1; i <= totalPages; i++) {
            const pageButton = document.createElement('button');
            pageButton.textContent = i;
            if (i === currentPage) {
                pageButton.classList.add('active');
            }
            pageButton.addEventListener('click', () => {
                currentPage = i;
                renderWorkersList();
            });
            paginationContainer.appendChild(pageButton);
        }
        
        // Add Next button if not on last page
        if (currentPage < totalPages) {
            const nextButton = document.createElement('button');
            nextButton.textContent = 'Next';
            nextButton.addEventListener('click', () => {
                currentPage++;
                renderWorkersList();
            });
            paginationContainer.appendChild(nextButton);
        }
    }
    
    function renderWorkersList() {
        workersList.innerHTML = '';
        
        if (filteredWorkers.length === 0) {
            workersList.innerHTML = '<p>No workers found.</p>';
            paginationContainer.innerHTML = '';
            return;
        }
        
        // Calculate pagination indexes
        const startIndex = (currentPage - 1) * workersPerPage;
        const endIndex = Math.min(startIndex + workersPerPage, filteredWorkers.length);
        
        // Get current page workers
        const currentWorkers = filteredWorkers.slice(startIndex, endIndex);
        
        // Render workers for current page
        currentWorkers.forEach(worker => {
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
                        <p>WorkerID : ${worker.workerID}</p>
                        <p>${worker.roleName}</p>
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
        
        // Update pagination
        renderPagination(filteredWorkers);
    }
    
    workerFieldDropdown.addEventListener('change', function () {
        const selectedField = this.value.toLowerCase();
        
        filteredWorkers = selectedField === 'all'
            ? workers
            : workers.filter(worker => worker.roleName.toLowerCase() === selectedField);
        
        // Reset to first page when filter changes
        currentPage = 1;
        renderWorkersList();
    });
    
    // Initial render
    renderWorkersList();
});
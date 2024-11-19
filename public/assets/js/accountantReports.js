document.addEventListener('DOMContentLoaded', function () {
    // Work Time Pie Chart
    new Chart(document.getElementById('workTimePieChart'), {
        type: 'pie',
        data: {
            labels: ['Work Hours', 'Other Hours'],
            datasets: [{
                data: [72.22, 27.78],
                backgroundColor: ['#4169E1', '#DDA0DD']
            }]
        },
        options: {
            responsive: true
        }
    });

    // Weekly Work Hours Bar Chart
    new Chart(document.getElementById('weeklyWorkChart'), {
        type: 'bar',
        data: {
            labels: ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'],
            datasets: [{
                label: 'Work Hours',
                data: [8, 7, 9, 8, 6, 5, 4],
                backgroundColor: '#4169E1'
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: { beginAtZero: true }
            }
        }
    });

    // Worker Category Sales Bar Chart
    new Chart(document.getElementById('workerCategoryBarChart'), {
        type: 'bar',
        data: {
            labels: ['Nannies', 'Cleaners', 'Drivers', 'Cooks'],
            datasets: [{
                label: 'Sales (in $)',
                data: [275000, 150000, 200000, 300000],
                backgroundColor: ['#4CAF50', '#FFC107', '#2196F3', '#FF5722']
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: { beginAtZero: true }
            }
        }
    });

    // Worker Category Sales Pie Chart
    new Chart(document.getElementById('workerCategoryPieChart'), {
        type: 'pie',
        data: {
            labels: ['Nannies', 'Cleaners', 'Drivers', 'Cooks'],
            datasets: [{
                data: [30, 20, 25, 25],
                backgroundColor: ['#4CAF50', '#FFC107', '#2196F3', '#FF5722']
            }]
        },
        options: {
            responsive: true
        }
    });
});

function generateReport() {
    alert("Generate Report functionality will be implemented here.");
}

function exportAsPDF() {
    alert("Export as PDF functionality will be implemented here.");
}

function generateCategoryReport() {
    alert("Generate Report for Category functionality will be implemented here.");
}

function exportCategoryPDF() {
    alert("Export as PDF for Category functionality will be implemented here.");
}

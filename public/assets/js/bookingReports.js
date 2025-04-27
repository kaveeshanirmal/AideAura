document.addEventListener('DOMContentLoaded', function() {
    // Initialize Chart.js
    let totalRevenueChart = null;
    let serviceRevenueChart = null;
    
    // Load initial data
    fetchTotalRevenue();
    
    // Add event listeners
    document.getElementById('filterBtn').addEventListener('click', function() {
        const activeSection = document.querySelector('.report-section.active');
        if (activeSection.id === 'totalRevenueSection') {
            fetchTotalRevenue();
        } else {
            fetchServiceTypeRevenue();
        }
    });
    
    document.getElementById('totalRevenueBtn').addEventListener('click', function() {
        switchReportType('totalRevenueSection');
        fetchTotalRevenue();
    });
    
    document.getElementById('serviceRevenueBtn').addEventListener('click', function() {
        switchReportType('serviceRevenueSection');
        fetchServiceTypeRevenue();
    });
    
    document.getElementById('exportTotalRevenueCSV').addEventListener('click', function() {
        exportTableToCSV('totalRevenueTable', 'Total_Revenue_Report.csv');
    });
    
    document.getElementById('exportServiceRevenueCSV').addEventListener('click', function() {
        exportTableToCSV('serviceRevenueTable', 'Service_Type_Revenue_Report.csv');
    });
    
    document.getElementById('exportTotalRevenuePDF').addEventListener('click', function() {
        exportToPDF('totalRevenueSection', 'Total_Revenue_Report.pdf');
    });
    
    document.getElementById('exportServiceRevenuePDF').addEventListener('click', function() {
        exportToPDF('serviceRevenueSection', 'Service_Type_Revenue_Report.pdf');
    });
    
    // Helper function to switch between report types
    function switchReportType(sectionId) {
        // Remove active class from all sections and buttons
        document.querySelectorAll('.report-section').forEach(section => {
            section.classList.remove('active');
        });
        document.querySelectorAll('.report-btn').forEach(btn => {
            btn.classList.remove('active');
        });
        
        // Add active class to the selected section and button
        document.getElementById(sectionId).classList.add('active');
        if (sectionId === 'totalRevenueSection') {
            document.getElementById('totalRevenueBtn').classList.add('active');
        } else {
            document.getElementById('serviceRevenueBtn').classList.add('active');
        }
    }
    
    // Fetch total revenue data
    function fetchTotalRevenue() {
        const startDate = document.getElementById('startDate').value;
        const endDate = document.getElementById('endDate').value;
        
        showLoading('totalRevenueSection');
        
        fetch(`http://localhost/AideAura/public/BookingReports/getTotalRevenue?startDate=${startDate}&endDate=${endDate}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    renderTotalRevenueReport(data.data);
                } else {
                    showError('totalRevenueSection', data.message || 'Failed to fetch total revenue data');
                }
                hideLoading('totalRevenueSection');
            })
            .catch(error => {
                console.error('Error fetching total revenue:', error);
                showError('totalRevenueSection', 'An error occurred while fetching data');
                hideLoading('totalRevenueSection');
            });
    }
    
    // Fetch service type revenue data
    function fetchServiceTypeRevenue() {
        const startDate = document.getElementById('startDate').value;
        const endDate = document.getElementById('endDate').value;
        
        showLoading('serviceRevenueSection');
        
        fetch(`http://localhost/AideAura/public/BookingReports/getServiceTypeRevenue?startDate=${startDate}&endDate=${endDate}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    renderServiceTypeRevenueReport(data.data);
                } else {
                    showError('serviceRevenueSection', data.message || 'Failed to fetch service type revenue data');
                }
                hideLoading('serviceRevenueSection');
            })
            .catch(error => {
                console.error('Error fetching service type revenue:', error);
                showError('serviceRevenueSection', 'An error occurred while fetching data');
                hideLoading('serviceRevenueSection');
            });
    }
    
    // Render total revenue report
    function renderTotalRevenueReport(data) {
        const tableBody = document.querySelector('#totalRevenueTable tbody');
        tableBody.innerHTML = '';
        
        // Prepare data for chart
        const labels = [];
        const revenueData = [];
        const bookingsData = [];
        
        let totalRevenue = 0;
        let totalBookings = 0;
        
        if (data.length === 0) {
            tableBody.innerHTML = '<tr><td colspan="4" class="no-data">No data available for the selected date range</td></tr>';
            updateTotalRevenueSummary(0, 0);
            renderTotalRevenueChart([], [], []);
            return;
        }
        
        data.forEach(item => {
            const monthYear = item.monthYear;
            const revenue = parseFloat(item.totalRevenue);
            const bookings = parseInt(item.totalBookings);
            const avgRevenue = revenue / bookings;
            
            totalRevenue += revenue;
            totalBookings += bookings;
            
            // Format month/year for display
            const [year, month] = monthYear.split('-');
            const date = new Date(year, month - 1);
            const formattedDate = date.toLocaleDateString('en-US', { month: 'short', year: 'numeric' });
            
            labels.push(formattedDate);
            revenueData.push(revenue);
            bookingsData.push(bookings);
            
            // Add row to table
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${formattedDate}</td>
                <td>Rs.${revenue.toFixed(2)}</td>
                <td>${bookings}</td>
                <td>Rs.${avgRevenue.toFixed(2)}</td>
            `;
            tableBody.appendChild(row);
        });
        
        // Update summary in table footer
        updateTotalRevenueSummary(totalRevenue, totalBookings);
        
        // Render chart
        renderTotalRevenueChart(labels, revenueData, bookingsData);
    }
    
    // Update total revenue summary in table footer
    function updateTotalRevenueSummary(totalRevenue, totalBookings) {
        document.getElementById('grandTotalRevenue').textContent = `Rs.${totalRevenue.toFixed(2)}`;
        document.getElementById('grandTotalBookings').textContent = totalBookings;
        
        const avgRevenue = totalBookings > 0 ? totalRevenue / totalBookings : 0;
        document.getElementById('grandAverageRevenue').textContent = `Rs.${avgRevenue.toFixed(2)}`;
    }
    
    // Render total revenue chart
    function renderTotalRevenueChart(labels, revenueData, bookingsData) {
        const ctx = document.getElementById('totalRevenueChart').getContext('2d');
        
        if (totalRevenueChart) {
            totalRevenueChart.destroy();
        }
        
        totalRevenueChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Revenue (Rs.)',
                        data: revenueData,
                        backgroundColor: 'rgba(75, 192, 192, 0.6)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1,
                        yAxisID: 'y'
                    },
                    {
                        label: 'Bookings',
                        data: bookingsData,
                        type: 'line',
                        backgroundColor: 'rgba(153, 102, 255, 0.6)',
                        borderColor: 'rgba(153, 102, 255, 1)',
                        borderWidth: 2,
                        yAxisID: 'y1'
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        type: 'linear',
                        display: true,
                        position: 'left',
                        title: {
                            display: true,
                            text: 'Revenue (Rs.)'
                        }
                    },
                    y1: {
                        type: 'linear',
                        display: true,
                        position: 'right',
                        title: {
                            display: true,
                            text: 'Number of Bookings'
                        },
                        grid: {
                            drawOnChartArea: false
                        }
                    }
                }
            }
        });
    }
    
    // Render service type revenue report
    function renderServiceTypeRevenueReport(data) {
        const tableBody = document.querySelector('#serviceRevenueTable tbody');
        tableBody.innerHTML = '';
        
        if (data.length === 0) {
            tableBody.innerHTML = '<tr><td colspan="5" class="no-data">No data available for the selected date range</td></tr>';
            updateServiceRevenueSummary(0, 0);
            renderServiceRevenueChart([], []);
            return;
        }
        
        // Group data by service type
        const serviceTypes = {};
        let totalRevenue = 0;
        let totalBookings = 0;
        
        data.forEach(item => {
            const serviceType = item.serviceType;
            const revenue = parseFloat(item.revenue);
            const bookings = parseInt(item.bookings);
            
            if (!serviceTypes[serviceType]) {
                serviceTypes[serviceType] = {
                    revenue: 0,
                    bookings: 0
                };
            }
            
            serviceTypes[serviceType].revenue += revenue;
            serviceTypes[serviceType].bookings += bookings;
            
            totalRevenue += revenue;
            totalBookings += bookings;
        });
        
        // Prepare data for chart and table
        const labels = [];
        const revenueData = [];
        
        // Add rows to table
        Object.keys(serviceTypes).forEach(serviceType => {
            const revenue = serviceTypes[serviceType].revenue;
            const bookings = serviceTypes[serviceType].bookings;
            const avgRevenue = revenue / bookings;
            const percentage = (revenue / totalRevenue) * 100;
            
            labels.push(serviceType);
            revenueData.push(revenue);
            
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${serviceType}</td>
                <td>Rs.${revenue.toFixed(2)}</td>
                <td>${bookings}</td>
                <td>Rs.${avgRevenue.toFixed(2)}</td>
                <td>${percentage.toFixed(2)}%</td>
            `;
            tableBody.appendChild(row);
        });
        
        // Update summary in table footer
        updateServiceRevenueSummary(totalRevenue, totalBookings);
        
        // Render chart
        renderServiceRevenueChart(labels, revenueData);
    }
    
    // Update service revenue summary in table footer
    function updateServiceRevenueSummary(totalRevenue, totalBookings) {
        document.getElementById('serviceTotalRevenue').textContent = `Rs.${totalRevenue.toFixed(2)}`;
        document.getElementById('serviceTotalBookings').textContent = totalBookings;
        
        const avgRevenue = totalBookings > 0 ? totalRevenue / totalBookings : 0;
        document.getElementById('serviceAverageRevenue').textContent = `Rs.${avgRevenue.toFixed(2)}`;
    }
    
    // Render service type revenue chart
    function renderServiceRevenueChart(labels, revenueData) {
        const ctx = document.getElementById('serviceRevenueChart').getContext('2d');
        
        if (serviceRevenueChart) {
            serviceRevenueChart.destroy();
        }
        
        // Generate colors for each service type
        const backgroundColors = [
            'rgba(75, 192, 192, 0.6)',
            'rgba(153, 102, 255, 0.6)',
            'rgba(255, 159, 64, 0.6)',
            'rgba(255, 99, 132, 0.6)',
            'rgba(54, 162, 235, 0.6)'
        ];
        
        const borderColors = [
            'rgba(75, 192, 192, 1)',
            'rgba(153, 102, 255, 1)',
            'rgba(255, 159, 64, 1)',
            'rgba(255, 99, 132, 1)',
            'rgba(54, 162, 235, 1)'
        ];
        
        serviceRevenueChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: labels,
                datasets: [{
                    data: revenueData,
                    backgroundColor: backgroundColors,
                    borderColor: borderColors,
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const value = context.raw;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = ((value / total) * 100).toFixed(2);
                                return `Rs.${value.toFixed(2)} (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        });
    }
    
    // Helper functions for loading state
    function showLoading(sectionId) {
        const section = document.getElementById(sectionId);
        // Check if loading element already exists
        if (!section.querySelector('.loading-overlay')) {
            const loadingDiv = document.createElement('div');
            loadingDiv.className = 'loading-overlay';
            loadingDiv.innerHTML = '<div class="loading-spinner"></div><p>Loading data...</p>';
            section.appendChild(loadingDiv);
        }
    }
    
    function hideLoading(sectionId) {
        const section = document.getElementById(sectionId);
        const loadingDiv = section.querySelector('.loading-overlay');
        if (loadingDiv) {
            section.removeChild(loadingDiv);
        }
    }
    
    function showError(sectionId, message) {
        const section = document.getElementById(sectionId);
        const errorDiv = document.createElement('div');
        errorDiv.className = 'error-message';
        errorDiv.textContent = message;
        
        // Remove existing error message if any
        const existingError = section.querySelector('.error-message');
        if (existingError) {
            section.removeChild(existingError);
        }
        
        section.appendChild(errorDiv);
        
        // Auto-hide error after 5 seconds
        setTimeout(() => {
            if (section.contains(errorDiv)) {
                section.removeChild(errorDiv);
            }
        }, 5000);
    }
    
    // Export table to CSV
    function exportTableToCSV(tableId, filename) {
        const table = document.getElementById(tableId);
        const rows = table.querySelectorAll('tr');
        
        let csv = [];
        for (let i = 0; i < rows.length; i++) {
            const row = [], cols = rows[i].querySelectorAll('td, th');
            
            for (let j = 0; j < cols.length; j++) {
                // Replace the ₹ symbol with nothing and clean the text
                let data = cols[j].innerText.replace('Rs.', '');
                // Escape double quotes and wrap with quotes
                data = '"' + data.replace(/"/g, '""') + '"';
                row.push(data);
            }
            
            csv.push(row.join(','));
        }
        
        downloadCSV(csv.join('\n'), filename);
    }
    
    function downloadCSV(csv, filename) {
        const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
        const link = document.createElement('a');
        
        // Create a URL for the blob
        const url = URL.createObjectURL(blob);
        
        link.setAttribute('href', url);
        link.setAttribute('download', filename);
        link.style.visibility = 'hidden';
        
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }
    
    // Export to PDF using window.print()
    function exportToPDF(sectionId, filename) {
        // Save current page state
        const originalContent = document.body.innerHTML;
        const originalTitle = document.title;
        
        // Change document title for PDF filename
        document.title = filename.replace('.pdf', '');
        
        // Get the section content
        const sectionContent = document.getElementById(sectionId).innerHTML;
        
        // Create a new page with only the section content and CSS
        const printContent = `
            <html>
            <head>
                <title>${document.title}</title>
                <style>
                    body {
                        font-family: Arial, sans-serif;
                        margin: 20px;
                    }
                    h1, h2, h3 {
                        color: #333;
                    }
                    .report-chart-container {
                        height: 400px;
                        margin-bottom: 30px;
                    }
                    .report-table {
                        width: 100%;
                        border-collapse: collapse;
                        margin-top: 15px;
                    }
                    .report-table th, .report-table td {
                        padding: 8px;
                        border: 1px solid #ddd;
                        text-align: left;
                    }
                    .report-table th {
                        background-color: #f2f2f2;
                    }
                    .report-table tfoot {
                        font-weight: bold;
                        background-color: #e9e9e9;
                    }
                    .export-buttons, .btn {
                        display: none;
                    }
                </style>
            </head>
            <body>
                <h1>Booking Revenue Report</h1>
                ${sectionContent}
            </body>
            </html>
        `;
        
        // Replace the current page content
        document.body.innerHTML = printContent;
        
        // Print the page
        window.print();
        
        // Restore the original page content
        document.body.innerHTML = originalContent;
        document.title = originalTitle;
    }
});
document.addEventListener('DOMContentLoaded', function() {
  // Set default date values (current month)
  setDefaultDates();
  
  // Initialize all charts with empty data
  initializeCharts();
  
  // Add event listeners to buttons
  document.getElementById('generate-worker-report').addEventListener('click', generateWorkerReport);
  document.getElementById('generate-service-report').addEventListener('click', generateServiceReport);
  document.getElementById('generate-revenue-report').addEventListener('click', generateRevenueReport);
  
  document.getElementById('export-worker-report').addEventListener('click', () => exportToPDF('worker'));
  document.getElementById('export-service-report').addEventListener('click', () => exportToPDF('service'));
  document.getElementById('export-revenue-report').addEventListener('click', () => exportToPDF('revenue'));
  
  // Generate initial reports with default values
  generateServiceReport();
  generateRevenueReport();
});

// Set default date values (current month)
function setDefaultDates() {
  const today = new Date();
  
  // Set start date to first day of current month
  const firstDay = new Date(today.getFullYear(), today.getMonth(), 1);
  
  // Set end date to today
  document.getElementById('worker-start-date').valueAsDate = firstDay;
  document.getElementById('worker-end-date').valueAsDate = today;
  document.getElementById('service-start-date').valueAsDate = firstDay;
  document.getElementById('service-end-date').valueAsDate = today;
  document.getElementById('revenue-start-date').valueAsDate = firstDay;
  document.getElementById('revenue-end-date').valueAsDate = today;
}

// Initialize charts with empty data
function initializeCharts() {
  // Booking Status Chart (Pie)
  const bookingStatusCtx = document.getElementById('bookingStatusChart').getContext('2d');
  window.bookingStatusChart = new Chart(bookingStatusCtx, {
      type: 'pie',
      data: {
          labels: [],
          datasets: [{
              data: [],
              backgroundColor: [
                  '#FFC107', // Pending - Yellow
                  '#2196F3', // Accepted - Blue
                  '#4CAF50', // Confirmed - Green
                  '#9C27B0', // Completed - Purple
                  '#F44336'  // Cancelled - Red
              ],
              borderWidth: 1
          }]
      },
      options: {
          responsive: true,
          plugins: {
              legend: {
                  position: 'bottom'
              },
              title: {
                  display: true,
                  text: 'Booking Status Distribution'
              }
          }
      }
  });

  // Weekly Bookings Chart (Bar)
  const weeklyBookingsCtx = document.getElementById('weeklyBookingsChart').getContext('2d');
  window.weeklyBookingsChart = new Chart(weeklyBookingsCtx, {
      type: 'bar',
      data: {
          labels: ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'],
          datasets: [{
              label: 'Number of Bookings',
              data: [],
              backgroundColor: '#4CAF50',
              borderWidth: 1
          }]
      },
      options: {
          responsive: true,
          scales: {
              y: {
                  beginAtZero: true,
                  title: {
                      display: true,
                      text: 'Number of Bookings'
                  }
              },
              x: {
                  title: {
                      display: true,
                      text: 'Day of Week'
                  }
              }
          }
      }
  });

  // Service Category Bar Chart
  const serviceCategoryBarCtx = document.getElementById('serviceCategoryBarChart').getContext('2d');
  window.serviceCategoryBarChart = new Chart(serviceCategoryBarCtx, {
      type: 'bar',
      data: {
          labels: [],
          datasets: [{
              label: 'Est. Total Cost ($)',
              data: [],
              backgroundColor: '#2196F3',
              borderWidth: 1
          }]
      },
      options: {
          responsive: true,
          scales: {
              y: {
                  beginAtZero: true,
                  title: {
                      display: true,
                      text: 'Est. Total Cost ($)'
                  }
              },
              x: {
                  title: {
                      display: true,
                      text: 'Service Category'
                  }
              }
          }
      }
  });

  // Service Category Pie Chart
  const serviceCategoryPieCtx = document.getElementById('serviceCategoryPieChart').getContext('2d');
  window.serviceCategoryPieChart = new Chart(serviceCategoryPieCtx, {
      type: 'pie',
      data: {
          labels: [],
          datasets: [{
              data: [],
              backgroundColor: [
                  '#FF9800', // Cook - Orange
                  '#F44336', // Cook 24h - Red
                  '#2196F3', // Maid - Blue 
                  '#4CAF50', // Nanny - Green
                  '#9C27B0'  // All-Rounder - Purple
              ],
              borderWidth: 1
          }]
      },
      options: {
          responsive: true,
          plugins: {
              legend: {
                  position: 'bottom'
              },
              title: {
                  display: true,
                  text: 'Service Category Distribution'
              }
          }
      }
  });

  // Revenue Trend Line Chart
  const revenueTrendCtx = document.getElementById('revenueTrendChart').getContext('2d');
  window.revenueTrendChart = new Chart(revenueTrendCtx, {
      type: 'line',
      data: {
          labels: [],
          datasets: [{
              label: 'Est. Total Cost',
              data: [],
              backgroundColor: 'rgba(75, 192, 192, 0.2)',
              borderColor: 'rgba(75, 192, 192, 1)',
              borderWidth: 2,
              tension: 0.4
          }]
      },
      options: {
          responsive: true,
          scales: {
              y: {
                  beginAtZero: true,
                  title: {
                      display: true,
                      text: 'Est. Total Cost ($)'
                  }
              },
              x: {
                  title: {
                      display: true,
                      text: 'Time Period'
                  }
              }
          }
      }
  });

  // Initialize summary stats with zeros
  updateSummaryStats({
      totalRevenue: 0,
      avgBookingValue: 0,
      totalBookings: 0
  });
}

// Generate Worker Performance Report
async function generateWorkerReport() {
  const workerID = document.getElementById('worker-id-input').value;
  const startDate = document.getElementById('worker-start-date').value;
  const endDate = document.getElementById('worker-end-date').value;
  
  // Validate input
  if (!workerID) {
      alert('Please enter a Worker ID');
      return;
  }
  
  console.log(`Generating worker report for Worker ID: ${workerID}, Period: ${startDate} to ${endDate}`);
  
  try {
      // Make AJAX call to get data from server
      const response = await fetchBookingData('worker_stats', {
          workerID: workerID,
          startDate: startDate,
          endDate: endDate
      });
      
      if (response.success) {
          const data = response.data;
          
          // Process status distribution data
          if (data.status_distribution && data.status_distribution.length > 0) {
              const statusLabels = data.status_distribution.map(item => item.status);
              const statusCounts = data.status_distribution.map(item => parseInt(item.count));
              
              // Update booking status chart
              updateBookingStatusChart(statusLabels, statusCounts);
          } else {
              // No data available
              updateBookingStatusChart(['No Data'], [1]);
          }
          
          // Process day of week data
          if (data.day_of_week && data.day_of_week.length > 0) {
              // Create an array for all days of the week
              const daysOfWeek = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
              const bookingCounts = Array(7).fill(0);
              
              // Fill in the counts for days with bookings
              data.day_of_week.forEach(item => {
                  // DAYOFWEEK in MySQL returns 1 for Sunday, 2 for Monday, etc.
                  const dayIndex = parseInt(item.day_num) - 1;
                  bookingCounts[dayIndex] = parseInt(item.count);
              });
              
              // Update weekly bookings chart
              updateWeeklyBookingsChart(daysOfWeek, bookingCounts);
          } else {
              // No data available
              const daysOfWeek = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
              updateWeeklyBookingsChart(daysOfWeek, Array(7).fill(0));
          }
          
          // Update summary stats if available
          if (data.summary) {
              updateSummaryStats({
                  totalRevenue: parseFloat(data.summary.total_cost || 0),
                  avgBookingValue: parseFloat(data.summary.avg_cost || 0),
                  totalBookings: parseInt(data.summary.total_bookings || 0)
              });
          }
      } else {
          console.error('Error fetching worker stats:', response.error);
          alert('Error fetching data. Please try again.');
      }
  } catch (error) {
      console.error('Error generating worker report:', error);
      alert('An error occurred while generating the report.');
  }
}

// Generate Service Category Report
async function generateServiceReport() {
  const serviceType = document.getElementById('service-type').value;
  const startDate = document.getElementById('service-start-date').value;
  const endDate = document.getElementById('service-end-date').value;
  
  console.log(`Generating service report for Service Type: ${serviceType}, Period: ${startDate} to ${endDate}`);
  
  try {
      // Make AJAX call to get data from server
      const response = await fetchBookingData('service_stats', {
          serviceType: serviceType,
          startDate: startDate,
          endDate: endDate
      });
      
      if (response.success) {
          const data = response.data;
          
          // Process category revenue data
          if (data.category_revenue && data.category_revenue.length > 0) {
              const categoryLabels = data.category_revenue.map(item => item.serviceType);
              const categoryCosts = data.category_revenue.map(item => parseFloat(item.total_cost));
              const categoryBookings = data.category_revenue.map(item => parseInt(item.booking_count));
              
              // Update service category bar chart
              updateServiceCategoryBarChart(categoryLabels, categoryCosts);
              
              // Update service category pie chart
              updateServiceCategoryPieChart(categoryLabels, categoryBookings);
          } else {
              // No data available
              updateServiceCategoryBarChart(['No Data'], [0]);
              updateServiceCategoryPieChart(['No Data'], [1]);
          }
          
          // Update summary stats if available
          if (data.summary) {
              updateSummaryStats({
                  totalRevenue: parseFloat(data.summary.total_cost || 0),
                  avgBookingValue: data.summary.total_bookings > 0 ? 
                      parseFloat(data.summary.total_cost) / parseInt(data.summary.total_bookings) : 0,
                  totalBookings: parseInt(data.summary.total_bookings || 0)
              });
          }
      } else {
          console.error('Error fetching service stats:', response.error);
          alert('Error fetching data. Please try again.');
      }
  } catch (error) {
      console.error('Error generating service report:', error);
      alert('An error occurred while generating the report.');
  }
}

// Generate Revenue Report
async function generateRevenueReport() {
  const period = document.getElementById('period-select').value;
  const startDate = document.getElementById('revenue-start-date').value;
  const endDate = document.getElementById('revenue-end-date').value;
  
  console.log(`Generating revenue report for Period Type: ${period}, Date Range: ${startDate} to ${endDate}`);
  
  try {
      // Make AJAX call to get data from server
      const response = await fetchBookingData('revenue_trend', {
          period: period,
          startDate: startDate,
          endDate: endDate
      });
      
      if (response.success) {
          const data = response.data;
          
          // Process trend data
          if (data.trend_data && data.trend_data.length > 0) {
              const labels = data.trend_data.map(item => item.period_label);
              const costs = data.trend_data.map(item => parseFloat(item.total_cost));
              
              // Update revenue trend chart
              updateRevenueTrendChart(labels, costs);
          } else {
              // No data available
              updateRevenueTrendChart(['No Data'], [0]);
          }
          
          // Update summary stats if available
          if (data.summary) {
              updateSummaryStats({
                  totalRevenue: parseFloat(data.summary.total_cost || 0),
                  avgBookingValue: parseFloat(data.summary.avg_cost || 0),
                  totalBookings: parseInt(data.summary.total_bookings || 0)
              });
          }
      } else {
          console.error('Error fetching revenue trend:', response.error);
          alert('Error fetching data. Please try again.');
      }
  } catch (error) {
      console.error('Error generating revenue report:', error);
      alert('An error occurred while generating the report.');
  }
}

// Update Booking Status Chart
function updateBookingStatusChart(labels, data) {
  window.bookingStatusChart.data.labels = labels;
  window.bookingStatusChart.data.datasets[0].data = data;
  window.bookingStatusChart.update();
}

// Update Weekly Bookings Chart
function updateWeeklyBookingsChart(labels, data) {
  window.weeklyBookingsChart.data.labels = labels;
  window.weeklyBookingsChart.data.datasets[0].data = data;
  window.weeklyBookingsChart.update();
}

// Update Service Category Bar Chart
function updateServiceCategoryBarChart(labels, data) {
  window.serviceCategoryBarChart.data.labels = labels;
  window.serviceCategoryBarChart.data.datasets[0].data = data;
  window.serviceCategoryBarChart.update();
}

// Update Service Category Pie Chart
function updateServiceCategoryPieChart(labels, data) {
  window.serviceCategoryPieChart.data.labels = labels;
  window.serviceCategoryPieChart.data.datasets[0].data = data;
  window.serviceCategoryPieChart.update();
}

// Update Revenue Trend Chart
function updateRevenueTrendChart(labels, data) {
  window.revenueTrendChart.data.labels = labels;
  window.revenueTrendChart.data.datasets[0].data = data;
  window.revenueTrendChart.update();
}

// Update Summary Stats
function updateSummaryStats(stats) {
  document.getElementById('total-revenue').textContent = `$${parseFloat(stats.totalRevenue).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2})}`;
  document.getElementById('avg-booking-value').textContent = `$${parseFloat(stats.avgBookingValue).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2})}`;
  document.getElementById('total-bookings').textContent = stats.totalBookings;
}

// Export charts to PDF
function exportToPDF(reportType) {
  // Use jsPDF library
  const { jsPDF } = window.jspdf;
  const doc = new jsPDF();
  
  // Set document properties
  const title = `${reportType.charAt(0).toUpperCase() + reportType.slice(1)} Booking Report`;
  doc.setProperties({
      title: title,
      author: 'Booking System',
      subject: 'Booking Reports',
      keywords: 'booking, report, statistics'
  });
  
  // Add title
  doc.setFontSize(18);
  doc.text(title, 105, 15, { align: 'center' });
  
  // Add date range
  doc.setFontSize(12);
  let startDate, endDate;
  
  switch(reportType) {
      case 'worker':
          startDate = document.getElementById('worker-start-date').value;
          endDate = document.getElementById('worker-end-date').value;
          
          doc.text(`Worker ID: ${document.getElementById('worker-id-input').value}`, 14, 25);
          break;
      case 'service':
          startDate = document.getElementById('service-start-date').value;
          endDate = document.getElementById('service-end-date').value;
          
          doc.text(`Service Type: ${document.getElementById('service-type').value}`, 14, 25);
          break;
      case 'revenue':
          startDate = document.getElementById('revenue-start-date').value;
          endDate = document.getElementById('revenue-end-date').value;
          
          doc.text(`Period: ${document.getElementById('period-select').value}`, 14, 25);
          break;
  }
  
  doc.text(`Date Range: ${startDate} to ${endDate}`, 14, 32);
  doc.text(`Generated on: ${new Date().toLocaleString()}`, 14, 39);
  
  // Add summary stats
  doc.setFontSize(14);
  doc.text('Summary', 14, 50);
  
  doc.setFontSize(12);
  doc.text(`Total Est. Cost: ${document.getElementById('total-revenue').textContent}`, 14, 58);
  doc.text(`Avg. Booking Value: ${document.getElementById('avg-booking-value').textContent}`, 14, 65);
  doc.text(`Total Bookings: ${document.getElementById('total-bookings').textContent}`, 14, 72);
  
  // Add charts as images
  let startY = 85;
  
  if (reportType === 'worker') {
      // Add booking status chart
      doc.text('Booking Status Distribution', 105, startY - 5, { align: 'center' });
      const statusChartImg = document.getElementById('bookingStatusChart').toDataURL('image/png');
      doc.addImage(statusChartImg, 'PNG', 55, startY, 100, 70);
      
      // Add weekly bookings chart
      startY += 80;
      doc.text('Bookings Per Day', 105, startY - 5, { align: 'center' });
      const weeklyChartImg = document.getElementById('weeklyBookingsChart').toDataURL('image/png');
      doc.addImage(weeklyChartImg, 'PNG', 55, startY, 100, 70);
  }
  else if (reportType === 'service') {
      // Add service category bar chart
      doc.text('Est. Total Cost by Service Category', 105, startY - 5, { align: 'center' });
      const barChartImg = document.getElementById('serviceCategoryBarChart').toDataURL('image/png');
      doc.addImage(barChartImg, 'PNG', 55, startY, 100, 70);
      
      // Add service category pie chart
      startY += 80;
      doc.text('Service Category Distribution', 105, startY - 5, { align: 'center' });
      const pieChartImg = document.getElementById('serviceCategoryPieChart').toDataURL('image/png');
      doc.addImage(pieChartImg, 'PNG', 55, startY, 100, 70);
  }
  else if (reportType === 'revenue') {
      // Add revenue trend chart
      doc.text('Est. Total Cost Trend', 105, startY - 5, { align: 'center' });
      const trendChartImg = document.getElementById('revenueTrendChart').toDataURL('image/png');
      doc.addImage(trendChartImg, 'PNG', 20, startY, 170, 80);
  }
  
  // Save the PDF
  doc.save(`${reportType}_booking_report.pdf`);
}

// Fetch booking data from server
// Fetch booking data from server
async function fetchBookingData(endpoint, params) {
  try {
    
      const response = await fetch(`${ROOT}/public/admin/${endpoint}`, {
          method: 'POST',
          headers: {
              'Content-Type': 'application/json',
          },
          body: JSON.stringify(params)
      });
      
      if (!response.ok) {
          throw new Error(`HTTP error! Status: ${response.status}`);
      }
      
      return await response.json();
  } catch (error) {
      console.error('Error fetching data:', error);
      
      // If there's an error with the API, return a formatted error response
      return {
          success: false,
          error: error.message
      };
  }
}


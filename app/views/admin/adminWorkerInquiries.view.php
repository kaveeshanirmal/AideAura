<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Complaints Management</title>
    <link rel="stylesheet" href="<?=ROOT?>/public/assets/css/adminWorkerInquiries.css">
</head>
<body>
    <div class="dashboard-container">
        <?php include(ROOT_PATH . '/app/views/components/admin_navbar.view.php'); ?>
        <div class="container">
            <div class="main-content">
                <div class="filters-container">
                    <label for="issueFilter">Filter by Issue Type:</label>
                    <select id="issueFilter">
                        <option value="all">All</option>
                        <option value="general-inquiry">General Inquiry</option>
                        <option value="feedback">Feedback/Suggestions</option>
                        <option value="worker-unavailability">Worker Unavailability</option>
                        <option value="worker-misconduct">Worker Misconduct</option>
                        <option value="unable-to-book">Unable to Book</option>
                        <option value="failed-payment">Failed Payment</option>
                        <!-- Add remaining options -->
                    </select>

                    <label for="prioritySort">Sort by Priority:</label>
                    <select id="prioritySort">
                        <option value="none">None</option>
                        <option value="high-to-low">High to Low</option>
                        <option value="low-to-high">Low to High</option>
                    </select>
                </div>

                <table class="complaints-table">
                    <thead>
                        <tr>
                            <th>Customer ID</th>
                            <th>Issue Type</th>
                            <th>Description</th>
                            <th>Priority</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="complaintsTableBody">
                        <!-- Dynamic complaint rows will be injected here -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        // Dummy data for complaints
        const complaints = [
            {
                customerID: "12",
                issueType: "failed-payment",
                description: "Payment failed during checkout.",
                priority: "Critical",
                date: "2024-11-20"
            },
            {
                customerID: "14",
                issueType: "general-inquiry",
                description: "Inquiry about service availability.",
                priority: "Low",
                date: "2024-11-22"
            },
            {
                customerID: "21",
                issueType: "worker-misconduct",
                description: "Complaint about worker behavior.",
                priority: "High",
                date: "2024-11-23"
            },
            // Add more dummy data
        ];

        // Function to render complaints
        const renderComplaints = (data) => {
            const tableBody = document.getElementById('complaintsTableBody');
            tableBody.innerHTML = "";
            data.forEach(complaint => {
                const row = `
                    <tr>
                        <td>${complaint.customerID}</td>
                        <td>${complaint.issueType}</td>
                        <td>${complaint.description}</td>
                        <td>${complaint.priority}</td>
                        <td>${complaint.date}</td>
                        <td><button class="reply-button">Reply</button></td>
                    </tr>
                `;
                tableBody.innerHTML += row;
            });
        };

        // Initial render
        renderComplaints(complaints);

        // Filter and sorting logic
        document.getElementById('issueFilter').addEventListener('change', (e) => {
            const filterValue = e.target.value;
            let filteredData = complaints;

            if (filterValue !== "all") {
                filteredData = complaints.filter(c => c.issueType === filterValue);
            }

            renderComplaints(filteredData);
        });

        document.getElementById('prioritySort').addEventListener('change', (e) => {
            const sortValue = e.target.value;
            let sortedData = [...complaints];

            if (sortValue === "high-to-low") {
                sortedData.sort((a, b) => priorityValue(b.priority) - priorityValue(a.priority));
            } else if (sortValue === "low-to-high") {
                sortedData.sort((a, b) => priorityValue(a.priority) - priorityValue(b.priority));
            }

            renderComplaints(sortedData);
        });

        // Function to map priority to numeric value
        const priorityValue = (priority) => {
            if (priority === "Critical") return 3;
            if (priority === "High") return 2;
            if (priority === "Medium") return 1;
            if (priority === "Low") return 0;
            return -1; // For undefined priorities
        };
    </script>
</body>
</html>

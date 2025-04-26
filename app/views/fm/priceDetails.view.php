<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>fm payment rates</title>
    <link rel="stylesheet" href="<?=ROOT?>/public/assets/css/accountantPriceDetails.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- <script src="<?=ROOT?>/public/assets/js/admin/adminPayrate.js"></script> -->

    
</head>
<body>
        <!-- Notification container -->
        <div id="notification" class="notification hidden"></div>
    <div class="dashboard-container">
        <?php include(ROOT_PATH . '/app/views/components/employeeNavbar.view.php'); ?>
        <div class="main-content">
            <!-- <div class="employee-details">
                <button class="add-employee-btn">
                    <a href="ROOT/public/Admin/adminPayratePriceAdd">New Price Details</a>
                </button>
            </div> -->
            <div class="table-container">
                <table class="employee-table">
                    <thead>
                        <tr>
                            <th>Detail Name</th>
                            <th>Description</th>
                            <th>Role</th>
                            <th>Category Name</th>
                            <th>Category Description</th>
                            <th>Category Display Name</th>
                            <th>UpdateAt</th></th>
                            <th>Price</th>
                            <!-- <th>CreateAt</th> -->
                            <th>Update</th>
                            <!-- <th>Delete</th> -->
                        </tr>
                    </thead>
                    <tbody id="employeeTableBody">
                    <?php if (empty($priceData)): ?>
        <tr>
            <td colspan="10" style="text-align: center; font-style: italic;">
                No prices data found.
            </td>
        </tr>
    <?php else: ?>
                        
                        <?php foreach ($priceData as $priceD): ?>
                        <tr data-detailID="<?= htmlspecialchars($priceD->detailID) ?>" data-catergoryID="<?= htmlspecialchars($priceD->categoryID) ?>" data-detailName="<?= htmlspecialchars($priceD->detailName) ?>" data-price="<?= htmlspecialchars($priceD->price) ?>" data-description="<?= htmlspecialchars($priceD->description) ?>" data-categoryDisplayName="<?= htmlspecialchars($priceD->categoryDisplayName) ?>" data-roleName="<?= htmlspecialchars($priceD->roleName) ?>" data-categoryName="<?= htmlspecialchars($priceD-> categoryName) ?>" data-categoryDescription="<?= htmlspecialchars($priceD->categoryDescription) ?>" data-updateAt="<?= htmlspecialchars($priceD->updateAt) ?>" data-createdAt="<?= htmlspecialchars($priceD->createdAt) ?>">
                            <td><?= htmlspecialchars($priceD->detailName) ?></td>
                            <td><?= htmlspecialchars($priceD->description) ?></td>
                            <td><?= htmlspecialchars($priceD->roleName) ?></td>
                            <td><?= htmlspecialchars($priceD-> categoryName) ?></td>
                            <td><?= htmlspecialchars($priceD->categoryDescription) ?></td>
                            <td><?= htmlspecialchars($priceD->categoryDisplayName) ?></td>
                            <td><?= htmlspecialchars($priceD->updatedAt) ?></td>
                            <td><?= htmlspecialchars($priceD->price) ?></td>

                            <!-- <td> htmlspecialchars($priceD->createdAt) ?></td> -->
                            <td>
                                <button class="update-btn" onclick="showUpdateModal(this)">
                                    <i class="fas fa-sync-alt"></i>
                                </button>
                            </td>
                            <!-- <td>
                                <button class="delete-btn" onclick="deleteEmployee(' $priceD->detailID ?>')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td> -->
                        </tr>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Update Modal -->
    <div id="updateModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeUpdateModal()">&times;</span>
            <h2 class="topic">Update price Details</h2>
            <form id="updatePriceDetailForm" onsubmit="event.preventDefault(); updatePriceDetail();">
                <input type="hidden" id="updatedetailID">
                <!-- <input type="hidden" id="updatecategoryID"> -->
                
                <div class="form-item">
                    <label for="updatedetailName">DetailName:</label>
                    <input class="inputc1" type="text" id="updatedetailName">
                </div>
                <div class="form-item">
                    <label for="updatefirstName">Price:</label>
                    <input class="inputc2" type="text" id="updateprice">
                </div>
                <div class="form-item">
                    <label for="updatelastName">Description:</label>
                    <input class="inputc3" type="text" id="updatedescription">
                </div>
                <!-- <div class="form-item">
                    <label for="updatelastName">Category Name:</label>
                    <input class="inputc3" type="text" id="updateCategoryName">
                </div>
                <div class="form-item">
                    <label for="updatelastName">Category Description:</label>
                    <input class="inputc3" type="text" id="updateCategoryDescription">
                </div>
                <div class="form-item">
                    <label for="updatelastName">Category Display Name:</label>
                    <input class="inputc3" type="text" id="updateCategoryDisplayName">
                </div> -->
                <button type="submit">Update</button>
            </form>
        </div>
    </div>

    <script>

const priceData = <?= isset($priceData) ? json_encode($priceData) : '[]' ?>;
console.log("Price data:", priceData);

        // Notification Functionality
        const notification = document.getElementById('notification');
        const showNotification = (message, type) => {
            notification.textContent = message;
            notification.className = `notification ${type} show`;
            setTimeout(() => notification.className = 'notification hidden', 2000);
        };

        // Show Update Modal
        function showUpdateModal(button) {
            const row = button.closest('tr');
            const detailID = row.getAttribute('data-detailID');
            // const categoryID = row.getAttribute('data-catergoryID');
            const detailName = row.getAttribute('data-detailName');
            const price = row.getAttribute('data-price');
            const description = row.getAttribute('data-description');
            // const categoryName = row.getAttribute('data-categoryName');
            // const categoryDescription = row.getAttribute('data-categoryDescription');
            // const categoryDisplayName = row.getAttribute('data-categoryDisplayName');

            document.getElementById('updatedetailID').value = detailID;
            // document.getElementById('updatecategoryID').value = categoryID;
            document.getElementById('updatedetailName').value = detailName;
            document.getElementById('updateprice').value = price;
            document.getElementById('updatedescription').value = description;
            // document.getElementById('updateCategoryName').value = categoryName;
            // document.getElementById('updateCategoryDescription').value = categoryDescription;
            // document.getElementById('updateCategoryDisplayName').value = categoryDisplayName;

            document.getElementById('updateModal').style.display = 'block';
        }

        function closeUpdateModal() {
            document.getElementById('updateModal').style.display = 'none';
        }

        function updatePriceDetail() {
            try {
            const detailID = document.getElementById('updatedetailID').value;
            // const categoryID = document.getElementById('updatecategoryID').value;
            const data = {
                detailID,
                // categoryID,
                detailName: document.getElementById('updatedetailName').value,
                price: document.getElementById('updateprice').value,
                description: document.getElementById('updatedescription').value,
                // categoryName: document.getElementById('updateCategoryName').value,
                // categoryDescription: document.getElementById('updateCategoryDescription').value,
                // categoryDisplayName: document.getElementById('updateCategoryDisplayName').value,
            };

            console.log("Data sent:", data); // Debugging line

            fetch('<?=ROOT?>/public/FinanceManager/updatePriceDetails', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data),
            })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    closeUpdateModal();
                    showNotification('Price updated successfully', 'success');
                    setTimeout(() => location.reload(), 2000);
                } else {
                    showNotification('Update failed', 'error');
                }
            })
        } catch{
            (error => showNotification('An unexpected error occurred', 'error'));
        }
        }

    //     function deleteEmployee(userID) {
    //         try {
    //         if (!confirm('Are you sure you want to delete this employee?')) return;

    //         fetch('ROOT/public/adminEmployees/delete', {
    //             method: 'POST',
    //             headers: { 'Content-Type': 'application/json' },
    //             body: JSON.stringify({ userID }),
    //         })
    //         .then(response => response.json())
    //         .then(result => {
    //             if (result.success) {
    //                 showNotification('Employee deleted successfully', 'success');
    //                 setTimeout(() => location.reload(), 2000);
    //             } else {
    //                 showNotification('Delete failed', 'error');
    //             }
    //         })
    //     } catch {
    //      (error => showNotification('An unexpected error occurred', 'error'));
    //     }
    // }

//     // Global array to store all employees
//     let allEmployees = [];

//     // Function to load all employees initially
//     function loadEmployees() {
//         try {
//         fetch('ROOT/public/adminEmployees/all')
//             .then(response => response.json())
//             .then(result => {
//                 if (result.success) {
//                     allEmployees = result.employees;
//                     renderTable(allEmployees);
//                 } else {
//                     showNotification('Failed to load employees', 'error');
//                 }
//             })
//         } catch {
//             (error => showNotification('An unexpected error occurred', 'error'));
//     }
// }


// Function to reset the search filters and reload all employees
// Simplified function to reset the search filters
// function resetEmployees() {
//     // Simply reload the page to show all employees again
//     location.reload();
// }


//     // Load employees on page load
//     window.onload = () => {
//     loadEmployees();
// };
    </script>

</body>
</html>
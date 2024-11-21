<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Management</title>
    <link rel="stylesheet" href="<?=ROOT?>/public/assets/css/adminEmployees.css">
    <!-- Include Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="dashboard-container">
    <?php include(ROOT_PATH . '/app/views/components/admin_sidebar.view.php'); ?>
        
        <div class="main-content">
        <?php include(ROOT_PATH . '/app/views/components/admin_navbar.view.php'); ?>

                <div class="employee-controls">
                    <div class="search-filters">
                        <div class="input-group">
                            <label>Employee Name:</label>
                            <input type="text" value="Mr.Kamal Gunarathne" class="employee-input">
                        </div>
                        <div class="input-group">
                            <label>Role:</label>
                            <select class="role-select">
                                <option selected>Accountant</option>
                                <option>Manager</option>
                                <option>Developer</option>
                            </select>
                        </div>
                        <div class="input-group">
                            <label>Status:</label>
                            <select class="status-select">
                                <option selected>Active</option>
                                <option>Inactive</option>
                            </select>
                        </div>
                    </div>

                    <button class="add-employee-btn">
                    <a href="<?=ROOT?>/public/adminEmployeeAdd">Add Employee</a>
                    </button>
                </div>

                <div class="employee-details">
                    <div class="input-group">
                        <label>Employee ID:</label>
                        <input type="text" value="#158963257" class="id-input" readonly>
                    </div>
                    <div class="input-group">
                        <label>Email:</label>
                        <input type="email" value="kamal1234@gamil.com" class="email-input">
                    </div>
                    <div class="search-btn-container">
                        <button class="search-btn">Search</button>
                    </div>
                </div>

                <div class="table-container">
                    <table class="employee-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Role</th>
                                <th>Email Address</th>
                                <th>Contact</th>
                                <th>Password</th>
                                <th>Date Of Hire</th>
                                <th>Status</th>
                                <th>Update</th>
                                <th>Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>#158963257</td>
                                <td>Mr.Kamal Gunarathne</td>
                                <td>Accountant</td>
                                <td>kamal1234@gamil.com</td>
                                <td>0758964501</td>
                                <td>KamGun#543</td>
                                <td>2024.8.17</td>
                                <td><span class="status-active">Active</span></td>
                                <td>
                                    <button class="update-btn">
                                        <i class="fas fa-sync-alt"></i>
                                    </button>
                                </td>
                                <td>
                                    <button class="delete-btn">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>

                        <tbody>
                            <tr>
                                <td>#158963257</td>
                                <td>Mr.Kamal Gunarathne</td>
                                <td>Accountant</td>
                                <td>kamal1234@gamil.com</td>
                                <td>0758964501</td>
                                <td>KamGun#543</td>
                                <td>2024.8.17</td>
                                <td><span class="status-active">Active</span></td>
                                <td>
                                    <button class="update-btn">
                                        <i class="fas fa-sync-alt"></i>
                                    </button>
                                </td>
                                <td>
                                    <button class="delete-btn">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>

                        <tbody>
                            <tr>
                                <td>#158963257</td>
                                <td>Mr.Kamal Gunarathne</td>
                                <td>Accountant</td>
                                <td>kamal1234@gamil.com</td>
                                <td>0758964501</td>
                                <td>KamGun#543</td>
                                <td>2024.8.17</td>
                                <td><span class="status-active">Active</span></td>
                                <td>
                                    <button class="update-btn">
                                        <i class="fas fa-sync-alt"></i>
                                    </button>
                                </td>
                                <td>
                                    <button class="delete-btn">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>

                        <tbody>
                            <tr>
                                <td>#158963257</td>
                                <td>Mr.Kamal Gunarathne</td>
                                <td>Accountant</td>
                                <td>kamal1234@gamil.com</td>
                                <td>0758964501</td>
                                <td>KamGun#543</td>
                                <td>2024.8.17</td>
                                <td><span class="status-active">Active</span></td>
                                <td>
                                    <button class="update-btn">
                                        <i class="fas fa-sync-alt"></i>
                                    </button>
                                </td>
                                <td>
                                    <button class="delete-btn">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>

                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
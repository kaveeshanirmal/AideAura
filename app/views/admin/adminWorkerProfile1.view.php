<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Worker Profile Update</title>
    <link rel="stylesheet" href="<?=ROOT?>/public/assets/css/adminWorkerProfile1.css">
</head>
<body>
    <div class="dashboard-container">
        <?php include(ROOT_PATH . '/app/views/components/employeeNavbar.view.php'); ?>
    <main class="main-content">
            <div class="back-header">
                <a href="workers" class="back-button">‹</a>
                <div class="worker-header">
                    <div class="worker-avatar">
                        <img src="<?=ROOT?>/public/assets/images/user_icon.png" alt="Worker Avatar">
                    </div>
                    <div class="worker-title">
                        <h2>MR. <?= htmlspecialchars($worker['fullName']) ?></h2>
                        <p><?= htmlspecialchars($worker['role']) ?></p>
                    </div>
                </div>
            </div>

            <div class="worker-details-container">
                <div class="details-grid">
                    <div class="detail-item">
                        <span class="label">Full Name :</span>
                        <span class="value"><?= htmlspecialchars($worker['fullName']) ?></span>
                    </div>
                    <div class="detail-item">
                        <span class="label">User Name :</span>
                        <span class="value"><?= htmlspecialchars($worker['username']) ?></span>
                    </div>
                    <div class="detail-item">
                        <span class="label">Nationality :</span>
                        <span class="value"><?= htmlspecialchars($worker['Nationality']) ?></span>
                    </div>
                    <div class="detail-item">
                        <span class="label">Gender :</span>
                        <span class="value"><?= htmlspecialchars($worker['Gender']) ?></span>
                    </div>
                    <div class="detail-item">
                        <span class="label">Contact :</span>
                        <span class="value"><?= htmlspecialchars($worker['Contact']) ?></span>
                    </div>
                    <div class="detail-item">
                        <span class="label">Email :</span>
                        <span class="value"><?= htmlspecialchars($worker['email']) ?></span>
                    </div>
                    <div class="detail-item">
                        <span class="label">NIC/Passport No :</span>
                        <span class="value"><?= htmlspecialchars($worker['NIC']) ?></span>
                    </div>
                    <div class="detail-item">
                        <span class="label">Age : </span>
                        <span class="value"><?= htmlspecialchars($worker['Age']) ?></span>
                    </div>
                    <div class="detail-item">
                        <span class="label">Skilled or Specialized Areas :</span>
                        <span class="value"><?= htmlspecialchars($worker['ServiceType']) ?></span>
                    </div>
                    <div class="detail-item">
                        <span class="label">Spoken Languages :</span>
                        <span class="value"><?= htmlspecialchars($worker['SpokenLanguages']) ?></span>
                    </div>
                    <div class="detail-item">
                        <span class="label">Work Locations :</span>
                        <span class="value"><?= htmlspecialchars($worker['WorkLocations']) ?></span>
                    </div>
                    <div class="detail-item">
                        <span class="label">Experience Level :</span>
                        <span class="value"><?= htmlspecialchars($worker['ExperienceLevel']) ?></span>
                    </div>

                    <div class="detail-item">
                        <span class="label">Allergies or Physical Limitations :</span>
                        <span class="value"><?= htmlspecialchars($worker['AllergiesOrPhysicalLimitations']) ?></span>
                    </div>

                    <div class="detail-item">
                        <span class="label">Description :</span>
                        <span class="value"><?= htmlspecialchars($worker['Description']) ?></span>
                    </div>

                    <div class="detail-item">
                        <span class="label">Home Town :</span>
                        <span class="value"><?= htmlspecialchars($worker['HomeTown']) ?></span>
                    </div>
                    <div class="detail-item">
                        <span class="label">Bank Name and Branch Code :</span>
                        <span class="value"><?= htmlspecialchars($worker['BankNameAndBranchCode']) ?></span>
                    </div>
                    <div class="detail-item">
                        <span class="label">Bank Account Number : </span>
                        <span class="value"><?= htmlspecialchars($worker['BankAccountNumber']) ?></span>
                    </div>
                    <div class="detail-item">
                        <span class="label">Working Week Days : </span>
                        <span class="value"><?= htmlspecialchars($worker['WorkingWeekDays']) ?></span>
                    </div>
                    <div class="detail-item">
                        <span class="label">Working Week Ends : </span>
                        <span class="value"><?= htmlspecialchars($worker['WorkingWeekEnds']) ?></span>
                    </div>
                    <div class="detail-item">
                        <span class="label">Notes : </span>
                        <span class="value"><?= htmlspecialchars($worker['Notes']) ?></span>
                    </div>
                    <div class="detail-item">
                        <span class="label">Verification Status :</span>
                        <span class="value"><?= htmlspecialchars($worker['Status']) ?></span>
                    </div>
                </div>
            </div>

            <div class="action-buttons">
                <button class="btn update" onclick="openUpdateModal()">Update</button>
                <button class="btn schedule"><a href="workerSchedule">Availability schedule</a></button>
                <button class="btn certificates"><a href="workerCertificates">Certificates</a></button>
            </div>

            <!-- Update Modal -->
            <div id="updateModal" class="modal">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2>Update Worker Profile</h2>
                        <button class="close-btn" onclick="closeUpdateModal()">&times;</button>
                    </div>
                    <form id="workerUpdateForm" class="update-form">
                        <div class="form-group">
                            <label>Full Name</label>
                            <input type="text" name="full_name" value="<?= htmlspecialchars($worker['firstName'] . ' ' . $worker['lastName']) ?>">
                        </div>
                        <div class="form-group">
                            <label>User Name</label>
                            <input type="text" name="user_name" value="<?= htmlspecialchars($worker['username'] 
                            ) ?>">
                        </div>            
                        <div class="form-group">
                            <label>Date of Birth</label>
                            <input type="date" name="date_of_birth" value="N/A">
                        </div>
                        <div class="form-group">
                            <label>Nationality</label>
                            <input type="text" name="nationality" value="N/A">
                        </div>
                        <div class="form-group">
                            <label>Gender</label>
                            <select name="gender">
                                <option value="Male" selected>Male</option>
                                <option value="Female">Female</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Contact Number</label>
                            <input type="tel" name="contact" value="<?= htmlspecialchars($worker['phone']) ?>">
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" name="email" value="<?= htmlspecialchars($worker['email']) ?>">
                        </div>
                        <div class="form-group">
                            <label>ID/Passport No</label>
                            <input type="text" name="id_passport" value="N/A">
                        </div>
                        <div class="form-group">
                            <label>Work Area/Location</label>
                            <input type="text" name="work_location" value="N/A">
                        </div>
                        <div class="form-group">
                            <label>Address</label>
                            <input type="text" name="address" value="N/A">
                        </div>
                        <div class="form-group">
                            <label>Bank Details</label>
                            <input type="text" name="bank_details" value="N/A">
                        </div>
                        <div class="form-group">
                            <label>Job Preferences</label>
                            <textarea name="job_preferences">N/A</textarea>
                        </div>
                        <div class="form-actions">
                            <button type="button" class="btn btn-cancel" onclick="closeUpdateModal()">Cancel</button>
                            <button type="submit" class="btn btn-update">Update Profile</button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>


    <script>
    const worker = <?= json_encode($worker); ?>;
    console.log(worker);
</script>

    <script>
        function openUpdateModal() {
            document.getElementById('updateModal').style.display = 'flex';
        }

        function closeUpdateModal() {
            document.getElementById('updateModal').style.display = 'none';
        }

        console.log(worker);
        // Form submission handler
        document.getElementById('workerUpdateForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Collect form data
            const formData = new FormData(this);
            const data = Object.fromEntries(formData.entries());

            // Send update request
            fetch('<?=ROOT?>/public/adminWorkerProfile/update', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    alert('Profile updated successfully');
                    closeUpdateModal();
                    // Optionally refresh the page or update UI
                    location.reload();
                } else {
                    alert('Update failed: ' + result.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while updating the profile');
            });
        });
    </script>
</body>
</html>

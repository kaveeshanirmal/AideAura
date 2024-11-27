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
    <?php include(ROOT_PATH . '/app/views/components/admin_navbar.view.php');  ?>
    <main class="main-content">
            <div class="back-header">
                <a href="adminWorkerProfile" class="back-button">‹</a>
                <div class="worker-header">
                    <div class="worker-avatar">
                        <img src="assets/images/user_icon.png" alt="Worker Avatar">
                    </div>
                    <div class="worker-title">
                        <h2>MR. Kamal Rupasinghe</h2>
                        <p>Cleaner</p>
                    </div>
                </div>
            </div>

            <div class="worker-details-container">
                <div class="details-grid">
                    <div class="detail-item">
                        <span class="label">Name :</span>
                        <span class="value">R.N.D. Kamal Gunarathne</span>
                    </div>
                    <div class="detail-item">
                        <span class="label">Date Of Birth :</span>
                        <span class="value">1984.06.19</span>
                    </div>
                    <div class="detail-item">
                        <span class="label">Nationality :</span>
                        <span class="value">Sinhalese</span>
                    </div>
                    <div class="detail-item">
                        <span class="label">Gender :</span>
                        <span class="value">Male</span>
                    </div>
                    <div class="detail-item">
                        <span class="label">Contact :</span>
                        <span class="value">078 4968 720</span>
                    </div>
                    <div class="detail-item">
                        <span class="label">Email :</span>
                        <span class="value">kamal123@gamil.com</span>
                    </div>
                    <div class="detail-item">
                        <span class="label">ID/Passport No :</span>
                        <span class="value">1984 2543 6702 0149</span>
                    </div>
                    <div class="detail-item">
                        <span class="label">Employment History / Experience :</span>
                        <span class="value">5 years in Sri Lanka</span>
                    </div>
                    <div class="detail-item">
                        <span class="label">Skilled or Specialized Areas :</span>
                        <span class="value">Cooking</span>
                    </div>
                    <div class="detail-item">
                        <span class="label">Certificates :</span>
                        <span class="value">Master Chef Certificate Of Levenro Garden Hotel Schole</span>
                    </div>
                    <div class="detail-item">
                        <span class="label">Spoken Languages :</span>
                        <span class="value">Sinhala, Tamil</span>
                    </div>
                    <div class="detail-item">
                        <span class="label">Work Area/Location :</span>
                        <span class="value">Colombo Mid Town</span>
                    </div>
                    <div class="detail-item">
                        <span class="label">Job Preferences :</span>
                        <span class="value">Maximum an be cooked Three meals per day for adults</span>
                    </div>
                    <div class="detail-item">
                        <span class="label">Medical Fitness Certificate :</span>
                        <span class="value">Colombo Central Hospital Medical Fitness Report</span>
                    </div>
                    <div class="detail-item">
                        <span class="label">Allergies of Physical Limitations :</span>
                        <span class="value">No</span>
                    </div>
                    <div class="detail-item">
                        <span class="label">Address :</span>
                        <span class="value">78/1 Queen street Kollupitiya Colombo 07</span>
                    </div>
                    <div class="detail-item">
                        <span class="label">Bank Details :</span>
                        <span class="value">Peoples Bank Kollupitiya 1789-6325-4147-0502</span>
                    </div>
                    <!-- ... (previous detail items remain the same) ... -->
                </div>
            </div>

            <div class="action-buttons">
                <button class="btn update" onclick="openUpdateModal()">Update</button>
                <button class="btn schedule"><a href="adminWorkerProfileSchedule">Availability schedule</a></button>
                <button class="btn certificates"><a href="adminWorkerProfile2">Certificates</a></button>
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
                            <input type="text" name="full_name" value="R.N.D. Kamal Gunarathne">
                        </div>
                        <div class="form-group">
                            <label>Date of Birth</label>
                            <input type="date" name="date_of_birth" value="1984-06-19">
                        </div>
                        <div class="form-group">
                            <label>Nationality</label>
                            <input type="text" name="nationality" value="Sinhalese">
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
                            <input type="tel" name="contact" value="078 4968 720">
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" name="email" value="kamal123@gamil.com">
                        </div>
                        <div class="form-group">
                            <label>ID/Passport No</label>
                            <input type="text" name="id_passport" value="1984 2543 6702 0149">
                        </div>
                        <div class="form-group">
                            <label>Work Area/Location</label>
                            <input type="text" name="work_location" value="Colombo Mid Town">
                        </div>
                        <div class="form-group">
                            <label>Address</label>
                            <input type="text" name="address" value="78/1 Queen street Kollupitiya Colombo 07">
                        </div>
                        <div class="form-group">
                            <label>Bank Details</label>
                            <input type="text" name="bank_details" value="Peoples Bank Kollupitiya 1789-6325-4147-0502">
                        </div>
                        <div class="form-group">
                            <label>Job Preferences</label>
                            <textarea name="job_preferences">Maximum can be cooked Three meals per day for adults</textarea>
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
        function openUpdateModal() {
            document.getElementById('updateModal').style.display = 'flex';
        }

        function closeUpdateModal() {
            document.getElementById('updateModal').style.display = 'none';
        }

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
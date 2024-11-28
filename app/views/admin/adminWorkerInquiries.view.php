<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Complaints Management</title>
    <link rel="stylesheet" href="<?=ROOT?>/public/assets/css/adminWorkerInquiries.css">
    <script src="<?=ROOT?>/public/assets/js/adminWorkerInquiries.js" defer></script>
    <script>
        const ROOT = '<?=ROOT?>';
    </script>
</head>
<body>
    <div id="replyOverlay" class="overlay" style="display: none;">
        <!-- reply overlay -->
        <div class="overlay-content">
            <h3>Give a Solution</h3>
            <form id="replyForm">
                <div class="form-group">
                    <label for="solutionText">Solution:</label>
                    <textarea id="solutionText" name="solution" rows="4" placeholder="Enter solution..." required></textarea>
                </div>
                <input type="hidden" id="complaintIdInput" name="complaintID">
                <div class="form-actions">
                    <button type="button" id="closeOverlayButton">Cancel</button>
                    <button type="submit" id="submitReplyButton">Submit</button>
                </div>
            </form>
            <div id="responseMessage" class="response-message" style="display: none;"></div>
            <button id="closeMessageButton" style="display: none;">OK</button>
        </div>
        <!-- delete overlay -->
        <div id="deleteOverlay" class="overlay" style="display: none;">
            <div class="overlay-content">
                <h3>Confirm Deletion</h3>
                <p>Are you sure you want to delete this resolved complaint?</p>
                <input type="hidden" id="deleteComplaintId" name="complaintId">
                <div class="form-actions">
                    <button id="cancelDeleteButton">Cancel</button>
                    <button id="confirmDeleteButton" class="delete-confirm-btn">Delete</button>
                </div>
                <div id="deleteResponseMessage" class="response-message" style="display: none;"></div>
                <button id="closeDeleteMessageButton" style="display: none;">OK</button>
            </div>
        </div>
    </div>

    <div class="dashboard-container">
        <?php include(ROOT_PATH . '/app/views/components/admin_navbar.view.php'); ?>
        <div class="container">
            <div class="main-content">
                <div class="filters-container">
                    <label for="issueFilter">Filter by Issue Type:</label>
                    <select id="issueFilter">
                        <option value="all">All</option>
                        <option value="General Issues">General Issues</option>
                        <option value="Service Issues">Service Issues</option>
                        <option value="Booking Issues">Booking Issues</option>
                        <option value="Payment Issues">Payment Issues</option>
                        <option value="Technical Issues">Technical Issues</option>
                        <option value="Account Issues">Account Issues</option>
                        <option value="Complaint/Feedback">Complaint/Feedback</option>
                        <option value="Help Requests">Help Requests</option>
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
                            <th>Issue</th>
                            <th>Description</th>
                            <th>Priority</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="complaintsTableBody">
                        <?php if (!empty($complaints)) : ?>
                            <?php foreach ($complaints as $complaint) : ?>
                                <tr>
                                    <td><?= htmlspecialchars($complaint->customerID, ENT_QUOTES, 'UTF-8') ?></td>
                                    <td><?= htmlspecialchars($complaint->issue_type, ENT_QUOTES, 'UTF-8') ?></td>
                                    <td><?= htmlspecialchars($complaint->issue, ENT_QUOTES, 'UTF-8') ?></td>
                                    <td>
                                        <span class="description-tooltip" data-full-description="<?= htmlspecialchars($complaint->description, ENT_QUOTES, 'UTF-8') ?>">
                                            <?= htmlspecialchars(substr($complaint->description, 0, 50) . (strlen($complaint->description) > 50 ? '...' : ''), ENT_QUOTES, 'UTF-8') ?>
                                        </span>
                                    </td>
                                    <td><?= htmlspecialchars($complaint->priority, ENT_QUOTES, 'UTF-8') ?></td>
                                    <td><?= htmlspecialchars($complaint->submitted_at, ENT_QUOTES, 'UTF-8') ?></td>
                                    <td><?= htmlspecialchars($complaint->status, ENT_QUOTES, 'UTF-8') ?></td>
                                    <td>
                                        <?php if ($complaint->status === 'Resolved') : ?>
                                            <button class="delete-button" data-complaint-id="<?= htmlspecialchars($complaint->complaintID, ENT_QUOTES, 'UTF-8') ?>">Delete</button>
                                        <?php else : ?>
                                            <button class="reply-button" data-complaint-id="<?= htmlspecialchars($complaint->complaintID, ENT_QUOTES, 'UTF-8') ?>">Reply</button>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="6">No complaints found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FM - Worker Complaints Management</title>
    <link rel="stylesheet" href="<?=ROOT?>/public/assets/css/adminWorkerInquiries.css">
    <script src="<?=ROOT?>/public/assets/js/admin/adminWorkerComplaints.js" defer></script>
    <script>
        const ROOT = '<?=ROOT?>';
    </script>
</head>
<body>
<?php include(ROOT_PATH . '/app/views/components/employeeNavbar.view.php'); ?>
    <div class="dashboard-container">
        <div class="container">
            <div class="main-content">
                <!-- Complaints List Sidebar -->
                <div class="complaints-sidebar">
                    <div class="sidebar-header">
                        <h2>Worker Complaints</h2>
                    </div>
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
                        <label for="statusFilter">Filter by Status:</label>
                        <select id="statusFilter">
                            <option value="all">All</option>
                            <option value="Pending">Pending</option>
                            <option value="In Progress">In Progress</option>
                            <option value="Resolved">Resolved</option>
                        </select>
                    </div>
                    <div class="complaints-list" id="complaints-list">
                        <?php if (!empty($complaints)) : ?>
                            <?php foreach ($complaints as $complaint) : ?>
                                <div class="complaint-item" data-complaint-id="<?= htmlspecialchars($complaint->complaintID, ENT_QUOTES, 'UTF-8') ?>" data-worker-id="<?= htmlspecialchars($complaint->workerID, ENT_QUOTES, 'UTF-8') ?>">
                                    <div class="complaint-header">
                                        <span class="worker-id">Worker #<?= htmlspecialchars($complaint->workerID, ENT_QUOTES, 'UTF-8') ?></span>
                                        <span class="complaint-date"><?= htmlspecialchars(date('M d, Y', strtotime($complaint->submitted_at)), ENT_QUOTES, 'UTF-8') ?></span>
                                    </div>
                                    <div class="complaint-issue">
                                        <?= htmlspecialchars($complaint->issue, ENT_QUOTES, 'UTF-8') ?>
                                        <span class="priority-badge priority-<?= strtolower(htmlspecialchars($complaint->priority, ENT_QUOTES, 'UTF-8')) ?>">
                                            <?= htmlspecialchars($complaint->priority, ENT_QUOTES, 'UTF-8') ?>
                                        </span>
                                    </div>
                                    <div class="complaint-preview">
                                        <?= htmlspecialchars(substr($complaint->description, 0, 60) . (strlen($complaint->description) > 60 ? '...' : ''), ENT_QUOTES, 'UTF-8') ?>
                                    </div>
                                    <span class="status-badge status-<?= strtolower(str_replace(' ', '-', $complaint->status)) ?>">
                                        <?= htmlspecialchars($complaint->status, ENT_QUOTES, 'UTF-8') ?>
                                    </span>
                                </div>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <div class="empty-state">
                                <p>No complaints found.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Chat Interface -->
                <div class="chat-container">
                    <div id="no-chat-selected" class="no-chat-selected">
                        <i class="far fa-comments"></i>
                        <h3>Select a complaint to start chatting</h3>
                        <p>Choose a complaint from the list to view details and respond</p>
                    </div>
                    
                    <div id="chat-interface" style="display: none; height: 100%; flex-direction: column;">
                        <div class="chat-header">
                            <div class="customer-info">
                                <img id="worker-avatar" src="<?= ROOT ?>/public/assets/images/avatar-image.png" alt="Worker Avatar" class="customer-avatar">
                                <div class="customer-details">
                                    <h3 id="worker-name">Worker Name</h3>
                                    <p id="worker-id">Worker ID: </p>
                                    <div class="issue-details">
                                        <span class="issue-type" id="issue-type">Issue Type: </span>
                                        <span id="issue-priority" class="priority-badge">Priority</span>
                                    </div>
                                </div>
                            </div>
                            <div class="action-buttons">
                                <button id="resolveButton" class="resolve-button">Mark as Resolved</button>
                                <button id="deleteButton" class="delete-button">Delete</button>
                            </div>
                        </div>
                        
                        <div class="chat-messages" id="chat-messages">
                            <!-- Messages will be loaded here dynamically -->
                        </div>
                        
                        <div class="chat-input">
                            <form id="chat-form" class="chat-form">
                                <textarea id="message-input" placeholder="Type your reply here..." required></textarea>
                                <button type="submit">Send</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Resolve Confirmation Modal -->
    <div id="resolveModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Confirm Resolution</h3>
            </div>
            <p>Are you sure you want to mark this complaint as resolved?</p>
            <div class="modal-actions">
                <button id="cancelResolve" class="cancel-button">Cancel</button>
                <button id="confirmResolve" class="confirm-button">Confirm</button>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Confirm Deletion</h3>
            </div>
            <p>Are you sure you want to delete this complaint? This action cannot be undone.</p>
            <div class="modal-actions">
                <button id="cancelDelete" class="cancel-button">Cancel</button>
                <button id="confirmDelete" class="confirm-button">Delete</button>
            </div>
        </div>
    </div>
</body>
</html>
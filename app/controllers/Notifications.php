<?php

class Notifications extends Controller
{
    protected $notifications;

    public function __construct()
    {
        parent::__construct();
        $this->notifications = $this->loadModel('NotificationModel');
    }

    public function index()
    {
        echo "Invalid notification action.";
    }

    public function poll()
    {
        header('Content-Type: application/json');

        if (!isset($_SESSION['userID'])) {
            echo json_encode([]);
            return;
        }

        $data = $this->notifications->getUnread($_SESSION['userID']);
        echo json_encode($data);
    }

    public function markAsRead()
    {
        header('Content-Type: application/json');

        // Check if user is logged in
        if (!isset($_SESSION['userID'])) {
            echo json_encode(['success' => false, 'message' => 'User not logged in']);
            return;
        }

        // Check if notification ID was provided
        if (!isset($_POST['id']) || empty($_POST['id'])) {
            echo json_encode(['success' => false, 'message' => 'No notification ID provided']);
            return;
        }

        $notificationId = (int)$_POST['id'];

        // Verify the notification belongs to the current user
        $notification = $this->notifications->getById($notificationId);

        if (!$notification || $notification->userID != $_SESSION['userID']) {
            echo json_encode(['success' => false, 'message' => 'Invalid notification']);
            return;
        }

        // Mark the notification as read
        $this->notifications->markAsRead($notificationId);

        echo json_encode(['success' => true]);
    }

    public function markAllAsRead()
    {
        header('Content-Type: application/json');

        // Check if user is logged in
        if (!isset($_SESSION['userID'])) {
            echo json_encode(['success' => false, 'message' => 'User not logged in']);
            return;
        }

        // Mark all notifications as read for this user
        $this->notifications->markAllAsRead($_SESSION['userID']);

        echo json_encode(['success' => true]);
    }

    public function renderItem() {
        $id = $_POST['id'] ?? null;
        if ($id) {
            $notification = $this->notifications->getById($id);
            if ($notification) {
                // Render a single notification item with proper escaping and time formatting
                include ROOT_PATH . '/app/views/components/notification-item.view.php';
            }
        }
    }
}
<?php

class NotificationModel
{
    use Model;

    // Constructor to set the table name
    public function __construct()
    {
        $this->setTable('notifications');
    }

    public function create($userId, $type, $title, $message = '') {
        // Insert new notification
        $data = [
            'userID' => $userId,
            'type' => $type,
            'title' => $title,
            'message' => $message
        ];
        return $this->insertAndGetId($data);
    }

    public function getUnread($userId) {
        // Return unread notifications
        $query = "SELECT * FROM {$this->getTable()} WHERE userID = :userID AND is_read = 0 ORDER BY created_at DESC";
        return $this->get_all($query, ['userID' => $userId]);
    }

    public function markAsRead($notificationId) {
        // Set `is_read` to 1
        $this->update($notificationId, ['is_read' => 1], 'notificationID');
    }

    public function markAllAsRead($userId) {
        // Set `is_read` to 1 for all notifications of this user
        $query = "UPDATE {$this->getTable()} SET is_read = 1 WHERE userID = :userID AND is_read = 0";
        $this->query($query, ['userID' => $userId]);
    }

    public function getById($notificationId) {
        // Get a specific notification by ID
        $query = "SELECT * FROM {$this->getTable()} WHERE notificationID = :notificationID LIMIT 1";
        return $this->get_row($query, ['notificationID' => $notificationId]);
    }
}
<?php
require_once __DIR__ . '/../config/database.php';

class Notification {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function saveNotification($messageId, $notificationText, $receiverId = null) {
        $sql = "INSERT INTO notifications (message_id, notification_text, is_seen, created_at, receiver_id) VALUES (?, ?, 0, NOW(), ?)";
        $stmt = $this->db->getConnection()->prepare($sql);
        return $stmt->execute([$messageId, $notificationText, $receiverId]);
    }

    public function addNotification($receiverId, $messageId, $notificationText) {
        $sql = "INSERT INTO notifications (receiver_id, message_id, notification_text, is_seen, created_at) VALUES (?, ?, ?, 0, NOW())";
        $stmt = $this->db->getConnection()->prepare($sql);
        return $stmt->execute([$receiverId, $messageId, $notificationText]);
    }

    public function countUnseenForUser($userId) {
        $sql = "SELECT COUNT(*) FROM notifications WHERE receiver_id = ? AND is_seen = 0";
        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetchColumn();
    }

    public function markAsSeen($receiverId) {
        $sql = "UPDATE notifications SET is_seen = 1 WHERE receiver_id = ?";
        $stmt = $this->db->getConnection()->prepare($sql);
        return $stmt->execute([$receiverId]);
    }

    public function getNotificationsByMessageId($messageId) {
        $sql = "SELECT * FROM notifications WHERE message_id = ? ORDER BY created_at DESC";
        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->execute([$messageId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getNotificationsByUserId($userId) {
        $sql = "SELECT n.idnot as notification_id, n.notification_text, n.is_seen, n.created_at as notification_created_at, 
                       m.id as message_id, m.sender, m.receiver, m.content, m.created_at as message_created_at
                FROM notifications n
                JOIN messages m ON n.message_id = m.id
                WHERE m.receiver = ?
                ORDER BY n.created_at DESC";
        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUnreadNotificationCountsByUser() {
        $sql = "SELECT d.user1_id as user1, d.user2_id as user2, COUNT(*) as unread_count
                FROM notifications n
                JOIN messages m ON n.message_id = m.id
                JOIN discussions d ON m.discussion_id = d.id
                WHERE n.is_seen = FALSE
                GROUP BY d.user1_id, d.user2_id";
        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $counts = [];
        foreach ($results as $row) {
            // Count unread for both users in the discussion
            $counts[$row['user1']] = ($counts[$row['user1']] ?? 0) + $row['unread_count'];
            $counts[$row['user2']] = ($counts[$row['user2']] ?? 0) + $row['unread_count'];
        }
        return $counts;
    }

    public function getNotificationsWithMessages() {
        $sql = "SELECT n.idnot as notification_id, n.notification_text, n.is_seen, n.created_at as notification_created_at, 
                       m.id as message_id, m.sender, m.receiver, m.content, m.created_at as message_created_at
                FROM notifications n
                JOIN messages m ON n.message_id = m.id
                ORDER BY n.created_at DESC";
        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function markAsRead($notificationId) {
        $sql = "UPDATE notifications SET is_seen = TRUE WHERE idnot = ?";
        $stmt = $this->db->getConnection()->prepare($sql);
        return $stmt->execute([$notificationId]);
    }
}

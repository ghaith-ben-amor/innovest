<?php
class MessageModel {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    // Get total messages count
    public function getTotalMessages() {
        $sql = "SELECT COUNT(*) as total FROM messages";
        $result = $this->db->query($sql);
        $row = $result->fetch(PDO::FETCH_ASSOC);
        return $row['total'];
    }

    // Get messages for a discussion between two users
    public function getMessages($user1_id, $user2_id) {
        // Find discussion id
        $sqlDiscussion = "SELECT id FROM discussions WHERE
            (user1_id = ? AND user2_id = ?) OR (user1_id = ? AND user2_id = ?)";
        $stmt = $this->db->getConnection()->prepare($sqlDiscussion);
        $stmt->execute([$user1_id, $user2_id, $user2_id, $user1_id]);
        $discussion = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$discussion) {
            return [];
        }
        $discussion_id = $discussion['id'];

        // Get messages for discussion
        $sql = "SELECT m.id, m.sender_id, m.message, m.created_at FROM messages m WHERE m.discussion_id = ? ORDER BY m.created_at ASC";
        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->execute([$discussion_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Save message to a discussion
    public function save($sender_id, $receiver_id, $message) {
        // Vérifier les mots interdits
        $bannedWordModel = new BannedWordModel();
        $detectedWords = $bannedWordModel->checkMessage($message);

        if (!empty($detectedWords)) {
            $severity = $bannedWordModel->getHighestSeverity($detectedWords);
            // Pour les insultes de haute sévérité, on bloque le message
            if ($severity === 'high') {
                throw new Exception("Message bloqué : contenu inapproprié détecté");
            }
        }

        // Find or create discussion
        $sqlDiscussion = "SELECT id FROM discussions WHERE
            (user1_id = ? AND user2_id = ?) OR (user1_id = ? AND user2_id = ?)";
        $stmt = $this->db->getConnection()->prepare($sqlDiscussion);
        $user1 = min($sender_id, $receiver_id);
        $user2 = max($sender_id, $receiver_id);
        $stmt->execute([$user1, $user2, $user2, $user1]);
        $discussion = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$discussion) {
            // Create new discussion
            $sqlInsert = "INSERT INTO discussions (user1_id, user2_id, created_at) VALUES (?, ?, NOW())";
            $stmtInsert = $this->db->getConnection()->prepare($sqlInsert);
            $stmtInsert->execute([$user1, $user2]);
            $discussion_id = $this->db->getConnection()->lastInsertId();
        } else {
            $discussion_id = $discussion['id'];
        }

        // Insert message with flag if contains medium severity words
        $has_offensive_content = (!empty($detectedWords) && $severity === 'medium') ? 1 : 0;
        $sqlMessage = "INSERT INTO messages (discussion_id, sender_id, message, has_offensive_content, created_at)
                      VALUES (?, ?, ?, ?, NOW())";
        $stmtMessage = $this->db->getConnection()->prepare($sqlMessage);
        return $stmtMessage->execute([$discussion_id, $sender_id, $message, $has_offensive_content]);
    }

    // Get count of messages created today
    public function getTodayMessages() {
        $sql = "SELECT COUNT(*) as total FROM messages WHERE DATE(created_at) = CURDATE()";
        $result = $this->db->query($sql);
        $row = $result->fetch(PDO::FETCH_ASSOC);
        return $row['total'];
    }

    // Get count of messages for a specific period
    public function getMessagesByPeriod($period) {
        $sql = "SELECT COUNT(*) as total FROM messages WHERE 1=1";

        switch ($period) {
            case 'today':
                $sql .= " AND DATE(created_at) = CURDATE()";
                break;
            case 'week':
                $sql .= " AND YEARWEEK(created_at, 1) = YEARWEEK(CURDATE(), 1)";
                break;
            case 'month':
                $sql .= " AND YEAR(created_at) = YEAR(CURDATE()) AND MONTH(created_at) = MONTH(CURDATE())";
                break;
            default:
                // No filter, return all messages
                break;
        }

        $result = $this->db->query($sql);
        $row = $result->fetch(PDO::FETCH_ASSOC);
        return $row['total'];
    }

    // Get recent message activity (last 10 messages)
    public function getRecentActivity($limit = 10) {
        $sql = "SELECT
                m.id,
                m.message,
                m.created_at,
                u_sender.username as username,
                u_receiver.username as receiver_username
                FROM messages m
                JOIN discussions d ON m.discussion_id = d.id
                JOIN users u_sender ON m.sender_id = u_sender.iduser
                JOIN users u_receiver ON (
                    CASE
                        WHEN d.user1_id = m.sender_id THEN d.user2_id
                        ELSE d.user1_id
                    END = u_receiver.iduser
                )
                ORDER BY m.created_at DESC
                LIMIT ?";
        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->bindValue(1, (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get total filtered messages count for admin dashboard
    public function getTotalFilteredMessages($filters = []) {
        $sql = "SELECT COUNT(*) as total FROM messages m
                JOIN users u ON m.sender_id = u.iduser
                WHERE 1=1";
        $params = [];

        if (!empty($filters['search'])) {
            $sql .= " AND (m.message LIKE ? OR u.username LIKE ?)";
            $search = "%" . $filters['search'] . "%";
            $params[] = $search;
            $params[] = $search;
        }

        if (!empty($filters['user'])) {
            $sql .= " AND u.username = ?";
            $params[] = $filters['user'];
        }

        if (!empty($filters['date'])) {
            $sql .= " AND DATE(m.created_at) = ?";
            $params[] = $filters['date'];
        }

        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->execute($params);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'];
    }

    // Get filtered messages for admin dashboard
    public function getFilteredMessages($limit, $offset, $filters = []) {
        $sql = "SELECT m.id, m.message, m.created_at, u.username as sender_username
                FROM messages m
                JOIN users u ON m.sender_id = u.iduser
                WHERE 1=1";
        $params = [];

        if (!empty($filters['search'])) {
            $sql .= " AND (m.message LIKE ? OR u.username LIKE ?)";
            $search = "%" . $filters['search'] . "%";
            $params[] = $search;
            $params[] = $search;
        }

        if (!empty($filters['user'])) {
            $sql .= " AND u.username = ?";
            $params[] = $filters['user'];
        }

        if (!empty($filters['date'])) {
            $sql .= " AND DATE(m.created_at) = ?";
            $params[] = $filters['date'];
        }

        $sql .= " ORDER BY m.created_at DESC LIMIT ? OFFSET ?";
        $params[] = (int)$limit;
        $params[] = (int)$offset;

        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get all discussions with latest message
    public function getAllDiscussions() {
        $sql = "SELECT
                d.id as discussion_id,
                d.created_at as discussion_created_at,
                u1.username as user1_username,
                u2.username as user2_username,
                m.message as last_message,
                m.created_at as last_message_date,
                u_sender.username as last_message_sender
            FROM discussions d
            JOIN users u1 ON d.user1_id = u1.iduser
            JOIN users u2 ON d.user2_id = u2.iduser
            LEFT JOIN (
                SELECT discussion_id, MAX(created_at) as max_date
                FROM messages
                GROUP BY discussion_id
            ) latest ON d.id = latest.discussion_id
            LEFT JOIN messages m ON latest.discussion_id = m.discussion_id
                AND latest.max_date = m.created_at
            LEFT JOIN users u_sender ON m.sender_id = u_sender.iduser
            ORDER BY m.created_at DESC";

        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get all messages from a specific discussion
    public function getDiscussionMessages($discussion_id) {
        $sql = "SELECT
                m.id,
                m.message,
                m.created_at,
                u.username as sender_username
            FROM messages m
            JOIN users u ON m.sender_id = u.iduser
            WHERE m.discussion_id = ?
            ORDER BY m.created_at ASC";

        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->execute([$discussion_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countUnreadMessages($userId, $otherUserId = null) {
        if ($otherUserId) {
            // Count unread messages between two specific users
            $sql = "SELECT COUNT(*) FROM messages m
                    JOIN discussions d ON m.discussion_id = d.id
                    WHERE ((d.user1_id = ? AND d.user2_id = ? AND m.sender_id = ?)
                       OR (d.user1_id = ? AND d.user2_id = ? AND m.sender_id = ?))
                    AND m.is_read = 0";
            $stmt = $this->db->getConnection()->prepare($sql);
            $stmt->execute([$userId, $otherUserId, $otherUserId, $otherUserId, $userId, $otherUserId]);
        } else {
            // Count all unread messages for a user
            $sql = "SELECT COUNT(*) FROM messages m
                    JOIN discussions d ON m.discussion_id = d.id
                    WHERE ((d.user1_id = ? AND m.sender_id = d.user2_id)
                       OR (d.user2_id = ? AND m.sender_id = d.user1_id))
                    AND m.is_read = 0";
            $stmt = $this->db->getConnection()->prepare($sql);
            $stmt->execute([$userId, $userId]);
        }
        return $stmt->fetchColumn();
    }
}

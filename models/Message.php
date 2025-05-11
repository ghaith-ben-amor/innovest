<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/BannedWordModel.php';
require_once __DIR__ . '/Notification.php';

class Message {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    // Save message between sender and receiver
    public function save($sender_id, $receiver_id, $message) {
        error_log("Début de la vérification des mots interdits pour le message: " . $message);

        // Vérifier les mots interdits
        $bannedWordModel = new BannedWordModel();
        $detectedWords = $bannedWordModel->checkMessage($message);

        error_log("Mots interdits détectés: " . print_r($detectedWords, true));

        if (!empty($detectedWords)) {
            $severity = $bannedWordModel->getHighestSeverity($detectedWords);
            $bannedWord = $detectedWords[0]['word'];

            error_log("Mot interdit trouvé: " . $bannedWord . " avec sévérité: " . $severity);

            // Pour les insultes de haute sévérité, on bloque le message
            if ($severity === 'high') {
                error_log("Message bloqué car sévérité haute");
                throw new Exception("Le mot '" . htmlspecialchars($bannedWord) . "' est interdit et ne peut pas être envoyé.");
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
            $sqlInsert = "INSERT INTO discussions (user1_id, user2_id, created_at) VALUES (?, ?, NOW())";
            $stmtInsert = $this->db->getConnection()->prepare($sqlInsert);
            $stmtInsert->execute([$user1, $user2]);
            $discussion_id = $this->db->getConnection()->lastInsertId();
        } else {
            $discussion_id = $discussion['id'];
        }

        // Insert message with flag if contains medium severity words
        $has_offensive_content = (!empty($detectedWords) && isset($severity) && $severity === 'medium') ? 1 : 0;
        error_log("Insertion du message avec has_offensive_content = " . $has_offensive_content);

        // Si le message est vide, utiliser un espace pour éviter d'afficher "undefined"
        if (empty($message)) {
            $message = " ";
        }

        $sqlMessage = "INSERT INTO messages (discussion_id, sender_id, message, has_offensive_content, is_read, created_at)
                      VALUES (?, ?, ?, ?, 0, NOW())";
        $stmtMessage = $this->db->getConnection()->prepare($sqlMessage);
        $result = $stmtMessage->execute([$discussion_id, $sender_id, $message, $has_offensive_content]);

        if ($result) {
            $messageId = $this->db->getConnection()->lastInsertId();
            // Créer une notification pour le destinataire
            $notification = new Notification();
            $notification->addNotification($receiver_id, $messageId, "Nouveau message reçu");
        }

        return $result;
    }

    // Get messages between two users
    public function getMessages($user1_id, $user2_id) {
        // Marquer les messages comme lus lorsqu'ils sont consultés
        $this->markMessagesAsRead($user1_id, $user2_id);
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
        $sql = "SELECT * FROM messages WHERE discussion_id = ? ORDER BY created_at ASC";
        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->execute([$discussion_id]);
        $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Fetch reactions for these messages
        $messageIds = array_column($messages, 'id');
        $reactions = $this->getReactionsByMessageIds($messageIds);

        // Attach reactions to messages
        foreach ($messages as &$message) {
            $message['reactions'] = $reactions[$message['id']] ?? [];
        }

        return $messages;
    }

    public function addReaction($messageId, $userId, $reactionType) {
        $sql = "INSERT INTO message_reactions (message_id, user_id, reaction_type, created_at) VALUES (?, ?, ?, NOW())
                ON DUPLICATE KEY UPDATE reaction_type = VALUES(reaction_type), created_at = NOW()";
        $stmt = $this->db->getConnection()->prepare($sql);
        return $stmt->execute([$messageId, $userId, $reactionType]);
    }

    public function addOrUpdateReaction($messageId, $userId, $reactionType) {
        $sql = "INSERT INTO message_reactions (message_id, user_id, reaction_type, created_at) VALUES (?, ?, ?, NOW())
                ON DUPLICATE KEY UPDATE reaction_type = VALUES(reaction_type), created_at = NOW()";
        $stmt = $this->db->getConnection()->prepare($sql);
        return $stmt->execute([$messageId, $userId, $reactionType]);
    }

    public function removeReaction($messageId, $userId) {
        $sql = "DELETE FROM message_reactions WHERE message_id = ? AND user_id = ?";
        $stmt = $this->db->getConnection()->prepare($sql);
        return $stmt->execute([$messageId, $userId]);
    }

    public function getReactionsByMessageIds($messageIds) {
        if (empty($messageIds)) {
            return [];
        }
        try {
            $placeholders = implode(',', array_fill(0, count($messageIds), '?'));
            $sql = "SELECT message_id, user_id, reaction_type FROM message_reactions WHERE message_id IN ($placeholders)";
            $stmt = $this->db->getConnection()->prepare($sql);
            $stmt->execute($messageIds);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $reactions = [];
            foreach ($rows as $row) {
                $reactions[$row['message_id']][] = [
                    'user_id' => $row['user_id'],
                    'reaction_type' => $row['reaction_type']
                ];
            }
            return $reactions;
        } catch (PDOException $e) {
            // Si la table n'existe pas, retourner un tableau vide
            error_log("Erreur lors de la récupération des réactions: " . $e->getMessage());
            return [];
        }
    }

    public function getReactionsByMessageId($messageId) {
        try {
            $sql = "SELECT user_id, reaction_type FROM message_reactions WHERE message_id = ?";
            $stmt = $this->db->getConnection()->prepare($sql);
            $stmt->execute([$messageId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération des réactions pour le message $messageId: " . $e->getMessage());
            return [];
        }
    }

    public function getReactionCountsByMessageIds($messageIds) {
        if (empty($messageIds)) {
            return [];
        }
        $placeholders = implode(',', array_fill(0, count($messageIds), '?'));
        $sql = "SELECT message_id, reaction_type, COUNT(*) as count FROM message_reactions WHERE message_id IN ($placeholders) GROUP BY message_id, reaction_type";
        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->execute($messageIds);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $reactionCounts = [];
        foreach ($rows as $row) {
            $reactionCounts[$row['message_id']][$row['reaction_type']] = $row['count'];
        }
        return $reactionCounts;
    }

    public function deleteMessage($messageId, $sender_id) {
        try {
            // Vérifier d'abord si le message existe et appartient à l'utilisateur
            $sql = "SELECT id FROM messages WHERE id = ? AND sender_id = ?";
            $stmt = $this->db->getConnection()->prepare($sql);
            $stmt->execute([$messageId, $sender_id]);

            if (!$stmt->fetch()) {
                error_log("Tentative de suppression d'un message non autorisée. Message ID: $messageId, Sender: $sender_id");
                return false;
            }

            // Supprimer d'abord les réactions associées
            $sqlDeleteReactions = "DELETE FROM message_reactions WHERE message_id = ?";
            $stmtReactions = $this->db->getConnection()->prepare($sqlDeleteReactions);
            $stmtReactions->execute([$messageId]);

            // Supprimer ensuite le message
            $sqlDeleteMessage = "DELETE FROM messages WHERE id = ? AND sender_id = ?";
            $stmtMessage = $this->db->getConnection()->prepare($sqlDeleteMessage);
            $result = $stmtMessage->execute([$messageId, $sender_id]);

            if ($result) {
                error_log("Message supprimé avec succès. Message ID: $messageId");
            } else {
                error_log("Échec de la suppression du message. Message ID: $messageId");
            }

            return $result;
        } catch (PDOException $e) {
            error_log("Erreur lors de la suppression du message: " . $e->getMessage());
            return false;
        }
    }

    public function updateMessage($messageId, $sender_id, $newContent) {
        $sql = "UPDATE messages SET message = ? WHERE id = ? AND sender_id = ?";
        $stmt = $this->db->getConnection()->prepare($sql);
        return $stmt->execute([$newContent, $messageId, $sender_id]);
    }

    // Marquer les messages comme lus
    public function markMessagesAsRead($user_id, $other_user_id) {
        // Trouver l'ID de la discussion
        $sqlDiscussion = "SELECT id FROM discussions WHERE
            (user1_id = ? AND user2_id = ?) OR (user1_id = ? AND user2_id = ?)";
        $stmt = $this->db->getConnection()->prepare($sqlDiscussion);
        $stmt->execute([$user_id, $other_user_id, $other_user_id, $user_id]);
        $discussion = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$discussion) {
            return false;
        }

        // Marquer tous les messages envoyés par l'autre utilisateur comme lus
        $sql = "UPDATE messages SET is_read = 1
                WHERE discussion_id = ? AND sender_id = ? AND is_read = 0";
        $stmt = $this->db->getConnection()->prepare($sql);
        $result = $stmt->execute([$discussion['id'], $other_user_id]);

        // Marquer les notifications comme vues
        if ($result) {
            $notification = new Notification();
            $notification->markAsSeen($user_id);
        }

        return $result;
    }

    // Compter les messages non lus pour un utilisateur
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

    // Obtenir les discussions avec le nombre de messages non lus
    public function getDiscussionsWithUnreadCount($user_id) {
        $sql = "SELECT d.id as discussion_id,
                      CASE WHEN d.user1_id = ? THEN d.user2_id ELSE d.user1_id END as other_user_id,
                      u.username as other_username,
                      COUNT(CASE WHEN m.is_read = 0 AND m.sender_id != ? THEN 1 ELSE NULL END) as unread_count,
                      MAX(m.created_at) as last_message_time
               FROM discussions d
               JOIN users u ON (CASE WHEN d.user1_id = ? THEN d.user2_id ELSE d.user1_id END = u.iduser)
               LEFT JOIN messages m ON m.discussion_id = d.id
               WHERE d.user1_id = ? OR d.user2_id = ?
               GROUP BY d.id, other_user_id, other_username
               ORDER BY last_message_time DESC";
        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->execute([$user_id, $user_id, $user_id, $user_id, $user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getLastMessageBetweenUsers($username1, $username2) {
        try {
            // Obtenir les IDs des utilisateurs
            $sql = "SELECT iduser, username FROM users WHERE username IN (?, ?)";
            $stmt = $this->db->getConnection()->prepare($sql);
            $stmt->execute([$username1, $username2]);
            $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if (count($users) !== 2) {
                return null;
            }
            $user1_id = $users[0]['iduser'];
            $user2_id = $users[1]['iduser'];
            $user1_name = $users[0]['username'];
            $user2_name = $users[1]['username'];
            // Pour la requête discussion, peu importe l'ordre
            $id_min = min($user1_id, $user2_id);
            $id_max = max($user1_id, $user2_id);
            // Trouver l'ID de la discussion
            $sqlDiscussion = "SELECT id FROM discussions WHERE (user1_id = ? AND user2_id = ?) OR (user1_id = ? AND user2_id = ?)";
            $stmt = $this->db->getConnection()->prepare($sqlDiscussion);
            $stmt->execute([$id_min, $id_max, $id_max, $id_min]);
            $discussion = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$discussion) {
                return null;
            }
            // Récupérer le dernier message de la discussion
            $sql = "SELECT m.*, u.username as sender, ur.username as receiver_username
                    FROM messages m
                    JOIN users u ON m.sender_id = u.iduser
                    JOIN users ur ON (
                        CASE WHEN m.sender_id = ? THEN ? ELSE ? END = ur.iduser
                    )
                    WHERE m.discussion_id = ?
                    ORDER BY m.created_at DESC
                    LIMIT 1";
            // On veut le destinataire de ce message
            $stmt = $this->db->getConnection()->prepare($sql);
            // Si le sender est user1, receiver est user2, sinon l'inverse
            $stmt->execute([$user1_id, $user2_id, $user1_id, $discussion['id']]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération du dernier message: " . $e->getMessage());
            return null;
        }
    }
}

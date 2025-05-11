<?php
require_once __DIR__ . '/../models/Message.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Notification.php';
require_once __DIR__ . '/../config/database.php';

class ChatController {
    private $db;

    public function __construct() {
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        $this->db = new Database();
    }

    public function index() {
        if (!isset($_SESSION['user_id'])) {
            error_log("No user_id in session");
            header('Location: index.php?action=login');
            exit;
        }

        try {
            // Get chat data including last messages
            $chatData = $this->getChatData();
            $users = $chatData['users'];
            $selectedUser = $chatData['selectedUser'];

            // Si c'est une requête de synchronisation, retourner les données en JSON
            if (isset($_GET['sync']) && $_GET['sync'] == 1) {
                header('Content-Type: application/json');
                echo json_encode([
                    'users' => $users,
                    'selectedUser' => $selectedUser
                ]);
                exit;
            }

            // Get notification counts for users
            $notificationModel = new Notification();
            $notificationCounts = $notificationModel->getUnreadNotificationCountsByUser();

            include __DIR__ . '/../views/chat/chat.php';
        } catch (Exception $e) {
            error_log("Error in ChatController::index(): " . $e->getMessage());
            echo "Une erreur est survenue. Veuillez réessayer plus tard.";
        }
    }

    public function getNotificationCountsAjax() {
        $notificationModel = new Notification();
        $counts = $notificationModel->getUnreadNotificationCountsByUser();
        header('Content-Type: application/json');
        echo json_encode($counts);
        exit;
    }

    // Method to add or update reaction
    public function addOrUpdateReaction() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message_id']) && isset($_POST['reaction_type'])) {
            $messageId = $_POST['message_id'];
            $reactionType = $_POST['reaction_type'];

            // Récupérer l'ID de l'utilisateur
            $userId = null;
            if (isset($_SESSION['user_id'])) {
                $userId = $_SESSION['user_id'];
            } else if (isset($_SESSION['username'])) {
                $userModel = new User();
                $user = $userModel->getByUsername($_SESSION['username']);
                if ($user) {
                    $userId = $user['iduser'];
                    $_SESSION['user_id'] = $userId;
                }
            }

            if (!$userId) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'error' => 'Utilisateur non identifié']);
                exit;
            }

            $msgModel = new Message();
            $success = $msgModel->addOrUpdateReaction($messageId, $userId, $reactionType);

            // Récupérer toutes les réactions pour ce message
            $reactions = [];
            if ($success) {
                $reactions = $msgModel->getReactionsByMessageId($messageId);
            }

            header('Content-Type: application/json');
            echo json_encode([
                'success' => $success,
                'reactions' => $reactions
            ]);
            exit;
        }
    }

    // New method to remove reaction
    public function removeReaction() {
        if (!isset($_SESSION['user_id']) || !isset($_POST['message_id']) || !isset($_POST['reaction_type'])) {
            echo json_encode(['success' => false, 'error' => 'Paramètres manquants']);
            return;
        }

        $messageId = $_POST['message_id'];
        $userId = $_SESSION['user_id'];
        $reactionType = $_POST['reaction_type'];

        try {
            $sql = "DELETE FROM message_reactions
                   WHERE message_id = ? AND user_id = ? AND reaction_type = ?";
            $stmt = $this->db->getConnection()->prepare($sql);
            $success = $stmt->execute([$messageId, $userId, $reactionType]);

            echo json_encode(['success' => $success]);
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    public function getChatData() {
        if (!isset($_SESSION['user_id'])) {
            error_log("No user_id in session");
            return ['users' => [], 'selectedUser' => null];
        }

        $currentUserId = $_SESSION['user_id'];

        try {
            // Optimisation de la requête pour récupérer les derniers messages
            $sql = "WITH LastMessages AS (
                SELECT
                    d.user1_id,
                    d.user2_id,
                    m.message,
                    m.created_at,
                    m.sender_id,
                    ROW_NUMBER() OVER (PARTITION BY
                        CASE
                            WHEN d.user1_id = ? THEN d.user2_id
                            ELSE d.user1_id
                        END
                    ORDER BY m.created_at DESC) as rn
                FROM discussions d
                JOIN messages m ON d.id = m.discussion_id
                WHERE d.user1_id = ? OR d.user2_id = ?
            )
            SELECT
                u.*,
                COALESCE(unread.count, 0) as unread_count,
                lm.message as last_message_content,
                lm.created_at as last_message_time,
                lm.sender_id as last_message_sender_id
            FROM users u
            LEFT JOIN (
                SELECT
                    CASE
                        WHEN user1_id = ? THEN user2_id
                        ELSE user1_id
                    END as other_user_id,
                    message,
                    created_at,
                    sender_id
                FROM LastMessages
                WHERE rn = 1
            ) lm ON u.iduser = lm.other_user_id
            LEFT JOIN (
                SELECT
                    sender_id,
                    COUNT(*) as count
                FROM messages m
                JOIN discussions d ON m.discussion_id = d.id
                WHERE ((d.user1_id = ? AND d.user2_id = m.sender_id)
                    OR (d.user2_id = ? AND d.user1_id = m.sender_id))
                AND m.is_read = 0
                GROUP BY sender_id
            ) unread ON u.iduser = unread.sender_id
            WHERE u.iduser != ?
            ORDER BY COALESCE(lm.created_at, '1970-01-01') DESC, u.username ASC";

            $stmt = $this->db->getConnection()->prepare($sql);
            $params = array_fill(0, 7, $currentUserId);
            $stmt->execute($params);
            $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $selectedUser = $_GET['user'] ?? null;

            // Si c'est une requête AJAX, inclure le timestamp
            if (isset($_GET['sync']) && $_GET['sync'] == 1) {
                return [
                    'users' => $users,
                    'selectedUser' => $selectedUser,
                    'timestamp' => time()
                ];
            }

            return ['users' => $users, 'selectedUser' => $selectedUser];
        } catch (PDOException $e) {
            error_log("Database error in getChatData: " . $e->getMessage());
            return ['users' => [], 'selectedUser' => null];
        }
    }

    private function sendWebSocketNotification($senderId, $receiverId, $message) {
        $wsUrl = 'ws://localhost:8080';
        $data = json_encode([
            'type' => 'new_message',
            'senderId' => $senderId,
            'receiverId' => $receiverId,
            'message' => $message
        ]);

        $errorNumber = 0;
        $errorString = '';
        $socket = @stream_socket_client("tcp://localhost:8080", $errorNumber, $errorString, 2);
        if (!$socket) {
            error_log("WebSocket connection failed: $errorString ($errorNumber)");
            return false;
        }

        // Perform WebSocket handshake
        $key = base64_encode(random_bytes(16));
        $headers = "GET / HTTP/1.1\r\n" .
                   "Host: localhost:8080\r\n" .
                   "Upgrade: websocket\r\n" .
                   "Connection: Upgrade\r\n" .
                   "Sec-WebSocket-Key: $key\r\n" .
                   "Sec-WebSocket-Version: 13\r\n\r\n";
        fwrite($socket, $headers);

        // Read handshake response
        $response = fread($socket, 1500);
        if (strpos($response, ' 101 ') === false) {
            error_log("WebSocket handshake failed");
            fclose($socket);
            return false;
        }

        // Send data frame (text frame)
        $frame = chr(0x81);
        $length = strlen($data);
        if ($length <= 125) {
            $frame .= chr($length);
        } elseif ($length <= 65535) {
            $frame .= chr(126) . pack('n', $length);
        } else {
            $frame .= chr(127) . pack('J', $length);
        }
        $frame .= $data;
        fwrite($socket, $frame);

        fclose($socket);
        return true;
    }

    public function submitMessage() {
        error_log("submitMessage method entered");
        error_log("POST data: " . print_r($_POST, true));
        error_log("FILES data: " . print_r($_FILES, true));
        error_log("Session data: " . print_r($_SESSION, true));

        if (!isset($_SESSION['username'])) {
            error_log("User not logged in");
            header('Location: index.php?action=login');
            exit;
        }

        // Vérifier si l'ID de l'utilisateur est dans la session
        if (!isset($_SESSION['user_id'])) {
            error_log("User ID not found in session, trying to get it from database");
            $userModel = new User();
            $user = $userModel->getByUsername($_SESSION['username']);
            if ($user) {
                $_SESSION['user_id'] = $user['iduser'];
                error_log("User ID retrieved from database: " . $user['iduser']);
            } else {
                error_log("Could not find user in database");
                header('Location: index.php?action=login');
                exit;
            }
        }

        $senderUsername = $_SESSION['username'];
        $receiverUsername = $_POST['receiver'] ?? null;
        $message = $_POST['message'] ?? '';  // Utiliser une chaîne vide par défaut au lieu de null

        error_log("submitMessage called with receiver: " . var_export($receiverUsername, true) . ", message: " . var_export($message, true));

        if (!$receiverUsername || (empty($message) && !isset($_FILES['photo']))) {
            error_log("submitMessage missing data: receiver or message/photo is null");
            header('Location: index.php?action=chat&user=' . urlencode($receiverUsername ?? '') . '&error=missing_data');
            exit;
        }

        $userModel = new User();
        $senderUser = $userModel->getByUsername($senderUsername);
        $receiverUser = $userModel->getByUsername($receiverUsername);

        error_log("Sender user: " . print_r($senderUser, true));
        error_log("Receiver user: " . print_r($receiverUser, true));

        if (!$senderUser || !$receiverUser) {
            error_log("submitMessage user not found: sender or receiver");
            header('Location: index.php?action=chat&user=' . urlencode($receiverUsername) . '&error=user_not_found');
            exit;
        }

        $senderId = $senderUser['iduser'];
        $receiverId = $receiverUser['iduser'];

        $msgModel = new Message();
        try {
            // Si le message est vide mais qu'il y a une photo, utiliser un espace pour éviter "undefined"
            if (empty($message) && isset($_FILES['photo'])) {
                $message = " "; // Espace pour éviter d'afficher "undefined"
            }

            $success = $msgModel->save($senderId, $receiverId, $message);
            error_log("submitMessage save result: " . var_export($success, true));

            // Si le message a été enregistré avec succès et qu'il y a une photo
            if ($success && isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
                $messageId = $this->db->getConnection()->lastInsertId();
                $this->savePhoto($messageId, $_FILES['photo']);
            }

            if ($success) {
                // Message envoyé avec succès
                header('Content-Type: application/json');
                echo json_encode(['success' => true]);
            } else {
                // Erreur lors de l'envoi
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'error' => 'Erreur lors de l\'envoi du message']);
            }
        } catch (Exception $e) {
            error_log("Error saving message: " . $e->getMessage());
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'error' => $e->getMessage(),
                'is_offensive' => strpos($e->getMessage(), 'contenu inapproprié') !== false
            ]);
        }
        exit;
    }

    // Méthode pour sauvegarder une photo
    private function savePhoto($messageId, $photoFile) {
        try {
            // Vérifier si le dossier uploads existe, sinon le créer
            $uploadDir = 'uploads/';
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            // Générer un nom de fichier unique
            $fileName = uniqid() . '_' . basename($photoFile['name']);
            $filePath = $uploadDir . $fileName;

            // Déplacer le fichier téléchargé vers le dossier uploads
            if (move_uploaded_file($photoFile['tmp_name'], $filePath)) {
                // Enregistrer les informations de la photo dans la base de données
                $sql = "INSERT INTO photos (message_id, file_path, file_name, file_type, file_size, created_at)
                        VALUES (?, ?, ?, ?, ?, NOW())";
                $stmt = $this->db->getConnection()->prepare($sql);
                $stmt->execute([
                    $messageId,
                    $filePath,
                    $fileName,
                    $photoFile['type'],
                    $photoFile['size']
                ]);

                error_log("Photo saved successfully: " . $filePath);
                return true;
            } else {
                error_log("Failed to move uploaded file: " . $photoFile['name']);
                return false;
            }
        } catch (Exception $e) {
            error_log("Error saving photo: " . $e->getMessage());
            return false;
        }
    }

    public function markNotificationsAsSeen() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['receiver_id'])) {
            $receiverId = $_POST['receiver_id'];
            $notificationModel = new Notification();
            $notificationModel->markAsSeen($receiverId);
            header('Content-Type: application/json');
            echo json_encode(['success' => true]);
            exit;
        }
    }


    public function fetchMessages() {
        $senderUsername = $_POST['sender'];
        $receiverUsername = $_POST['receiver'];

        $userModel = new User();
        $senderUser = $userModel->getByUsername($senderUsername);
        $receiverUser = $userModel->getByUsername($receiverUsername);

        if (!$senderUser || !$receiverUser) {
            error_log("Users not found in fetchMessages");
            return;
        }

        $msgModel = new Message();
        $messages = $msgModel->getMessages($senderUser['iduser'], $receiverUser['iduser']);

        // Récupérer les photos pour chaque message
        $db = new Database();
        foreach ($messages as &$message) {
            $sql = "SELECT * FROM photos WHERE message_id = ?";
            $stmt = $db->getConnection()->prepare($sql);
            $stmt->execute([$message['id']]);
            $photo = $stmt->fetch(PDO::FETCH_ASSOC);
            $message['photo'] = $photo;
        }

        include 'views/chat/fetch_messages.php';
    }

    public function fetchNotifications() {
        $messageId = $_POST['message_id'] ?? null;
        if ($messageId) {
            $notificationModel = new Notification();
            $notifications = $notificationModel->getNotificationsByMessageId($messageId);
            header('Content-Type: application/json');
            echo json_encode($notifications);
            exit;
        }
    }

    public function markNotificationRead() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['notification_id'])) {
            $notificationId = $_POST['notification_id'];
            $notificationModel = new Notification();
            // Use idnot field for notification id
            $success = $notificationModel->markAsRead($notificationId);
            header('Content-Type: application/json');
            echo json_encode(['success' => $success]);
            exit;
        }
    }

    public function deleteMessage() {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('Méthode non autorisée');
            }

            if (!isset($_POST['message_id']) || !is_numeric($_POST['message_id'])) {
                throw new Exception('ID du message invalide');
            }

            if (!isset($_SESSION['username'])) {
                throw new Exception('Utilisateur non authentifié');
            }

            $messageId = (int)$_POST['message_id'];
            $username = $_SESSION['username'];

            // Récupérer l'ID de l'utilisateur à partir de son nom d'utilisateur
            $userModel = new User();
            $user = $userModel->getByUsername($username);

            if (!$user) {
                throw new Exception('Utilisateur non trouvé');
            }

            $msgModel = new Message();
            $success = $msgModel->deleteMessage($messageId, $user['iduser']);

            if (!$success) {
                throw new Exception('Vous n\'êtes pas autorisé à supprimer ce message');
            }

            header('Content-Type: application/json');
            echo json_encode(['success' => true]);
            exit;

        } catch (Exception $e) {
            http_response_code(500);
            echo "Erreur système : " . $e->getMessage();
            exit;
        }
    }

    public function updateMessage() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message_id']) && isset($_POST['content'])) {
            $messageId = $_POST['message_id'];
            $username = $_SESSION['username'];
            $newContent = $_POST['content'];

            $msgModel = new Message();
            $success = $msgModel->updateMessage($messageId, $username, $newContent);

            header('Content-Type: application/json');
            echo json_encode(['success' => $success]);
            exit;
        }
    }

    // New method to add or update reaction

    public function fetchLastMessages() {
        if (!isset($_SESSION['username'])) {
            header('Content-Type: application/json');
            echo json_encode([]);
            exit;
        }

        $userModel = new User();
        $messageModel = new Message();
        $currentUser = $_SESSION['username'];
        $users = $userModel->getAllExcept($currentUser);
        $lastMessages = [];
        foreach ($users as $user) {
            $username = is_array($user) ? $user['username'] : $user;
            $lastMessage = $messageModel->getLastMessageBetweenUsers($currentUser, $username);
            if ($lastMessage) {
                $lastMessages[$username] = [
                    'sender' => $lastMessage['sender'],
                    'receiver' => $lastMessage['receiver_username'],
                    'content' => $lastMessage['content'],
                    'created_at' => date('d/m/Y H:i', strtotime($lastMessage['created_at']))
                ];
            }
        }
        header('Content-Type: application/json');
        echo json_encode($lastMessages);
        exit;
    }

    public function getRealtimeUpdates() {
        if (!isset($_SESSION['user_id']) || !isset($_GET['last_update'])) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Invalid request']);
            exit;
        }

        $currentUserId = $_SESSION['user_id'];
        $lastUpdate = intval($_GET['last_update']);

        try {
            // Vérifier les nouveaux messages
            $sql = "SELECT
                    m.id,
                    m.message,
                    m.created_at,
                    m.sender_id,
                    m.is_read,
                    d.user1_id,
                    d.user2_id
                FROM messages m
                JOIN discussions d ON m.discussion_id = d.id
                WHERE (d.user1_id = ? OR d.user2_id = ?)
                AND UNIX_TIMESTAMP(m.created_at) > ?";

            $stmt = $this->db->getConnection()->prepare($sql);
            $stmt->execute([$currentUserId, $currentUserId, $lastUpdate]);
            $newMessages = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Vérifier les messages lus
            $sql = "SELECT m.id
                FROM messages m
                JOIN discussions d ON m.discussion_id = d.id
                WHERE (d.user1_id = ? OR d.user2_id = ?)
                AND m.is_read = 1
                AND UNIX_TIMESTAMP(m.updated_at) > ?";

            $stmt = $this->db->getConnection()->prepare($sql);
            $stmt->execute([$currentUserId, $currentUserId, $lastUpdate]);
            $readMessages = $stmt->fetchAll(PDO::FETCH_COLUMN);

            header('Content-Type: application/json');
            echo json_encode([
                'new_messages' => $newMessages,
                'read_messages' => $readMessages,
                'timestamp' => time()
            ]);
            exit;

        } catch (Exception $e) {
            error_log("Error in getRealtimeUpdates: " . $e->getMessage());
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Server error']);
            exit;
        }
    }

    public function getSeenStatus() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['receiver'])) {
            $sender = $_SESSION['username'];
            $receiver = $_POST['receiver'];

            try {
                // Récupérer le dernier message envoyé
                $sql = "SELECT m.seen, m.seen_at
                       FROM messages m
                       JOIN discussions d ON m.discussion_id = d.id
                       JOIN users u1 ON m.sender_id = u1.iduser
                       JOIN users u2 ON m.receiver_id = u2.iduser
                       WHERE u1.username = ? AND u2.username = ?
                       ORDER BY m.created_at DESC
                       LIMIT 1";

                $stmt = $this->db->getConnection()->prepare($sql);
                $stmt->execute([$sender, $receiver]);
                $result = $stmt->fetch(PDO::FETCH_ASSOC);

                header('Content-Type: application/json');
                if ($result) {
                    echo json_encode([
                        'success' => true,
                        'seen' => (bool)$result['seen'],
                        'seen_at' => $result['seen_at']
                    ]);
                } else {
                    echo json_encode([
                        'success' => true,
                        'seen' => false,
                        'seen_at' => null
                    ]);
                }
            } catch (PDOException $e) {
                echo json_encode([
                    'success' => false,
                    'error' => 'Erreur lors de la récupération du statut'
                ]);
            }
            exit;
        }
    }

    public function markMessagesAsSeen() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $sender = $_POST['sender'] ?? '';
            $receiver = $_POST['receiver'] ?? '';

            if (empty($sender) || empty($receiver)) {
                echo json_encode(['success' => false, 'error' => 'Paramètres manquants']);
                return;
            }

            try {
                // Mettre à jour les messages non lus
                $sql = "UPDATE messages m
                       JOIN users u1 ON m.sender_id = u1.iduser
                       JOIN users u2 ON m.receiver_id = u2.iduser
                       SET m.seen = 1, m.seen_at = NOW()
                       WHERE u1.username = ? AND u2.username = ? AND m.seen = 0";

                $stmt = $this->db->getConnection()->prepare($sql);
                $stmt->execute([$sender, $receiver]);

                echo json_encode(['success' => true]);
            } catch (PDOException $e) {
                echo json_encode(['success' => false, 'error' => 'Erreur lors du marquage des messages']);
            }
        } else {
            echo json_encode(['success' => false, 'error' => 'Méthode non autorisée']);
        }
    }

    public function addReaction() {
        if (!isset($_SESSION['user_id']) || !isset($_POST['message_id']) || !isset($_POST['reaction_type'])) {
            echo json_encode(['success' => false, 'error' => 'Paramètres manquants']);
            return;
        }

        $messageId = $_POST['message_id'];
        $userId = $_SESSION['user_id'];
        $reactionType = $_POST['reaction_type'];

        try {
            // Vérifier si l'utilisateur a déjà réagi avec cet emoji
            $sql = "SELECT id FROM message_reactions
                   WHERE message_id = ? AND user_id = ? AND reaction_type = ?";
            $stmt = $this->db->getConnection()->prepare($sql);
            $stmt->execute([$messageId, $userId, $reactionType]);
            $existing = $stmt->fetch();

            if ($existing) {
                echo json_encode(['success' => false, 'error' => 'Réaction déjà existante']);
                return;
            }

            // Ajouter la nouvelle réaction
            $sql = "INSERT INTO message_reactions (message_id, user_id, reaction_type)
                   VALUES (?, ?, ?)";
            $stmt = $this->db->getConnection()->prepare($sql);
            $success = $stmt->execute([$messageId, $userId, $reactionType]);

            echo json_encode(['success' => $success]);
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    public function getReactions() {
        if (!isset($_GET['message_id'])) {
            echo json_encode(['success' => false, 'error' => 'ID du message manquant']);
            return;
        }

        $messageId = $_GET['message_id'];

        try {
            $sql = "SELECT mr.*, u.username
                   FROM message_reactions mr
                   JOIN users u ON mr.user_id = u.iduser
                   WHERE mr.message_id = ?";
            $stmt = $this->db->getConnection()->prepare($sql);
            $stmt->execute([$messageId]);
            $reactions = $stmt->fetchAll(PDO::FETCH_ASSOC);

            echo json_encode($reactions);
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
    }
}

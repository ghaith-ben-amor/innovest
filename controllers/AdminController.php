<?php
class AdminController {
    private $userModel;
    private $messageModel;

    public function __construct() {
        $this->userModel = new UserModel();
        $this->messageModel = new MessageModel();
    }

    public function dashboard() {
        // Vérifier si l'utilisateur est admin
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header('Location: index.php?action=login');
            exit;
        }

        // Récupérer les statistiques
        $stats = [
            'totalUsers' => $this->userModel->getTotalUsers(),
            'totalMessages' => $this->messageModel->getTotalMessages(),
            'activeUsers' => $this->userModel->getActiveUsers(),
            'todayMessages' => $this->messageModel->getTodayMessages()
        ];

        // Récupérer l'activité récente
        $recentActivity = $this->messageModel->getRecentActivity();

        // Récupérer les nouveaux utilisateurs
        $newUsers = $this->userModel->getNewUsers();

        // Charger la vue
        require_once 'views/backoffice/dashboard.php';
    }

    public function users() {
        // Vérifier si l'utilisateur est admin
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header('Location: index.php?action=login');
            exit;
        }

        // Récupérer la liste des utilisateurs avec les messages non lus
        $users = $this->userModel->getAllUsers();
        foreach ($users as &$user) {
            $user['unread_count'] = $this->messageModel->countUnreadMessages($_SESSION['user_id'], $user['iduser']);
        }

        // Charger la vue
        require_once 'views/backoffice/users.php';
    }

    public function messages() {
        // Vérifier si l'utilisateur est admin
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header('Location: index.php?action=login');
            exit;
        }

        // Récupérer toutes les discussions
        $discussions = $this->messageModel->getAllDiscussions();

        // Charger la vue
        require_once 'views/backoffice/messages.php';
    }

    public function getDiscussionMessages() {
        // Vérifier si l'utilisateur est admin
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Accès non autorisé']);
            exit;
        }

        $discussionId = $_GET['id'] ?? null;
        if (!$discussionId) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'ID de discussion manquant']);
            exit;
        }

        $messages = $this->messageModel->getDiscussionMessages($discussionId);

        header('Content-Type: application/json');
        echo json_encode($messages);
        exit;
    }

    public function getAdminStats() {
        // Vérifier si l'utilisateur est admin
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            echo json_encode(['error' => 'Accès non autorisé']);
            exit;
        }

        // Récupérer la période si elle est spécifiée
        $period = $_GET['period'] ?? null;

        if ($period) {
            $stats = [
                'totalUsers' => $this->userModel->getTotalUsers(),
                'totalMessages' => $this->messageModel->getMessagesByPeriod($period),
                'activeUsers' => $this->userModel->getActiveUsersByPeriod($period),
                'todayMessages' => $period === 'today' ? $this->messageModel->getTodayMessages() : $this->messageModel->getMessagesByPeriod($period)
            ];
        } else {
            $stats = [
                'totalUsers' => $this->userModel->getTotalUsers(),
                'totalMessages' => $this->messageModel->getTotalMessages(),
                'activeUsers' => $this->userModel->getActiveUsers(),
                'todayMessages' => $this->messageModel->getTodayMessages()
            ];
        }

        echo json_encode($stats);
    }

    public function addUser() {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            echo json_encode(['success' => false, 'message' => 'Accès non autorisé']);
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'username' => $_POST['username'],
                'password' => $_POST['password']
            ];

            try {
                $this->userModel->createUser($data);
                echo json_encode(['success' => true, 'message' => 'Utilisateur ajouté avec succès']);
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'message' => 'Erreur lors de l\'ajout de l\'utilisateur']);
            }
        }
    }

    public function editUser() {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            echo json_encode(['success' => false, 'message' => 'Accès non autorisé']);
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $iduser = $_POST['iduser'];
            $data = [
                'username' => $_POST['username']
            ];

            try {
                $this->userModel->updateUser($iduser, $data);
                echo json_encode(['success' => true, 'message' => 'Utilisateur modifié avec succès']);
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'message' => 'Erreur lors de la modification de l\'utilisateur']);
            }
        }
    }

    public function deleteUser() {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            echo json_encode(['success' => false, 'message' => 'Accès non autorisé']);
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $iduser = $_POST['iduser'];

            try {
                $this->userModel->deleteUser($iduser);
                echo json_encode(['success' => true, 'message' => 'Utilisateur supprimé avec succès']);
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'message' => 'Erreur lors de la suppression de l\'utilisateur']);
            }
        }
    }

    public function deleteMessage() {
        try {
            // Vérifier les droits d'admin
            if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
                throw new Exception('Accès non autorisé');
            }

            // Vérifier la méthode HTTP
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('Méthode non autorisée');
            }

            // Vérifier l'ID
            if (!isset($_POST['id']) || !is_numeric($_POST['id'])) {
                throw new Exception('ID du message invalide');
            }

            $id = (int)$_POST['id'];

            // Vérifier si le message existe
            $message = $this->messageModel->getMessage($id);
            if (!$message) {
                throw new Exception('Message non trouvé');
            }

            // Tenter la suppression
            $result = $this->messageModel->deleteMessage($id);

            if (!$result) {
                throw new Exception('Échec de la suppression du message');
            }

            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'message' => 'Message supprimé avec succès']);

        } catch (Exception $e) {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

}
// Before (error line 211):
// $query = "SELECT * FROM users WHERE du

// After correction:
$query = "SELECT * FROM users WHERE is_active = 1";  // Example valid condition
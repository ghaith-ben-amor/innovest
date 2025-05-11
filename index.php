<?php
session_start();
header('Content-Type: text/html; charset=utf-8');
error_log("Session data at start: " . print_r($_SESSION, true));

require_once 'config/Database.php';
require_once 'controllers/AdminController.php';
require_once 'controllers/AuthController.php';
require_once 'controllers/ChatController.php';
require_once 'controllers/BannedWordController.php';
require_once 'models/UserModel.php';
require_once 'models/MessageModel.php';

$action = $_GET['action'] ?? 'home';

// Vérifier si l'utilisateur est connecté pour les pages protégées
if (!isset($_SESSION['username']) && $action !== 'login' && $action !== 'register') {
    header('Location: index.php?action=login');
    exit();
}

// Debug session
error_log("Current session data: " . print_r($_SESSION, true));
error_log("Current action: " . $action);

// Instancier les contrôleurs
$authController = new AuthController();
$adminController = new AdminController();

// Router
switch ($action) {
    case 'login':
        $authController->login();
        break;
    case 'register':
        $authController->register();
        break;
    case 'logout':
        $authController->logout();
        break;
    case 'home':
        include 'views/frontoffice/index.php';
        break;
    case 'chat':
        $controller = new ChatController();
        $controller->index();
        break;
    case 'submitMessage':
        $controller = new ChatController();
        $controller->submitMessage();
        break;
    case 'fetchMessages':
        $controller = new ChatController();
        $controller->fetchMessages();
        break;
    case 'deleteMessage':
        $controller = new ChatController();
        $controller->deleteMessage();
        break;
    case 'updateMessage':
        $controller = new ChatController();
        $controller->updateMessage();
        break;
    case 'adminDashboard':
        $adminController->dashboard();
        break;
    case 'adminUsers':
        $adminController->users();
        break;
    case 'adminMessages':
        $adminController->messages();
        break;
    case 'getDiscussionMessages':
        $adminController->getDiscussionMessages();
        break;
    case 'getAdminStats':
        $adminController->getAdminStats();
        break;
    case 'getUser':
        if (isset($_GET['id'])) {
            echo json_encode($adminController->getUser($_GET['id']));
        }
        break;
    case 'saveUser':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            echo json_encode($adminController->saveUser($_POST));
        }
        break;
    case 'deleteUser':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            echo json_encode($adminController->deleteUser($_POST['id']));
        }
        break;
    case 'deleteMessage':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            echo json_encode($adminController->deleteMessage($_POST['message_id']));
        }
        break;
    case 'flagMessage':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            echo json_encode($adminController->flagMessage($_POST['message_id']));
        }
        break;
    case 'dashboard':
        if (isset($_SESSION['is_admin']) && $_SESSION['is_admin']) {
            $adminController->dashboard();
        } else {
            header('Location: index.php?action=home');
            exit;
        }
        break;
    case 'bannedWords':
        $controller = new BannedWordController();
        $controller->index();
        break;
    case 'addBannedWord':
        $controller = new BannedWordController();
        $controller->add();
        break;
    case 'updateBannedWord':
        $controller = new BannedWordController();
        $controller->update();
        break;
    case 'deleteBannedWord':
        $controller = new BannedWordController();
        $controller->delete();
        break;
    case 'getFilteredMessages':
        $controller = new BannedWordController();
        $controller->getFilteredMessages();
        break;
    case 'markMessagesAsSeen':
        $chatController->markMessagesAsSeen();
        break;
    case 'getSeenStatus':
        $chatController->getSeenStatus();
        break;
    case 'addReaction':
        $chatController->addReaction();
        break;
    case 'removeReaction':
        $chatController->removeReaction();
        break;
    case 'getReactions':
        $chatController->getReactions();
        break;
    case 'getBannedWords':
        $controller = new BannedWordController();
        $controller->getBannedWords();
        break;
    default:
        echo "Page non trouvée.";
}

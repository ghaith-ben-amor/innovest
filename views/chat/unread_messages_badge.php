<?php
// Ce fichier affiche un badge avec le nombre de messages non lus
require_once __DIR__ . '/../../models/Message.php';

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    echo '<span class="badge badge-danger">0</span>';
    exit;
}

$userId = $_SESSION['user_id'];
$messageModel = new Message();
$unreadCount = $messageModel->countUnreadMessages($userId);

// Afficher le badge uniquement s'il y a des messages non lus
if ($unreadCount > 0) {
    echo '<span class="badge badge-danger">' . $unreadCount . '</span>';
} else {
    echo '<span class="badge badge-danger d-none">0</span>';
}
?>
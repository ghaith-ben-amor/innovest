<?php
session_start();
require_once __DIR__ . '/../../models/Message.php';

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('HTTP/1.1 401 Unauthorized');
    exit('Non autorisé');
}

// Vérifier si les paramètres nécessaires sont présents
if (!isset($_POST['other_user_id'])) {
    header('HTTP/1.1 400 Bad Request');
    exit('Paramètres manquants');
}

$userId = $_SESSION['user_id'];
$otherUserId = $_POST['other_user_id'];

// Marquer les messages comme lus
$messageModel = new Message();
$result = $messageModel->markMessagesAsRead($userId, $otherUserId);

// Répondre avec un statut JSON
header('Content-Type: application/json');
echo json_encode(['success' => $result]);
?>
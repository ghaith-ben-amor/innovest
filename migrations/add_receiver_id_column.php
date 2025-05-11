<?php
require_once __DIR__ . '/../config/database.php';

$db = new Database();
$conn = $db->getConnection();

try {
    // Ajouter la colonne receiver_id à la table notifications
    $sql = "ALTER TABLE notifications ADD COLUMN receiver_id INT NOT NULL AFTER message_id";
    $conn->exec($sql);
    
    echo "Migration réussie : colonne receiver_id ajoutée à la table notifications.\n";
} catch (PDOException $e) {
    echo "Échec de la migration : " . $e->getMessage() . "\n";
}
?>
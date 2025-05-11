<?php
require_once __DIR__ . '/../config/database.php';

$db = new Database();
$conn = $db->getConnection();

try {
    // Ajouter la colonne is_read à la table messages
    $sql = "ALTER TABLE messages ADD COLUMN is_read TINYINT(1) NOT NULL DEFAULT 0 AFTER has_offensive_content";
    $conn->exec($sql);
    
    echo "Migration réussie : colonne is_read ajoutée à la table messages.\n";
} catch (PDOException $e) {
    echo "Échec de la migration : " . $e->getMessage() . "\n";
}
?>
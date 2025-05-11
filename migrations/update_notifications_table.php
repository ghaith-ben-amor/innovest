<?php
require_once __DIR__ . '/../config/database.php';

$db = new Database();
$conn = $db->getConnection();

try {
    // Vérifier si la colonne receiver_id existe déjà
    $checkColumnSql = "SHOW COLUMNS FROM notifications LIKE 'receiver_id'";
    $checkColumnStmt = $conn->prepare($checkColumnSql);
    $checkColumnStmt->execute();
    
    if ($checkColumnStmt->rowCount() == 0) {
        // Ajouter la colonne receiver_id à la table notifications
        $sql = "ALTER TABLE notifications ADD COLUMN receiver_id INT NOT NULL AFTER message_id";
        $conn->exec($sql);
        echo "Migration réussie : colonne receiver_id ajoutée à la table notifications.\n";
        
        // Mettre à jour les enregistrements existants en utilisant les informations de la table messages
        $updateSql = "UPDATE notifications n 
                      JOIN messages m ON n.message_id = m.id 
                      SET n.receiver_id = m.receiver 
                      WHERE n.receiver_id = 0";
        $conn->exec($updateSql);
        echo "Migration réussie : les enregistrements existants ont été mis à jour avec les valeurs de receiver_id.\n";
    } else {
        echo "La colonne receiver_id existe déjà dans la table notifications.\n";
    }
    
    echo "Migration terminée avec succès.\n";
} catch (PDOException $e) {
    echo "Échec de la migration : " . $e->getMessage() . "\n";
}
?>
<?php
// Script pour exécuter directement la migration SQL

// Connexion à la base de données
$host = "localhost";
$dbname = "chat";
$username = "root";
$password = "";

try {
    // Connexion à la base de données
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Requête SQL pour ajouter la colonne is_read
    $sql = "ALTER TABLE messages ADD COLUMN is_read TINYINT(1) NOT NULL DEFAULT 0 AFTER has_offensive_content";
    
    // Exécution de la requête
    $conn->exec($sql);
    
    echo "Migration réussie : colonne is_read ajoutée à la table messages.\n";
} catch(PDOException $e) {
    echo "Erreur lors de la migration : " . $e->getMessage() . "\n";
}
?>
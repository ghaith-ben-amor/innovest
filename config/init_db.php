<?php
require_once 'database.php';

try {
    $db = new Database();
    $conn = $db->getConnection();

    // Lire le contenu du fichier SQL
    $sql = file_get_contents(__DIR__ . '/database.sql');

    // Exécuter les requêtes SQL
    $conn->exec($sql);
    
    echo "Base de données initialisée avec succès!\n";
} catch(PDOException $e) {
    echo "Erreur lors de l'initialisation de la base de données : " . $e->getMessage() . "\n";
} 
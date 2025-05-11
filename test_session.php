<?php
session_start();
require_once __DIR__ . '/config/database.php';

echo "<h2>Session Data:</h2>";
echo "<pre>";
print_r($_SESSION);
echo "</pre>";

echo "<h2>Testing Database Connection:</h2>";
try {
    $db = new Database();
    $conn = $db->getConnection();
    
    // Test users query
    $stmt = $conn->query("SELECT * FROM users");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<h3>Users in database:</h3>";
    echo "<pre>";
    print_r($users);
    echo "</pre>";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?> 
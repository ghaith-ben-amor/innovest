<?php
require_once __DIR__ . '/../config/database.php';

class User {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getAllExcept($username) {
        $sql = "SELECT username FROM users WHERE username != ?";
        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->execute([$username]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function getByUsername($username) {
        $sql = "SELECT * FROM users WHERE username = ?";
        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->execute([$username]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($username, $password, $type = 'user') {
        $sql = "INSERT INTO users (username, password, type) VALUES (?, ?, ?)";
        $stmt = $this->db->getConnection()->prepare($sql);
        return $stmt->execute([$username, password_hash($password, PASSWORD_DEFAULT), $type]);
    }

    public function isAdmin($username) {
        $sql = "SELECT type FROM users WHERE username = ?";
        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->execute([$username]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result && $result['type'] === 'admin';
    }
}

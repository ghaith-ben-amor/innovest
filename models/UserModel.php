<?php
class UserModel {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getTotalUsers() {
        $sql = "SELECT COUNT(*) as total FROM users";
        $result = $this->db->query($sql);
        $row = $result->fetch(PDO::FETCH_ASSOC);
        return $row['total'];
    }

    public function getActiveUsers() {
        $sql = "SELECT COUNT(DISTINCT sender_id) as total FROM messages WHERE created_at > DATE_SUB(NOW(), INTERVAL 5 MINUTE)";
        $result = $this->db->query($sql);
        $row = $result->fetch(PDO::FETCH_ASSOC);
        return $row['total'];
    }

    public function getActiveUsersByPeriod($period) {
        $sql = "SELECT COUNT(DISTINCT sender_id) as total FROM messages WHERE 1=1";

        switch ($period) {
            case 'today':
                $sql .= " AND DATE(created_at) = CURDATE()";
                break;
            case 'week':
                $sql .= " AND YEARWEEK(created_at, 1) = YEARWEEK(CURDATE(), 1)";
                break;
            case 'month':
                $sql .= " AND YEAR(created_at) = YEAR(CURDATE()) AND MONTH(created_at) = MONTH(CURDATE())";
                break;
            default:
                $sql .= " AND created_at > DATE_SUB(NOW(), INTERVAL 5 MINUTE)";
                break;
        }

        $result = $this->db->query($sql);
        $row = $result->fetch(PDO::FETCH_ASSOC);
        return $row['total'];
    }

    public function getNewUsers($limit = 5) {
        $sql = "SELECT iduser, username FROM users ORDER BY iduser DESC LIMIT " . (int)$limit;
        $result = $this->db->query($sql);
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllUsers() {
        $sql = "SELECT iduser, username FROM users ORDER BY iduser DESC";
        $result = $this->db->query($sql);
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateUser($iduser, $data) {
        $sql = "UPDATE users SET username = ? WHERE iduser = ?";
        return $this->db->execute($sql, [
            $data['username'],
            $iduser
        ]);
    }

    public function deleteUser($iduser) {
        $sql = "DELETE FROM users WHERE iduser = ?";
        return $this->db->execute($sql, [$iduser]);
    }

    public function createUser($data) {
        $sql = "INSERT INTO users (username, password) VALUES (?, ?)";
        return $this->db->execute($sql, [
            $data['username'],
            password_hash($data['password'], PASSWORD_DEFAULT)
        ]);
    }
}
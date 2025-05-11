<?php
class Database {
    private $host = "localhost";
    private $dbname = "chat";
    private $username = "root";
    private $password = "";
    private $conn;

    public function __construct() {
        try {
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->dbname . ";charset=utf8mb4",
                $this->username,
                $this->password,
                array(
                    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4",
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false
                )
            );
            error_log("Connected to database successfully");
        } catch(PDOException $e) {
            error_log("Database connection error: " . $e->getMessage());
            echo "Erreur de connexion : " . $e->getMessage();
            die();
        }
    }

    public function getConnection() {
        return $this->conn;
    }

    public function query($sql) {
        return $this->conn->query($sql);
    }

    public function execute($sql, $params = []) {
        try {
            $stmt = $this->conn->prepare($sql);
            if (!empty($params)) {
                foreach ($params as $key => $value) {
                    $stmt->bindValue(is_int($key) ? $key + 1 : $key, $value);
                }
            }
            $stmt->execute();
            return $stmt;
        } catch (PDOException $e) {
            error_log("Erreur SQL: " . $e->getMessage() . "\nRequête: " . $sql . "\nParamètres: " . print_r($params, true));
            throw $e;
        }
    }

    public function lastInsertId() {
        return $this->conn->lastInsertId();
    }
}
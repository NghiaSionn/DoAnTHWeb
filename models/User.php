<?php
require_once __DIR__ . '/../config/database.php';

class User {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->connect();
    }

    public function register($username, $password, $role = "user") {
        $sql = "INSERT INTO users (username, password, role) 
                VALUES (:username, :password, :role)";
        $stmt = $this->conn->prepare($sql);

        $hashed = password_hash($password, PASSWORD_BCRYPT);

        $stmt->bindParam(":username", $username);
        $stmt->bindParam(":password", $hashed);
        $stmt->bindParam(":role", $role);

        return $stmt->execute();
    }

    public function login($username) {
        $sql = "SELECT * FROM users WHERE username = :username";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":username", $username);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}

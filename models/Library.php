<?php
require_once __DIR__ . "/../config/database.php";

class Library {
    private $conn;
    private $table = "user_library";

    public function __construct() {
        $db = new Database();
        $this->conn = $db->connect();
        
        // Tạo bảng nếu chưa tồn tại
        $this->createTableIfNotExists();
    }

    private function createTableIfNotExists() {
        $sql = "CREATE TABLE IF NOT EXISTS user_library (
            id INT NOT NULL AUTO_INCREMENT,
            user_id INT NOT NULL,
            book_id INT NOT NULL,
            added_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            UNIQUE KEY unique_user_book (user_id, book_id)
        ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci";
        
        try {
            $this->conn->exec($sql);
        } catch (PDOException $e) {
            // Bảng đã tồn tại hoặc có lỗi
        }
    }

    public function addToLibrary($userId, $bookId) {
        $sql = "INSERT INTO user_library (user_id, book_id) 
                VALUES (:user_id, :book_id)
                ON DUPLICATE KEY UPDATE added_at = CURRENT_TIMESTAMP";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":user_id", $userId);
        $stmt->bindParam(":book_id", $bookId);
        return $stmt->execute();
    }

    public function removeFromLibrary($userId, $bookId) {
        $sql = "DELETE FROM user_library WHERE user_id = :user_id AND book_id = :book_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":user_id", $userId);
        $stmt->bindParam(":book_id", $bookId);
        return $stmt->execute();
    }

    public function getUserLibrary($userId) {
        $sql = "SELECT b.*, ul.added_at 
                FROM user_library ul
                INNER JOIN books b ON ul.book_id = b.id
                WHERE ul.user_id = :user_id
                ORDER BY ul.added_at DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":user_id", $userId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function isInLibrary($userId, $bookId) {
        $sql = "SELECT COUNT(*) as count FROM user_library 
                WHERE user_id = :user_id AND book_id = :book_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":user_id", $userId);
        $stmt->bindParam(":book_id", $bookId);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'] > 0;
    }
}


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

    public function addToLibrary($userId, $bookId, $quantity = 1) {
        // Migration check for quantity column
        $this->checkAndAddQuantityColumn();
        
        $sql = "INSERT INTO user_library (user_id, book_id, quantity) 
                VALUES (:user_id, :book_id, :quantity)
                ON DUPLICATE KEY UPDATE 
                quantity = quantity + :quantity,
                added_at = CURRENT_TIMESTAMP";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":user_id", $userId);
        $stmt->bindParam(":book_id", $bookId);
        $stmt->bindParam(":quantity", $quantity);
        return $stmt->execute();
    }

    public function decreaseQuantity($userId, $bookId, $amount) {
        $sql = "UPDATE user_library SET quantity = quantity - :amount 
                WHERE user_id = :user_id AND book_id = :book_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":amount", $amount, PDO::PARAM_INT);
        $stmt->bindParam(":user_id", $userId);
        $stmt->bindParam(":book_id", $bookId);
        
        if ($stmt->execute()) {
            // Delete if quantity <= 0
            $checkSql = "DELETE FROM user_library WHERE user_id = :user_id AND book_id = :book_id AND quantity <= 0";
            $checkStmt = $this->conn->prepare($checkSql);
            $checkStmt->bindParam(":user_id", $userId);
            $checkStmt->bindParam(":book_id", $bookId);
            $checkStmt->execute();
            return true;
        }
        return false;
    }

    public function removeMultipleFromLibrary($userId, $bookIds) {
        if (empty($bookIds)) return false;
        $placeholders = implode(',', array_fill(0, count($bookIds), '?'));
        $sql = "DELETE FROM user_library WHERE user_id = ? AND book_id IN ($placeholders)";
        $stmt = $this->conn->prepare($sql);
        $params = array_merge([$userId], $bookIds);
        return $stmt->execute($params);
    }

    public function getUserLibrary($userId) {
        $this->checkAndAddQuantityColumn();
        $sql = "SELECT b.*, ul.added_at, ul.quantity as user_quantity 
                FROM user_library ul
                INNER JOIN books b ON ul.book_id = b.id
                WHERE ul.user_id = :user_id
                ORDER BY ul.added_at DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":user_id", $userId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function checkAndAddQuantityColumn() {
        try {
            $checkSql = "SHOW COLUMNS FROM user_library LIKE 'quantity'";
            $stmt = $this->conn->prepare($checkSql);
            $stmt->execute();
            if ($stmt->rowCount() == 0) {
                $alterSql = "ALTER TABLE user_library ADD COLUMN quantity INT DEFAULT 1 AFTER book_id";
                $this->conn->exec($alterSql);
            }
        } catch (Exception $e) {}
    }
}


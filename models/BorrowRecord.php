<?php
require_once __DIR__ . "/../config/database.php";

class BorrowRecord {
    private $conn;
    private $table = "borrow_records";

    public function __construct() {
        $db = new Database();
        $this->conn = $db->connect();
        $this->createTableIfNotExists();
    }

    private function createTableIfNotExists() {
        $sql = "CREATE TABLE IF NOT EXISTS borrow_records (
            id INT NOT NULL AUTO_INCREMENT,
            user_id INT NOT NULL,
            book_id INT NOT NULL,
            borrow_date DATE NOT NULL,
            return_date_due DATE NOT NULL,
            return_date_actual DATE,
            status ENUM('borrowed', 'returned', 'overdue') DEFAULT 'borrowed',
            quantity INT DEFAULT 1,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id)
        ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci";
        
        try {
            $this->conn->exec($sql);
            // Check for quantity column
            $checkSql = "SHOW COLUMNS FROM borrow_records LIKE 'quantity'";
            $stmt = $this->conn->prepare($checkSql);
            $stmt->execute();
            if ($stmt->rowCount() == 0) {
                $this->conn->exec("ALTER TABLE borrow_records ADD COLUMN quantity INT DEFAULT 1 AFTER status");
            }
        } catch (PDOException $e) {
            // Table exists or error
        }
    }

    public function create($userId, $bookId, $quantity, $durationDays) {
        $returnDateDue = date('Y-m-d', strtotime("+$durationDays days"));
        
        $sql = "INSERT INTO borrow_records (user_id, book_id, quantity, borrow_date, return_date_due, status) 
                VALUES (:user_id, :book_id, :quantity, CURRENT_DATE, :return_date_due, 'borrowed')";
                
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":user_id", $userId);
        $stmt->bindParam(":book_id", $bookId);
        $stmt->bindParam(":quantity", $quantity);
        $stmt->bindParam(":return_date_due", $returnDateDue);
        
        return $stmt->execute();
    }

    public function getByUser($userId) {
        $sql = "SELECT br.*, b.title, b.image, b.author 
                FROM borrow_records br
                JOIN books b ON br.book_id = b.id
                WHERE br.user_id = :user_id
                ORDER BY br.borrow_date DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":user_id", $userId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllRecords() {
        $sql = "SELECT br.*, b.title, b.image, u.username, u.email 
                FROM borrow_records br
                JOIN books b ON br.book_id = b.id
                JOIN users u ON br.user_id = u.id
                ORDER BY br.borrow_date DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $sql = "SELECT * FROM borrow_records WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateStatus($id, $status) {
        $sql = "UPDATE borrow_records SET status = :status, return_date_actual = CURRENT_DATE WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":status", $status);
        $stmt->bindParam(":id", $id);
        return $stmt->execute();
    }
}

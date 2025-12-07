<?php
require_once __DIR__ . "/../config/database.php";

class Book {
    private $conn;
    private $table = "books";

    public function __construct() {
        $db = new Database();
        $this->conn = $db->connect();
    }

    //lấy tất
    public function getAll() {
        $query = "SELECT * FROM " . $this->table . " ORDER BY id ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt;
    }

    //tạo
    public function create($title, $category, $author, $year, $price, $image, $quantity = 0) {
    $sql = "INSERT INTO books (title, category, author, publish_year, price, image, quantity) 
            VALUES (:title, :category, :author, :year, :price, :image, :quantity)";
    $stmt = $this->conn->prepare($sql);

    $stmt->bindParam(":title", $title);
    $stmt->bindParam(":category", $category);
    $stmt->bindParam(":author", $author);
    $stmt->bindParam(":year", $year);
    $stmt->bindParam(":price", $price);
    $stmt->bindParam(":image", $image);
    $stmt->bindParam(":quantity", $quantity);

    return $stmt->execute();   
    }

    public function getById($id) {
    $sql = "SELECT * FROM books WHERE id = :id";
    $stmt = $this->conn->prepare($sql);
    $stmt->bindParam(":id", $id);
    $stmt->execute();
    return $stmt;
    }

    public function update($id, $title, $category, $author, $year, $price, $image=null, $quantity=0) {
    if($image) {
        $sql = "UPDATE books SET title=:title, category=:category, author=:author, publish_year=:year, price=:price, image=:image, quantity=:quantity WHERE id=:id";
    } else {
        $sql = "UPDATE books SET title=:title, category=:category, author=:author, publish_year=:year, price=:price, quantity=:quantity WHERE id=:id";
    }
    
    $stmt = $this->conn->prepare($sql);

    $stmt->bindParam(":title", $title);
    $stmt->bindParam(":category", $category);
    $stmt->bindParam(":author", $author);
    $stmt->bindParam(":year", $year);
    $stmt->bindParam(":price", $price);
    $stmt->bindParam(":quantity", $quantity);
    $stmt->bindParam(":id", $id);

    if($image) {
        $stmt->bindParam(":image", $image);
    }

    return $stmt->execute();
    }

    public function delete($id) {
    $sql = "DELETE FROM books WHERE id = ?";
    $stmt = $this->conn->prepare($sql);
    $stmt->execute([$id]);

    // Reset lại AUTO_INCREMENT
    $sql2 = "ALTER TABLE books AUTO_INCREMENT = 1";
    $this->conn->exec($sql2);

    return true;
    }

    public function getPaginated($limit, $offset) {
    $sql = "SELECT * FROM books ORDER BY id ASC LIMIT :limit OFFSET :offset";
    $stmt = $this->conn->prepare($sql);
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt;
    }

    public function getCount() {
        $sql = "SELECT COUNT(*) AS total FROM books";
        $stmt = $this->conn->query($sql);
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }
}

<?php 
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include "includes/header.php"; 
include "includes/navbar.php"; 

require_once "config/database.php";
$db = new Database();
$conn = $db->connect();

// Lọc theo category nếu có tham số category trong URL
$category = $_GET['category'] ?? null;

if ($category) {
    $sql = "SELECT * FROM books WHERE category = :category ORDER BY id ASC"; 
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(":category", $category);
    $stmt->execute();
    $books = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    $sql = "SELECT * FROM books ORDER BY id ASC"; 
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $books = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<h2 style="text-align:center; margin-top:30px; color:#333;">THƯ VIỆN</h2>

<style>
body {
    background: #f5f5f5;
    font-family: Arial, sans-serif;
}

.book-container {
    display: flex;
    flex-wrap: wrap;
    gap: 25px;
    justify-content: center;
    margin: 40px auto;
    max-width: 1200px;
    padding: 0 20px;
}

.book-card {
    width: 220px;
    background: #fff;
    border-radius: 12px;
    padding: 15px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    text-align: center;
    transition: all 0.3s ease;
    cursor: pointer;
    display: block;
}

.book-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.2);
}

.book-image-wrapper {
    width: 100%;
    height: 280px;
    overflow: hidden;
    border-radius: 10px;
    background: #f0f0f0;
    margin-bottom: 15px;
}

.book-card img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.book-card:hover img {
    transform: scale(1.05);
}

.book-title {
    font-weight: bold;
    font-size: 16px;
    margin: 10px 0;
    color: #333;
    min-height: 40px;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.book-author {
    font-size: 13px;
    color: #666;
    margin-bottom: 8px;
}

.book-price {
    color: #e60000;
    margin-top: 10px;
    font-weight: bold;
    font-size: 18px;
}

.no-books {
    text-align: center;
    padding: 50px;
    color: #999;
    font-size: 18px;
}
</style>

<div class="book-container">
    <?php if (count($books) > 0): ?>
        <?php foreach ($books as $book): ?>
            <a href="views/books/detail.php?id=<?php echo $book['id']; ?>" style="text-decoration: none; color: inherit;">
                <div class="book-card">
                    <div class="book-image-wrapper">
                        <?php if (!empty($book['image'])): ?>
                            <img src="assets/uploads/<?php echo htmlspecialchars($book['image']); ?>" alt="<?php echo htmlspecialchars($book['title']); ?>">
                        <?php else: ?>
                            <img src="https://via.placeholder.com/220x280/cccccc/666666?text=No+Image" alt="No image">
                        <?php endif; ?>
                    </div>

                    <div class="book-title">
                        <?php echo htmlspecialchars($book['title']); ?>
                    </div>

                    <div class="book-author">
                        <?php echo htmlspecialchars($book['author']); ?>
                    </div>

                    <div class="book-price">
                        <?php echo number_format($book['price'], 0, ',', '.'); ?> đ
                    </div>
                </div>
            </a>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="no-books">
            📖 Chưa có sách nào trong thư viện
        </div>
    <?php endif; ?>
</div>

<?php include "includes/footer.php"; ?>
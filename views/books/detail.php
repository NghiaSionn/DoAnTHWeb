<?php 
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include "../../includes/header.php";
include "../../includes/navbar.php";
require_once "../../models/Book.php";

$bookModel = new Book();

$id = $_GET['id'] ?? null;

if (!$id) {
    header("Location: /DoAnTHWeb/index.php");
    exit();
}

// Lấy dữ liệu sách
$stmt = $bookModel->getById($id);
$book = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$book) {
    echo "Không tìm thấy sách!";
    exit();
}

// Kiểm tra xem sách đã được thêm vào kho chưa
require_once "../../models/Library.php";
$library = new Library();
$isInLibrary = false;
if (isset($_SESSION["user_id"])) {
    $isInLibrary = $library->isInLibrary($_SESSION["user_id"], $book['id']);
}
?>

<style>
body {
    background: #f5f5f5;
    font-family: Arial, sans-serif;
}

.book-detail-container {
    max-width: 1200px;
    margin: 40px auto;
    padding: 0 20px;
}

.book-detail-wrapper {
    background: white;
    border-radius: 12px;
    padding: 30px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    display: flex;
    gap: 40px;
}

.book-image-section {
    position: relative;
    flex-shrink: 0;
}

.back-button {
    position: absolute;
    top: -50px;
    left: 0;
    background: rgba(0,0,0,0.6);
    color: white;
    padding: 10px 20px;
    border-radius: 5px;
    text-decoration: none;
    font-size: 14px;
    z-index: 10;
    transition: background 0.3s;
}

.back-button:hover {
    background: rgba(0,0,0,0.8);
}

.book-image-wrapper {
    width: 350px;
    height: 500px;
    overflow: hidden;
    border-radius: 10px;
    background: #f0f0f0;
}

.book-image-wrapper img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.book-info-section {
    flex: 1;
}

.book-title {
    font-size: 32px;
    font-weight: bold;
    color: #333;
    margin-bottom: 15px;
}

.book-info-item {
    margin-bottom: 15px;
    font-size: 16px;
}

.book-info-label {
    font-weight: bold;
    color: #666;
    display: inline-block;
    width: 120px;
}

.book-info-value {
    color: #333;
}

.book-price {
    font-size: 28px;
    color: #e60000;
    font-weight: bold;
    margin: 20px 0;
}

.book-quantity {
    font-size: 18px;
    color: #333;
    margin: 15px 0;
}

.book-quantity .quantity-value {
    font-weight: bold;
    color: #28a745;
}

.book-buttons {
    margin-top: 30px;
    display: flex;
    gap: 15px;
}

.btn-borrow {
    background: #28a745;
    color: white;
    padding: 15px 40px;
    border: none;
    border-radius: 5px;
    font-size: 16px;
    font-weight: bold;
    cursor: pointer;
    text-decoration: none;
    display: inline-block;
    transition: background 0.3s;
}

.btn-borrow:hover {
    background: #218838;
}

.btn-add-to-library {
    background: #e9ecef;
    color: #333;
    padding: 15px 40px;
    border: 1px solid #ced4da;
    border-radius: 5px;
    font-size: 16px;
    font-weight: bold;
    cursor: pointer;
    text-decoration: none;
    display: inline-block;
    transition: background 0.3s;
}

.btn-add-to-library:hover {
    background: #dee2e6;
}
</style>

<div class="book-detail-container">
    <div class="book-detail-wrapper">
        <div class="book-image-section">
            <a href="/DoAnTHWeb/index.php" class="back-button">← Trở về</a>
            <div class="book-image-wrapper">
                <?php if (!empty($book['image'])): ?>
                    <img src="../../assets/uploads/<?php echo htmlspecialchars($book['image']); ?>" alt="<?php echo htmlspecialchars($book['title']); ?>">
                <?php else: ?>
                    <img src="https://via.placeholder.com/350x500/cccccc/666666?text=No+Image" alt="No image">
                <?php endif; ?>
            </div>
        </div>

        <div class="book-info-section">
            <h1 class="book-title"><?php echo htmlspecialchars($book['title']); ?></h1>

            <div class="book-info-item">
                <span class="book-info-label">Tác giả:</span>
                <span class="book-info-value"><?php echo htmlspecialchars($book['author'] ?? 'Chưa có thông tin'); ?></span>
            </div>

            <div class="book-info-item">
                <span class="book-info-label">Thể loại:</span>
                <span class="book-info-value"><?php echo htmlspecialchars($book['category'] ?? 'Chưa có thông tin'); ?></span>
            </div>

            <div class="book-info-item">
                <span class="book-info-label">Năm xuất bản:</span>
                <span class="book-info-value"><?php echo $book['publish_year'] ?? 'Chưa có thông tin'; ?></span>
            </div>

            <div class="book-price">
                <?php echo number_format($book['price'], 0, ',', '.'); ?> đ
            </div>

            <div class="book-quantity">
                Số lượng còn lại: <span class="quantity-value"><?php echo isset($book['quantity']) ? $book['quantity'] : 0; ?></span>
            </div>

            <div class="book-buttons">
                <a href="#" class="btn-borrow">Mượn</a>
                <?php if (isset($_SESSION["user_id"])): ?>
                    <?php if ($isInLibrary): ?>
                        <span style="padding: 15px 40px; background: #d4edda; color: #155724; border: 1px solid #c3e6cb; border-radius: 5px; font-size: 16px; font-weight: bold; display: inline-block;">Đã có trong kho sách</span>
                    <?php else: ?>
                        <a href="../../controllers/LibraryController.php?action=add&book_id=<?php echo $book['id']; ?>" class="btn-add-to-library">Thêm vào kho sách</a>
                    <?php endif; ?>
                <?php else: ?>
                    <a href="/DoAnTHWeb/views/auth/login.php" class="btn-add-to-library">Thêm vào kho sách</a>
                <?php endif; ?>
            </div>
            
            <?php if (isset($_GET['added']) && $_GET['added'] == '1'): ?>
                <div style="margin-top: 20px; padding: 15px; background: #d4edda; color: #155724; border: 1px solid #c3e6cb; border-radius: 5px;">
                    ✓ Đã thêm sách vào kho sách thành công!
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include "../../includes/footer.php"; ?>


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
    header("Location: list.php");
    exit();
}

// Lấy dữ liệu sách
$stmt = $bookModel->getById($id);
$book = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$book) {
    echo "Không tìm thấy sách!";
    exit();
}
?>

<h2 style="text-align:center; margin-top:20px;">Sửa thông tin sách</h2>

<div style="width:50%; margin:0 auto;">

<form action="../../controllers/BookController.php?action=update&id=<?= $id ?>" method="POST" enctype="multipart/form-data">

    <label>Tiêu đề sách:</label>
    <input type="text" name="title" required class="input" value="<?= htmlspecialchars($book['title']) ?>"><br><br>

    <label>Tác giả:</label>
    <input type="text" name="author" class="input" value="<?= htmlspecialchars($book['author']) ?>"><br><br>

    <label>Thể loại:</label>
    <input type="text" name="category" class="input" value="<?= htmlspecialchars($book['category']) ?>"><br><br>

    <label>Năm xuất bản:</label>
    <input type="number" name="publish_year" class="input" value="<?= $book['publish_year'] ?>"><br><br>

    <label>Giá:</label>
    <input type="number" name="price" class="input" value="<?= $book['price'] ?>"><br><br>

    <label>Số lượng:</label>
    <input type="number" name="quantity" class="input" value="<?= isset($book['quantity']) ? $book['quantity'] : 0 ?>" min="0"><br><br>

    <label>Ảnh bìa:</label>
    <?php if(!empty($book['image'])): ?>
        <img src="../../assets/uploads/<?= $book['image'] ?>" width="80"><br>
    <?php endif; ?>
    <input type="file" name="image"><br><br>

    <button type="submit" style="padding:10px 20px; background:blue; color:white;">Cập nhật</button>
</form>

</div>

<?php include "../../includes/footer.php"; ?>

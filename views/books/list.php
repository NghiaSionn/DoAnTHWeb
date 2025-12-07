<?php 
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include "../../includes/header.php";
include "../../includes/navbar.php";
require_once "../../models/Book.php";

$bookModel = new Book();

// PHÂN TRANG
$limit = 6;
$page = isset($_GET["page"]) ? (int)$_GET["page"] : 1;
if ($page < 1) $page = 1;

$offset = ($page - 1) * $limit;

$books = $bookModel->getPaginated($limit, $offset);
$totalBooks = $bookModel->getCount();
$totalPages = ceil($totalBooks / $limit);
?>

<h2 style="text-align:center; margin-top:20px;">Danh sách sách</h2>

<div style="text-align:right; margin:20px;">
    <a href="add.php" style="padding:8px 15px; background:green; color:white; text-decoration:none;">+ Thêm sách</a>
</div>

<table border="1" cellpadding="10" cellspacing="0" width="100%">
    <tr style="background:#eee;">
        <th>ID</th>
        <th>Tiêu đề</th>
        <th>Thể Loại</th>
        <th>Tác giả</th>
        <th>Năm XB</th>
        <th>Giá</th>
        <th>Số lượng</th>
        <th>Ảnh</th>
        <th>Hành động</th>
    </tr>

    <?php while($row = $books->fetch(PDO::FETCH_ASSOC)) { ?>
    <tr>
        <td><?= $row["id"]; ?></td>
        <td><?= $row["title"]; ?></td>
        <td><?= $row["category"]; ?></td>
        <td><?= $row["author"]; ?></td>
        <td><?= $row["publish_year"]; ?></td>
        <td><?= number_format($row["price"], 0, ',', '.') ?>đ</td>
        <td><?= isset($row["quantity"]) ? $row["quantity"] : 0; ?></td>
        <td>
            <?php if (!empty($row["image"])) { ?>
                <img src="../../assets/uploads/<?= $row["image"]; ?>" width="60">
            <?php } else { echo "Không có"; } ?>
        </td>
        <td>
            <a href="edit.php?id=<?= $row['id']; ?>" style="color:blue;">Sửa</a> | 
            <a href="../../controllers/BookController.php?action=delete&id=<?= $row['id'] ?>"
               onclick="return confirm('Bạn có chắc muốn xóa sách này?')">
               Xóa
            </a>
        </td>
    </tr>
    <?php } ?>
</table>

<!-- PHÂN TRANG -->
<div style="text-align:center; margin-top:20px;">

    <?php if ($page > 1): ?>
        <a href="?page=<?= $page - 1 ?>" 
           style="padding:8px 12px; background:#444; color:white; text-decoration:none; margin-right:10px;">
           ← Trang trước
        </a>
    <?php endif; ?>

    <span style="padding:8px 12px; border:1px solid #ccc;">
        Trang <?= $page ?> / <?= $totalPages ?>
    </span>

    <?php if ($page < $totalPages): ?>
        <a href="?page=<?= $page + 1 ?>" 
           style="padding:8px 12px; background:#444; color:white; text-decoration:none; margin-left:10px;">
           Trang sau →
        </a>
    <?php endif; ?>

</div>

<?php include "../../includes/footer.php"; ?>

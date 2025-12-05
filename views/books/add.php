<?php 
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include "../../includes/header.php";
include "../../includes/navbar.php";


?>

<h2 style="text-align:center; margin-top:20px;">Thêm sách mới</h2>

<div style="width:50%; margin:0 auto;">

<form action="../../controllers/BookController.php?action=store" method="POST" enctype="multipart/form-data">

    <label>Tiêu đề sách:</label>
    <input type="text" name="title" required class="input"><br><br>

    <label>Thể loại:</label>
    <input type="text" name="category" class="input"><br><br>

    <label>Tác giả:</label>
    <input type="text" name="author" class="input"><br><br>

    <label>Năm xuất bản:</label>
    <input type="number" name="publish_year" class="input"><br><br>

    <label>Giá:</label>
    <input type="number" name="price" class="input"><br><br>

    <label>Ảnh bìa:</label>
    <input type="file" name="image"><br><br>

    <button type="submit" style="padding:10px 20px; background:green; color:white;">Lưu lại</button>
</form>

</div>

<?php include "../../includes/footer.php"; ?>

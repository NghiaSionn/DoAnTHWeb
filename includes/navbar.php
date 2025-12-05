<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

<nav style="background:#333; padding:10px; color:white; display:flex; gap:20px; align-items:center;">
    <a href="/DoAnTHWeb/index.php" style="color:white;">Trang chủ</a>

    <?php if (isset($_SESSION["role"]) && $_SESSION["role"] === "admin"): ?>

        <div class="admin-menu" id="adminMenuBtn">
            <span class="admin-title">Quản lý ▼</span>

            <div class="admin-dropdown" id="adminDropdown">
                <a href="/DoAnTHWeb/views/books/list.php">Quản lý sách</a>
                <a href="/DoAnTHWeb/views/books/history.php">Lịch sử mượn trả</a>
            </div>
        </div>

    <?php endif; ?>
</nav>

<style>
.admin-menu {
    position: relative;
    cursor: pointer;
    color: white;
}

.admin-title {
    padding: 8px 10px;
    border-radius: 5px;
}

.admin-dropdown {
    display: none;
    position: absolute;
    top: 40px;
    left: 0;
    background: white;
    width: 180px;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.2);
    overflow: hidden;
    z-index: 999;
}

.admin-dropdown a {
    display: block;
    padding: 10px 12px;
    text-decoration: none;
    color: black;
}

.admin-dropdown a:hover {
    background: #eee;
}
</style>


<script>
document.addEventListener('DOMContentLoaded', function () {
    const adminBtn = document.getElementById("adminMenuBtn");
    const adminMenu = document.getElementById("adminDropdown");

    adminBtn.addEventListener("click", function (event) {
        event.stopPropagation(); 
        adminMenu.style.display = 
            adminMenu.style.display === "block" ? "none" : "block";
    });

    document.addEventListener("click", function(event) {
        if (!adminBtn.contains(event.target)) {
            adminMenu.style.display = "none";
        }
    });
});
</script>


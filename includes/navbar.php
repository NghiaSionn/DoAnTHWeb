<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . "/../config/database.php";
$db = new Database();
$conn = $db->connect();

// Lấy danh sách các category duy nhất từ database
$categorySql = "SELECT DISTINCT category FROM books ORDER BY category ASC";
$categoryStmt = $conn->prepare($categorySql);
$categoryStmt->execute();
$categories = $categoryStmt->fetchAll(PDO::FETCH_COLUMN);
?>

<nav style="background:#333; padding:8px 15px; color:white; display:flex; gap:15px; align-items:center;">
    <a href="/DoAnTHWeb/index.php" style="color:white;">Trang chủ</a>

    <div class="category-menu" id="categoryMenuBtn">
        <span class="category-title">Thể loại ▼</span>
        <div class="category-dropdown" id="categoryDropdown">
            <a href="/DoAnTHWeb/index.php">Tất cả</a>
            <?php foreach ($categories as $category): ?>
                <a href="/DoAnTHWeb/index.php?category=<?php echo urlencode($category); ?>">
                    <?php echo htmlspecialchars($category); ?>
                </a>
            <?php endforeach; ?>
        </div>
    </div>

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

.category-menu {
    position: relative;
    cursor: pointer;
    color: white;
}

.category-title {
    padding: 8px 10px;
    border-radius: 5px;
}

.category-dropdown {
    display: none;
    position: absolute;
    top: 40px;
    left: 0;
    background: white;
    width: 200px;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.2);
    overflow: hidden;
    z-index: 999;
}

.category-dropdown a {
    display: block;
    padding: 10px 12px;
    text-decoration: none;
    color: black;
}

.category-dropdown a:hover {
    background: #eee;
}
</style>


<script>
document.addEventListener('DOMContentLoaded', function () {
    const adminBtn = document.getElementById("adminMenuBtn");
    const adminMenu = document.getElementById("adminDropdown");

    if (adminBtn && adminMenu) {
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
    }

    // Category dropdown toggle
    const categoryBtn = document.getElementById("categoryMenuBtn");
    const categoryMenu = document.getElementById("categoryDropdown");

    if (categoryBtn && categoryMenu) {
        categoryBtn.addEventListener("click", function (event) {
            event.stopPropagation(); 
            categoryMenu.style.display = 
                categoryMenu.style.display === "block" ? "none" : "block";
        });

        document.addEventListener("click", function(event) {
            if (!categoryBtn.contains(event.target)) {
                categoryMenu.style.display = "none";
            }
        });
    }
});
</script>


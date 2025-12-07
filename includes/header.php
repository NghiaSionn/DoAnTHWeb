<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý</title>
</head>
<body>

<style>
.header-user {
    position: relative;
}

.header-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    cursor: pointer;
    border: 2px solid #fff;
    object-fit: cover;
}

.header-menu {
    display: none;
    position: absolute;
    right: 10px;
    top: 50px;
    background: white;
    border: 1px solid #ccc;
    padding: 10px 0;
    width: 150px;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    z-index: 1000;
}

.header-menu a {
    display: block;
    padding: 8px 15px;
    color: black;
    text-decoration: none;
}

.header-menu a:hover {
    background: #eee;
}

.username-display {
    padding: 8px 15px;
    font-weight: bold;
    border-bottom: 1px solid #eee;
    color: #333;
}

</style>

<header style="background:#333; color:white; padding:8px 15px;">
    <div style="max-width: 2100px; margin: 0 auto; display: flex; justify-content: flex-end; align-items: center; gap: 12px;">
               
        <?php if (isset($_SESSION["user_id"])): ?>
            <a href="/DoAnTHWeb/views/books/library.php" style="color: white; text-decoration: none; padding: 6px 12px; border: 1px solid white; border-radius: 5px; display: inline-block; font-size: 14px;">Kho sách</a>
            <div class="header-user" onclick="toggleMenu()">
                <img src="/DoAnTHWeb/assets/img/default_avatar.jpg" class="header-avatar" alt="Avatar">               
                <div class="header-menu" id="userMenu">
                    <div class="username-display">
                        <?php echo htmlspecialchars($_SESSION["username"]); ?>
                    </div>
                    <a href="/DoAnTHWeb/views/auth/logout.php">Đăng xuất</a>
                </div>
            </div>
        <?php else: ?>
            <div class="header-user">
                <a href="/DoAnTHWeb/views/auth/login.php" style="color: white; text-decoration: none; padding: 6px 12px; border: 1px solid white; border-radius: 5px; display: inline-block; font-size: 14px;">Đăng nhập</a>
            </div>
        <?php endif; ?>
    </div>
</header>

<script>
function toggleMenu() {
    let menu = document.getElementById("userMenu");
    menu.style.display = menu.style.display === "block" ? "none" : "block";
}

document.addEventListener('click', function(event) {
    let menu = document.getElementById("userMenu");
    let userDiv = document.querySelector(".header-user");
    
    if (menu && userDiv && !userDiv.contains(event.target)) {
        menu.style.display = "none";
    }
});

</script>
<form action="../../controllers/AuthController.php?action=login" method="POST">
    <h2>Đăng nhập</h2>

    <input type="text" name="username" placeholder="Tên đăng nhập" required>
    <input type="password" name="password" placeholder="Mật khẩu" required>

    <button type="submit">Đăng nhập</button>
</form>

<p>
    Chưa có tài khoản? 
    <a href="register.php">Đăng ký</a>
</p>

<form action="../../controllers/AuthController.php?action=register" method="POST">
    <h2>Đăng ký</h2>

    <input type="text" name="username" placeholder="Tên đăng nhập" required>
    <input type="password" name="password" placeholder="Mật khẩu" required>

    <select name="role">
        <option value="user">User</option>
        <option value="admin">Admin</option>
    </select>

    <button type="submit">Đăng ký</button>
</form>

<p>
    Đã có tài khoản?
    <a href="login.php">Đăng nhập</a>
</p>

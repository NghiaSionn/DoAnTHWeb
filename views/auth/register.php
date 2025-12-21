<link rel="stylesheet" href="../../assets/css/auth.css">

<div class="auth-container">
    <div class="auth-card">
        <h2 class="auth-title">Đăng Ký Tài Khoản</h2>
        
        <form action="../../controllers/AuthController.php?action=register" method="POST">
            <div class="form-group">
                <label>Tên đăng nhập</label>
                <input type="text" name="username" placeholder="Chọn tên đăng nhập" required>
            </div>
            
            <div class="form-group">
                <label>Mật khẩu</label>
                <input type="password" name="password" placeholder="Chọn mật khẩu bảo mật" required>
            </div>

            <div class="form-group">
                <label>Vai trò</label>
                <select name="role" class="auth-select">
                    <option value="user">Người dùng (User)</option>
                    <option value="admin">Quản trị viên (Admin)</option>
                </select>
            </div>

            <button type="submit" class="auth-btn">Đăng ký</button>
        </form>

        <p class="auth-link">
            Đã có tài khoản? <a href="login.php">Đăng nhập ngay</a>
        </p>
    </div>
</div>



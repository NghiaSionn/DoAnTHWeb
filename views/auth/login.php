
<link rel="stylesheet" href="../../assets/css/auth.css">

<div class="auth-container">
    <div class="auth-card">
        <h2 class="auth-title">Đăng Nhập</h2>
        
        <form action="../../controllers/AuthController.php?action=login" method="POST">
            <div class="form-group">
                <label>Tên đăng nhập</label>
                <input type="text" name="username" placeholder="Nhập tên đăng nhập" required>
            </div>
            
            <div class="form-group">
                <label>Mật khẩu</label>
                <input type="password" name="password" placeholder="Nhập mật khẩu" required>
            </div>

            <button type="submit" class="auth-btn">Đăng nhập</button>
        </form>

        <p class="auth-link">
            Chưa có tài khoản? <a href="register.php">Đăng ký ngay</a>
        </p>
    </div>
</div>



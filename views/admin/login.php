<!DOCTYPE html>
<html lang="vi">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Đăng nhập Admin</title>
  <link rel="stylesheet" href="views/shared/login.css">
</head>

<body>

  <div class="login-wrap">
    <div class="login-card">
      <div class="brand">
        <div class="logo">AD</div>
        <div>
          <h1>Đăng nhập quản trị</h1>
          <p class="lead">Quản trị hệ thống</p>
        </div>
      </div>

      <?php if (isset($_SESSION['error'])): ?>
        <div class="error"><?php echo htmlspecialchars($_SESSION['error']);
                            unset($_SESSION['error']); ?></div>
      <?php endif; ?>

      <form action="admin.php?act=login" method="POST">
        <div class="form-group">
          <label for="email">Email</label>
          <input id="email" type="email" name="email" placeholder="Email" required>
        </div>
        <div class="form-group">
          <label for="password">Mật khẩu</label>
          <input id="password" type="password" name="password" placeholder="Mật khẩu" required>
        </div>

        <div class="actions">
          <button type="submit" class="btn">Đăng nhập</button>
          <a href="/" class="btn secondary">Hủy</a>
        </div>
      </form>

      <footer>
        <div>&copy; 2025 Công Ty Quản Lý Tour Du Lịch.</div>
      </footer>
    </div>
  </div>

</body>

</html>
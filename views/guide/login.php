<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập Hướng dẫn viên</title>
    <link rel="stylesheet" href="views/shared/login.css">
</head>

<body>

    <div class="login-wrap">
        <div class="login-card">
            <div class="brand">
                <div class="logo">HD</div>
                <div>
                    <h1>Đăng nhập Hướng dẫn viên</h1>
                    <p class="lead">Quản lý lịch, điểm danh</p>
                </div>
            </div>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="error"><?= htmlspecialchars($_SESSION['error']);
                                    unset($_SESSION['error']); ?></div>
            <?php endif; ?>

            <form method="POST" action="index.php?act=login">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input id="email" type="email" name="email" placeholder="vd. you@example.com" required>
                </div>

                <div class="form-group">
                    <label for="password">Mật khẩu</label>
                    <input id="password" type="password" name="password" placeholder="Nhập mật khẩu" required>
                </div>

                <div class="actions">
                    <button type="submit" class="btn">Đăng nhập</button>
                </div>
            </form>

            <footer>
                <div style="margin-top:10px;">
                    <a href="/" style="color:#2563eb; text-decoration:none">Về trang chủ</a>
                </div>
            </footer>
        </div>
    </div>

</body>

</html>
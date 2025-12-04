<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Đăng nhập Hướng dẫn viên</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        :root{ --bg1:#0ea5a0; --bg2:#06b6d4; --card:#ffffff; --accent:#0ea5ff }
        *{box-sizing:border-box}
        body{margin:0;font-family:'Inter',system-ui,-apple-system,Segoe UI,Roboto,'Helvetica Neue',Arial;background:linear-gradient(135deg,var(--bg1),var(--bg2));min-height:100vh;display:flex;align-items:center;justify-content:center;padding:24px}
        .card{width:100%;max-width:420px;background:var(--card);border-radius:12px;box-shadow:0 10px 30px rgba(2,6,23,0.2);padding:28px}
        .brand{display:flex;align-items:center;gap:12px;margin-bottom:18px}
        .logo{width:56px;height:56px;border-radius:12px;background:linear-gradient(135deg,#2563eb,#0ea5ff);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:20px}
        h1{font-size:20px;margin:0;color:#0f172a}
        p.lead{margin:6px 0 18px;color:#475569;font-size:14px}
        .form-group{margin-bottom:12px}
        label{display:block;font-size:13px;color:#334155;margin-bottom:6px}
        input[type="email"],input[type="password"]{width:100%;padding:12px 14px;border:1px solid #e6eef6;border-radius:8px;font-size:14px;color:#0f172a}
        .actions{display:flex;gap:10px;align-items:center;margin-top:12px}
        .btn{flex:1;padding:10px 14px;border-radius:8px;border:0;background:linear-gradient(90deg,#0ea5ff,#2563eb);color:#fff;font-weight:600;cursor:pointer}
        .btn.secondary{background:#eef2ff;color:#1e293b}
        .note{font-size:13px;color:#94a3b8;margin-top:10px;text-align:center}
        .error{background:#fee2e2;color:#991b1b;padding:10px;border-radius:8px;margin-bottom:12px;text-align:center}
        .footer-links{display:flex;justify-content:space-between;margin-top:12px;font-size:13px}
        .footer-links a{color:#2563eb;text-decoration:none}
        @media (max-width:420px){.card{padding:20px}}
    </style>
</head>
<body>

<div class="card">
    <div class="brand">
        <div class="logo">HD</div>
        <div>
            <h1>Đăng nhập Hướng dẫn viên</h1>
            <p class="lead">Quản lý lịch, điểm danh</p>
        </div>
    </div>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="error"><?= htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?></div>
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
            <a href="index.php" class="btn secondary">Hủy</a>
        </div>

        <div class="footer-links">
            <a href="#">Quên mật khẩu?</a>
            <a href="/">Về trang chủ</a>
        </div>
        <p class="note">Chú ý: Sử dụng tài khoản hướng dẫn viên để đăng nhập.</p>
    </form>
</div>

</body>
</html>

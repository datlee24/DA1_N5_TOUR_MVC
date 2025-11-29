<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng nhập Hướng dẫn viên</title>

    <style>
        body {
            margin: 0;
            background: linear-gradient(135deg, #3b82f6, #06b6d4);
            font-family: Arial;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .login-box {
            width: 360px;
            background: #fff;
            padding: 28px;
            border-radius: 10px;
            box-shadow: 0 5px 18px rgba(0,0,0,0.2);
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #1e3a8a;
        }

        input {
            width: 100%;
            padding: 12px;
            margin-bottom: 14px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }

        button {
            width: 100%;
            padding: 12px;
            background: #2563eb;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
            transition: 0.2s;
        }

        button:hover {
            background: #1e40af;
        }

        .error {
            text-align: center;
            color: red;
            margin-bottom: 12px;
        }
    </style>
</head>
<body>

<div class="login-box">
    <h2>Đăng nhập Hướng Dẫn Viên</h2>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="error"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>

    <form method="POST" action="index.php?act=login">
        <input type="email" name="email" placeholder="Nhập email" required>
        <input type="password" name="password" placeholder="Nhập mật khẩu" required>
        <button type="submit">Đăng nhập</button>
    </form>
</div>

</body>
</html>

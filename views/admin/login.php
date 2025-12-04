<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Nhập</title>
    <Style>
        * {
      box-sizing: border-box;
    }

    body {
      margin: 0;
      font-family: Arial, sans-serif;
      background: #f2f2f2;
      display: flex;
      flex-direction: column;
      min-height: 100vh;
    }

    header {
      background-color: #fff;
      padding: 10px 20px;
      display: flex;
      align-items: center;
      box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }

    header img {
      height: 40px;
      margin-right: 10px;
    }

    header h1 {
      font-size: 20px;
      margin: 0;
    }

    main {
      flex: 1;
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 20px;
    }

    .login-form {
      background: white;
      padding: 30px;
      border-radius: 8px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
      width: 300px;
    }

    .login-form h2 {
      margin-bottom: 20px;
      text-align: center;
    }

    .login-form input[type="email"],
    .login-form input[type="password"] {
      width: 100%;
      padding: 10px;
      margin: 8px 0 16px;
      border: 1px solid #ccc;
      border-radius: 4px;
    }

    .login-form button {
      width: 100%;
      padding: 10px;
      background: #4285f4;
      color: white;
      border: none;
      border-radius: 4px;
      font-size: 16px;
    }

    .login-form button:hover {
      background: #3367d6;
    }

    footer {
      background-color: #fff;
      text-align: center;
      padding: 15px;
      font-size: 14px;
      color: #666;
      border-top: 1px solid #e0e0e0;
    }
  </Style>
</head>
<body>
    <header>
        <h1>Hệ Thống ADMIN</h1>
    </header>   
    <main>
    <form class="login-form" action="admin.php?act=login" method="POST">
      <h2>Đăng nhập</h2>
        <?php if (isset($_SESSION['error'])): ?>
            <div style="color: red; text-align: center; margin-bottom: 10px;">
            <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>
      <input type="email" name="email" placeholder="Email">
      <input type="password" name="password" placeholder="Mật khẩu">
      <button type="submit">Đăng nhập</button>
    </form>
  </main>
  <footer>
    &copy; 2025 Công Ty Quản Lý Tour Du Lịch.
  </footer>
</body>
</html>
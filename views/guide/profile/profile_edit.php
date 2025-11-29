<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Chỉnh sửa hồ sơ</title>

    <style>
        body {
            margin: 0;
            background: #f2f4f8;
            font-family: Arial;
            padding: 20px;
        }

        .profile-box {
            width: 450px;
            margin: auto;
            background: #fff;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.15);
        }

        h2 { text-align: center; color: #1e3a8a; }
        label { font-weight: bold; margin-bottom: 5px; display: block; }
        input { width: 100%; padding: 12px; margin-bottom: 14px; border-radius: 6px; }
        button { width: 100%; padding: 12px; background: #2563eb; color: white; border: none; }
    </style>
</head>
<body>

<div class="profile-box">

    <h2>Chỉnh sửa hồ sơ</h2>

    <?php if (isset($_SESSION['success'])): ?>
        <div style="color: green; text-align:center;">
            <?= $_SESSION['success']; unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>

    <form action="index.php?act=update-profile" method="POST">
        <label>Họ tên</label>
        <input type="text" name="fullname" required value="<?= $guide['fullname'] ?>">

        <label>Số điện thoại</label>
        <input type="text" name="phone" required value="<?= $guide['phone'] ?>">

        <label>Email</label>
        <input type="email" name="email" required value="<?= $guide['email'] ?>">

        <label>Mật khẩu</label>
        <input type="text" name="password" required value="<?= $guide['password'] ?>">

        <button type="submit">Lưu thay đổi</button>
    </form>
</div>

</body>
</html>

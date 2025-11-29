<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Hồ sơ Hướng Dẫn Viên</title>

    <link rel="stylesheet" 
          href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">

    <style>
        body { background: #f4f6f9; font-family: Arial; }
        .profile-box {
            width: 70%;
            margin: 25px auto;
            background: #fff;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 3px 8px rgba(0,0,0,0.1);
        }
        .section-title {
            padding-bottom: 8px;
            margin-bottom: 15px;
            border-bottom: 2px solid #0d6efd;
            font-size: 18px;
            font-weight: bold;
        }
        .info-title { font-weight: bold; color: #555; }
    </style>
</head>

<body>

<div class="profile-box">

    <h3>Hồ sơ chi tiết Hướng Dẫn Viên</h3>

    <!-- THÔNG TIN NGƯỜI DÙNG -->
    <div class="section-title">Thông tin cá nhân</div>
    <p><span class="info-title">Họ tên:</span> <?= $profile['fullname'] ?></p>
    <p><span class="info-title">Số điện thoại:</span> <?= $profile['phone'] ?></p>
    <p><span class="info-title">Email:</span> <?= $profile['email'] ?></p>
    <p><span class="info-title">Vai trò:</span> <?= $profile['role'] ?></p>

    <hr>

    <!-- THÔNG TIN HƯỚNG DẪN VIÊN -->
    <div class="section-title">Thông tin chuyên môn</div>
    <p><span class="info-title">Mã HDV:</span> <?= $profile['guide_id'] ?></p>
    <p><span class="info-title">Ngôn ngữ:</span> <?= $profile['language'] ?></p>
    <p><span class="info-title">Chứng chỉ:</span> <?= $profile['certificate'] ?></p>
    <p><span class="info-title">Kinh nghiệm:</span> <?= $profile['experience'] ?> năm</p>
    <p><span class="info-title">Chuyên môn:</span> <?= $profile['specialization'] ?></p>

    <a href="index.php?act=profile" class="btn btn-primary mt-3">Chỉnh sửa</a>
    <a href="index.php" class="btn btn-secondary mt-3">Quay lại</a>

</div>

</body>
</html>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang chủ Hướng Dẫn Viên</title>
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">

    <style>
        body {
            background: #f4f6f9;
            font-family: Arial;
        }

        /* Sidebar */
        #sidebar {
            width: 240px;
            height: 100vh;
            background: #fff;
            border-right: 1px solid #ddd;
            position: fixed;
            left: 0;
            top: 0;
            padding-top: 20px;
        }

        #sidebar a {
            display: block;
            padding: 12px 18px;
            font-size: 15px;
            color: #333;
            text-decoration: none;
        }

        #sidebar a:hover {
            background: #e7f1ff;
            color: #0d6efd;
        }

        /* Content */
        #content {
            margin-left: 240px;
            padding: 20px;
        }

        .card {
            border-radius: 10px;
            box-shadow: 0 0 6px rgba(0,0,0,0.08);
        }

        .profile-card p {
            margin: 4px 0;
        }
    </style>
</head>

<body>

<?php 
$guide = $_SESSION['guide']; 
?>

<!-- Sidebar -->
<div id="sidebar">
    <h5 class="text-center mb-4">Hướng Dẫn Viên</h5>

    <a href="index.php">Trang chủ</a>
    <a href="#">Lịch làm việc</a>
    <a href="#">Tour của tôi</a>
    <a href="#">Tin nhắn</a>
    <a href="index.php?act=logout" style="color:red;">Đăng xuất</a>
</div>

<!-- Content -->
<div id="content">

    <!-- Navbar -->
    <nav class="navbar navbar-light bg-white shadow-sm mb-4 p-3 rounded">
        <div class="container-fluid">
            <span class="navbar-brand mb-0 h4">Dashboard HDV</span>

            <div class="dropdown">
                <a class="dropdown-toggle" href="#" data-bs-toggle="dropdown">
                    <?= htmlspecialchars($guide['fullname']) ?>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item text-danger" href="index.php?act=logout">Đăng xuất</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Dashboard -->
    <div class="container-fluid">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h3>Xin chào, <?= htmlspecialchars($guide['fullname']) ?></h3>
                <p class="text-muted"><?= date("d/m/Y") ?></p>
            </div>

            <div>
                <a href="#" class="btn btn-primary">Lịch hôm nay</a>
            </div>
        </div>

        <div class="row">

            <div class="col-lg-8">

                <!-- Card: Chuyến trong ngày -->
                <div class="card p-3 mb-3">
                    <h5>Chuyến trong ngày</h5>
                    <p class="text-muted">Bạn hiện không có chuyến nào.</p>
                </div>

                <!-- Card: Thông báo -->
                <div class="card p-3 mb-3">
                    <h5>Thông báo</h5>
                    <p class="text-muted">Không có thông báo mới.</p>
                </div>

                <!-- Card: Khách sắp tới -->
                <div class="card p-3">
                    <h5>Khách sắp tới</h5>
                    <p class="text-muted">Hiện không có khách đặt tour mới.</p>
                </div>

            </div>

            <!-- Profile -->
            <div class="col-lg-4">
                <div class="card p-3 profile-card mb-3">
                    <h5>Hồ sơ cá nhân</h5>

                    <p><strong><?= htmlspecialchars($guide['fullname']) ?></strong></p>
                    <p>SĐT: <?= htmlspecialchars($guide['phone']) ?></p>
                    <p>Email: <?= htmlspecialchars($guide['email']) ?></p>

                    <a href="index.php?act=profile" class="btn btn-outline-primary btn-sm mt-2">
                        Chỉnh sửa hồ sơ
                    </a>
                </div>

                <div class="card p-3">
                    <h5>Tùy chọn nhanh</h5>

                    <a href="#" class="btn btn-success btn-sm mb-2">Bắt đầu chuyến</a>
                    <a href="#" class="btn btn-warning btn-sm mb-2">Báo cáo</a>
                    <a href="#" class="btn btn-secondary btn-sm">Tin nhắn</a>
                </div>
            </div>

        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>

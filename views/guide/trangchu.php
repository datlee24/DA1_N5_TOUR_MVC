<?php $guide = $_SESSION['guide']; ?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Hướng Dẫn Viên</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">

    <style>
        body {
            background: #f4f6f9;
            font-family: "Segoe UI", sans-serif;
        }

        /* Sidebar */
        #sidebar {
            width: 260px;
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            background: #1e293b;
            padding-top: 20px;
            color: #fff;
        }
        #sidebar h4 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 20px;
        }
        #sidebar a {
            display: block;
            padding: 12px 20px;
            font-size: 15px;
            color: #cbd5e1;
            text-decoration: none;
        }
        #sidebar a:hover {
            background: #334155;
            color: #fff;
        }
        #sidebar .active {
            background: #0ea5e9;
            color: #fff;
        }

        /* Content */
        #content {
            margin-left: 260px;
            padding: 20px;
        }

        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0px 3px 10px rgba(0,0,0,0.08);
        }

        .stat-box {
            padding: 20px;
            border-radius: 12px;
            color: #fff;
        }
        .bg-blue { background: #0ea5e9; }
        .bg-green { background: #22c55e; }
        .bg-orange { background: #f97316; }

        .menu-icon {
            margin-right: 8px;
        }
    </style>
</head>

<body>

<!-- SIDEBAR -->
<div id="sidebar">
    <h4>👨‍✈️ HƯỚNG DẪN VIÊN</h4>

    <a href="index.php" class="active">🏠 Dashboard</a>
    <a href="index.php?act=schedule-month">📅 Lịch làm việc</a>
    <a href="index.php?act=my-tours">🧭 Tour của tôi</a>
    <a href="index.php?act=today">⏳ Lịch hôm nay</a>
    <a href="index.php?act=messages">💬 Tin nhắn</a>
    <a href="index.php?act=profile">👤 Hồ sơ cá nhân</a>

    <a href="index.php?act=logout" style="color:#f87171;">🚪 Đăng xuất</a>
</div>

<!-- CONTENT -->
<div id="content">

    <!-- NAVBAR -->
    <nav class="navbar navbar-light bg-white shadow-sm p-3 rounded mb-4">
        <div class="container-fluid">
            <span class="navbar-brand h4">Dashboard</span>

            <div class="dropdown">
                <a class="dropdown-toggle fw-bold" href="#" data-bs-toggle="dropdown">
                    <?= htmlspecialchars($guide['fullname']) ?>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="index.php?act=profile">Hồ sơ</a></li>
                    <li><a class="dropdown-item text-danger" href="index.php?act=logout">Đăng xuất</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- HERO WELCOME -->
    <div class="card p-4 mb-4">
        <h3>Xin chào, <?= htmlspecialchars($guide['fullname']) ?> 👋</h3>
        <p class="text-muted mb-0">Hôm nay: <?= date("d/m/Y") ?></p>
    </div>

    <!-- STATISTICS -->
    <div class="row mb-4">

        <div class="col-lg-4 mb-3">
            <div class="stat-box bg-blue">
                <h4>📅 Lịch hôm nay</h4>
                <p class="mb-1">1 chuyến</p>
                <a href="index.php?act=today" class="btn btn-light btn-sm">Xem chi tiết</a>
            </div>
        </div>

        <div class="col-lg-4 mb-3">
            <div class="stat-box bg-green">
                <h4>🧭 Tour trong tháng</h4>
                <p class="mb-1">3 tour</p>
                <a href="index.php?act=my-tours" class="btn btn-light btn-sm">Xem tour</a>
            </div>
        </div>

        <div class="col-lg-4 mb-3">
            <div class="stat-box bg-orange">
                <h4>👥 Khách trong ngày</h4>
                <p class="mb-1">18 khách</p>
                <a href="index.php?act=today" class="btn btn-light btn-sm">Điểm danh</a>
            </div>
        </div>
    </div>

    <!-- MAIN SECTION -->
    <div class="row">

        <!-- Lịch hôm nay -->
        <div class="col-lg-8">
            <div class="card p-3 mb-4">
                <h5>🚌 Chuyến trong ngày</h5>
                <hr>

                <p class="text-muted">Chưa có dữ liệu (đợi kết nối model)</p>
            </div>

            <!-- Thông báo -->
            <div class="card p-3 mb-4">
                <h5>🔔 Thông báo</h5>
                <hr>

                <p class="text-muted">Không có thông báo mới.</p>
            </div>
        </div>

        <!-- Profile -->
        <div class="col-lg-4">
            <div class="card p-3 mb-4">
                <h5>👤 Hồ sơ cá nhân</h5>
                <hr>

                <p><strong><?= htmlspecialchars($guide['fullname']) ?></strong></p>
                <p>SĐT: <?= htmlspecialchars($guide['phone']) ?></p>
                <p>Email: <?= htmlspecialchars($guide['email']) ?></p>

                <a href="index.php?act=profile" class="btn btn-outline-primary btn-sm mt-2">Cập nhật hồ sơ</a>
            </div>

            <!-- Tùy chọn nhanh -->
            <div class="card p-3">
                <h5>⚡ Tùy chọn nhanh</h5>
                <hr>

                <a href="index.php?act=today" class="btn btn-success btn-sm w-100 mb-2">Điểm danh hôm nay</a>
                <a href="index.php?act=schedule-month" class="btn btn-warning btn-sm w-100 mb-2">Xem lịch tháng</a>
                <a href="index.php?act=my-tours" class="btn btn-info btn-sm w-100">Tour của tôi</a>
            </div>
        </div>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

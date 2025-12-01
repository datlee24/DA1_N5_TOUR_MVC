<?php $guide = $_SESSION['guide']; ?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách khách hàng</title>

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

        .table thead th {
            background: #e5e7eb;
        }
    </style>
</head>

<body>

<!-- SIDEBAR -->
<div id="sidebar">
    <h4>👨‍✈️ HƯỚNG DẪN VIÊN</h4>

    <a href="index.php">🏠 Dashboard</a>
    <a href="index.php?act=schedule-month">📅 Lịch làm việc</a>
    <a href="index.php?act=my-tours">🧭 Tour của tôi</a>
    <a href="index.php?act=today">⏳ Lịch hôm nay</a>
    <a href="index.php?act=customers" class="active">👥 Danh sách khách hàng</a>
    <a href="index.php?act=profile">👤 Hồ sơ cá nhân</a>

    <a href="index.php?act=logout" style="color:#f87171;">🚪 Đăng xuất</a>
</div>

<!-- CONTENT -->
<div id="content">

    <!-- NAVBAR -->
    <nav class="navbar navbar-light bg-white shadow-sm p-3 rounded mb-4">
        <div class="container-fluid">
            <span class="navbar-brand h4">Danh sách khách hàng</span>

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

    <div class="card p-4">
        <h5 class="mb-3">Tất cả khách hàng</h5>

        <?php if (empty($customers)): ?>
            <p class="text-muted">Chưa có khách hàng nào.</p>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Họ tên</th>
                        <th>Giới tính</th>
                        <th>Ngày sinh</th>
                        <th>SĐT</th>
                        <th>Email</th>
                        <th>Ghi chú</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $i = 1; foreach ($customers as $c): ?>
                        <tr>
                            <td><?= $i++ ?></td>
                            <td><?= htmlspecialchars($c['fullname'] ?? '') ?></td>
                            <td><?= htmlspecialchars($c['gender'] ?? '') ?></td>
                            <td><?= !empty($c['birthdate']) ? date('d/m/Y', strtotime($c['birthdate'])) : '' ?></td>
                            <td><?= htmlspecialchars($c['phone'] ?? '') ?></td>
                            <td><?= htmlspecialchars($c['email'] ?? '') ?></td>
                            <td><?= htmlspecialchars($c['notes'] ?? '') ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>



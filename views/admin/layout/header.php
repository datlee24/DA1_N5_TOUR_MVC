<!DOCTYPE html>
<html lang="vi">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard</title>

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
      box-shadow: 0px 3px 10px rgba(0, 0, 0, 0.08);
    }

    .stat-box {
      padding: 20px;
      border-radius: 12px;
      color: #fff;
    }

    .bg-blue {
      background: #0ea5e9;
    }

    .bg-green {
      background: #22c55e;
    }

    .bg-orange {
      background: #f97316;
    }
  </style>

</head>

<body>

  <!-- SIDEBAR -->
  <div id="sidebar">
    <h4>👨‍💼 ADMIN</h4>

    <a href="admin.php?act=dashboard" class="active">🏠 Dashboard</a>
    <a href="admin.php?act=category_list">📂 Danh mục</a>
    <a href="admin.php?act=tour_list">🧭 Danh sách Tour</a>
    <a href="admin.php?act=booking">📋 Booking</a>
    <a href="admin.php?act=guide">👥 Hướng dẫn viên</a>
    <a href="admin.php?act=attendance">🕒 Điểm danh</a>
    <a href="admin.php?act=statistical">📈 Thống kê</a>
    <a href="admin.php?act=customer">👤 Khách hàng</a>

    <a href="admin.php?act=logout" style="color:#f87171;">🚪 Đăng xuất</a>
  </div>

  <!-- CONTENT -->
  <div id="content">

    <!-- NAVBAR -->
    <nav class="navbar navbar-light bg-white shadow-sm p-3 rounded mb-4">
      <div class="container-fluid">
        <span class="navbar-brand h4">Dashboard Admin</span>

        <div class="dropdown">
          <a class="dropdown-toggle fw-bold" href="#" data-bs-toggle="dropdown">
            <?= htmlspecialchars($_SESSION['admin']['username'] ?? 'Admin') ?>
          </a>
          <ul class="dropdown-menu dropdown-menu-end">
            <li><a class="dropdown-item" href="admin.php?act=dashboard">Hồ sơ</a></li>
            <li><a class="dropdown-item text-danger" href="admin.php?act=logout">Đăng xuất</a></li>
          </ul>
        </div>
      </div>
    </nav>
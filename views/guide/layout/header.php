<?php 
if (!isset($_SESSION)) session_start();
$guide = $_SESSION['guide'] ?? null;
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Hướng Dẫn Viên</title>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">

    <!-- Custom Layout CSS -->
    <link rel="stylesheet" href="views/guide/layout/css/layout.css">
</head>

<body>

    <!-- SIDEBAR -->
    <div id="sidebar">
        <h4>👨‍✈️ HƯỚNG DẪN VIÊN</h4>

        <a href="index.php" class="<?= ($_GET['act'] ?? '') == '' ? 'active' : '' ?>">🏠 Dashboard</a>

        <a href="index.php?act=my-tours" 
           class="<?= ($_GET['act'] ?? '') == 'my-tours' ? 'active' : '' ?>">
           🧭 Lịch trong tháng
        </a>

        <a href="index.php?act=today" 
           class="<?= ($_GET['act'] ?? '') == 'today' ? 'active' : '' ?>">
           ⏳ Lịch hôm nay
        </a>

          <a href="index.php?act=history" 
              class="<?= ($_GET['act'] ?? '') == 'history' ? 'active' : '' ?>">
              👥 Lịch sử dẫn tour
          </a>

        <a href="index.php?act=profile" 
           class="<?= ($_GET['act'] ?? '') == 'profile' ? 'active' : '' ?>">
           👤 Hồ sơ cá nhân
        </a>

        <a href="index.php?act=logout" style="color: #f87171;">🚪 Đăng xuất</a>
    </div>

    <!-- CONTENT -->
    <div id="content">

        <!-- NAVBAR -->
        <nav class="navbar navbar-light bg-white shadow-sm p-3 rounded mb-4">
            <div class="container-fluid">
                <span class="navbar-brand h4">
                    <?= isset($pageTitle) ? $pageTitle : "Dashboard" ?>
                </span>

                <?php if ($guide): ?>
                <div class="dropdown">
                    <a class="dropdown-toggle fw-bold" href="#" data-bs-toggle="dropdown">
                        <?= htmlspecialchars($guide['fullname']) ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="index.php?act=profile">Hồ sơ</a></li>
                        <li><a class="dropdown-item text-danger" href="index.php?act=logout">Đăng xuất</a></li>
                    </ul>
                </div>
                <?php endif; ?>
            </div>
        </nav>

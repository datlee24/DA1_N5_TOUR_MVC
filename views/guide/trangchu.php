<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($title) ? htmlspecialchars($title) : 'Trang chủ'; ?></title>
    <link rel="stylesheet" href="views/admin/layout/css/styles.css">
    <style>
        .dashboard {
            padding: 24px;
        }

        .card {
            border-radius: 8px;
            padding: 14px;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.06);
            background: #fff;
        }

        .cards-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 12px;
        }

        .tours-list {
            margin-top: 12px;
        }

        .empty {
            color: #777;
            font-style: italic;
        }

        @media (max-width:900px) {
            .cards-grid {
                grid-template-columns: 1fr
            }
        }

        /* ensure page content spacing matches admin layout */
        #page-content-wrapper .container-fluid {
            padding-top: 18px;
        }
    </style>
</head>

<body>
    <div class="d-flex" id="wrapper">
        <!-- Sidebar-->
        <div class="border-end bg-white" id="sidebar-wrapper">
            <div class="sidebar-heading border-bottom bg-light">Hướng dẫn viên</div>
            <div class="list-group list-group-flush">
                <a class="list-group-item list-group-item-action list-group-item-light p-3" href="?controller=guide&action=dashboard">Dashboard</a>
                <a class="list-group-item list-group-item-action list-group-item-light p-3" href="?controller=guide&action=schedule">Lịch</a>
                <a class="list-group-item list-group-item-action list-group-item-light p-3" href="?controller=guide&action=tours">Tour của tôi</a>
                <a class="list-group-item list-group-item-action list-group-item-light p-3" href="?controller=guide&action=bookings">Đặt chỗ</a>
                <a class="list-group-item list-group-item-action list-group-item-light p-3" href="?controller=guide&action=messages">Tin nhắn</a>
                <a class="list-group-item list-group-item-action list-group-item-light p-3" href="?controller=guide&action=profile">Hồ sơ</a>
                <a class="list-group-item list-group-item-action list-group-item-light p-3" href="?controller=auth&action=logout">Đăng xuất</a>
            </div>
        </div>
        <!-- Page content wrapper-->
        <div id="page-content-wrapper">
            <!-- Top navigation-->
            <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
                <div class="container-fluid">
                    <button class="btn btn-primary" id="sidebarToggle">Menu</button>
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav ms-auto mt-2 mt-lg-0">
                            <li class="nav-item active"><a class="nav-link" href="#">Home</a></li>
                            <li class="nav-item"><a class="nav-link" href="#">Trợ giúp</a></li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <?php echo isset($user) && isset($user['name']) ? htmlspecialchars($user['name']) : (isset($guideName) ? htmlspecialchars($guideName) : 'Hướng dẫn viên'); ?>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="?controller=guide&action=profile">Hồ sơ</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="?controller=auth&action=logout">Đăng xuất</a>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>

            <!-- Page content-->
            <div class="container-fluid">
                <div class="dashboard container-fluid">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div>
                            <h1 class="h3 mb-0">Xin chào,
                                <?php echo isset($guideName) ? htmlspecialchars($guideName) : (isset($user) && isset($user['name']) ? htmlspecialchars($user['name']) : 'Hướng dẫn viên'); ?>
                            </h1>
                            <div class="text-muted small"><?php echo isset($thoiTiet) ? htmlspecialchars($thoiTiet) : date('d/m/Y'); ?></div>
                        </div>
                        <div>
                            <a class="btn btn-primary" href="?controller=guide&action=schedule">Lịch hôm nay</a>
                            <a class="btn btn-outline-secondary" href="?controller=guide&action=profile">Hồ sơ</a>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-8">
                            <div class="cards-grid">
                                <div class="card">
                                    <h4 class="h6">Chuyến trong ngày</h4>
                                    <div class="tours-list">
                                        <?php if (!empty($tours) && is_array($tours)): ?>
                                            <ul class="list-unstyled">
                                                <?php foreach ($tours as $t): ?>
                                                    <li class="mb-2"><strong><?php echo htmlspecialchars(isset($t['name']) ? $t['name'] : $t); ?></strong>
                                                        <div class="small text-muted"><?php echo htmlspecialchars(isset($t['time']) ? $t['time'] : '—'); ?></div>
                                                    </li>
                                                <?php endforeach; ?>
                                            </ul>
                                        <?php else: ?>
                                            <div class="empty">Không có chuyến nào trong ngày.</div>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="card">
                                    <h4 class="h6">Yêu cầu / Thông báo</h4>
                                    <?php if (!empty($notifications) && is_array($notifications)): ?>
                                        <ul class="list-unstyled">
                                            <?php foreach ($notifications as $n): ?><li class="mb-2"><small><?php echo htmlspecialchars($n); ?></small></li><?php endforeach; ?>
                                        </ul>
                                    <?php else: ?><div class="empty">Bạn không có thông báo mới.</div><?php endif; ?>
                                </div>

                                <div class="card">
                                    <h4 class="h6">Khách sắp tới</h4>
                                    <?php if (!empty($bookings) && is_array($bookings)): ?>
                                        <ul class="list-unstyled">
                                            <?php foreach (array_slice($bookings, 0, 5) as $b): ?>
                                                <li class="mb-2"><?php echo htmlspecialchars(isset($b['customer_name']) ? $b['customer_name'] : (isset($b['name']) ? $b['name'] : 'Khách')); ?>
                                                    <div class="small text-muted"><?php echo htmlspecialchars(isset($b['date']) ? $b['date'] : '—'); ?></div>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    <?php else: ?><div class="empty">Không có đặt chỗ sắp tới.</div><?php endif; ?>
                                </div>
                            </div>

                            <div class="card mt-3">
                                <h4 class="h6">Ghi chú nhanh</h4>
                                <p class="small text-muted">Bạn có thể dùng trang này để kiểm tra lịch, xem danh sách khách, và liên hệ ban quản trị khi cần.</p>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="card mb-3">
                                <h4 class="h6">Hồ sơ</h4>
                                <p><strong><?php echo isset($user) && isset($user['name']) ? htmlspecialchars($user['name']) : (isset($guideName) ? htmlspecialchars($guideName) : 'Hướng dẫn viên'); ?></strong></p>
                                <p class="small text-muted">Số điện thoại: <?php echo isset($user) && isset($user['phone']) ? htmlspecialchars($user['phone']) : 'Chưa cập nhật'; ?></p>
                                <p class="small text-muted">Email: <?php echo isset($user) && isset($user['email']) ? htmlspecialchars($user['email']) : 'Chưa cập nhật'; ?></p>
                                <div class="mt-2"><a class="btn btn-sm btn-outline-primary" href="?controller=guide&action=profile">Chỉnh sửa</a></div>
                            </div>

                            <div class="card">
                                <h4 class="h6">Tùy chọn nhanh</h4>
                                <div class="d-grid gap-2">
                                    <a class="btn btn-sm btn-success" href="?controller=guide&action=startTour">Bắt đầu chuyến</a>
                                    <a class="btn btn-sm btn-warning" href="?controller=guide&action=report">Báo cáo</a>
                                    <a class="btn btn-sm btn-secondary" href="?controller=guide&action=messages">Tin nhắn</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- bootstrap js-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        window.addEventListener("DOMContentLoaded", (event) => {
            const sidebarToggle = document.body.querySelector("#sidebarToggle");
            if (sidebarToggle) {
                sidebarToggle.addEventListener("click", (event) => {
                    event.preventDefault();
                    document.body.classList.toggle("sb-sidenav-toggled");
                    localStorage.setItem(
                        "sb|sidebar-toggle",
                        document.body.classList.contains("sb-sidenav-toggled")
                    );
                });
            }
        });
    </script>
</body>

</html>
</div>
</aside>
</div>
</div>
</body>

</html>
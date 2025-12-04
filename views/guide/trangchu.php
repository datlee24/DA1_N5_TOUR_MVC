<?php headerGuide() ?>

<!-- HERO WELCOME -->
<div class="card p-4 mb-4">
    <h3>Xin chào, <?= htmlspecialchars($guide['fullname'] ?? 'Hướng dẫn viên') ?> 👋</h3>
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

    <!-- Hồ sơ -->
    <div class="col-lg-4">
        <div class="card p-3 mb-4">
            <h5>👤 Hồ sơ cá nhân</h5>
            <hr>

            <p><strong><?= htmlspecialchars($guide['fullname'] ?? '') ?></strong></p>
            <p>SĐT: <?= htmlspecialchars($guide['phone'] ?? '') ?></p>
            <p>Email: <?= htmlspecialchars($guide['email'] ?? '') ?></p>

            <a href="index.php?act=profile" class="btn btn-outline-primary btn-sm mt-2">Cập nhật hồ sơ</a>
        </div>

        <!-- Tùy chọn nhanh -->
        <div class="card p-3">
            <h5>⚡ Tùy chọn nhanh</h5>
            <hr>

            <a href="index.php?act=today" class="btn btn-success btn-sm w-100 mb-2">Điểm danh hôm nay</a>
            <a href="index.php?act=schedule-month" class="btn btn-warning btn-sm w-100 mb-2">Xem lịch tháng</a>
        </div>
    </div>

</div>

<?php footerGuide() ?>

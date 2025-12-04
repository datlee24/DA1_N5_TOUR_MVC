<?php headerAdmin() ?>

<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger" role="alert">
        <?= $_SESSION['error'] ?>
    </div>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>
<?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success" role="alert">
        <?= $_SESSION['success'] ?>
    </div>
    <?php unset($_SESSION['success']); ?>
<?php endif; ?>

<div class="card p-4 mb-4">
    <h3>Xin chào, <?= htmlspecialchars($_SESSION['admin']['username'] ?? 'Admin') ?> 👋</h3>
    <p class="text-muted mb-0">Hôm nay: <?= date("d/m/Y") ?></p>
</div>

<div class="row mb-4">
    <div class="col-lg-4 mb-3">
        <div class="stat-box bg-blue">
            <h5>Tổng Tour</h5>
            <p class="mb-1">Xem chi tiết</p>
            <a href="admin.php?act=tour_list" class="btn btn-light btn-sm">Danh sách tour</a>
        </div>
    </div>
    <div class="col-lg-4 mb-3">
        <div class="stat-box bg-green">
            <h5>Booking</h5>
            <p class="mb-1">Quản lý booking</p>
            <a href="admin.php?act=booking" class="btn btn-light btn-sm">Xem Booking</a>
        </div>
    </div>
    <div class="col-lg-4 mb-3">
        <div class="stat-box bg-orange">
            <h5>Thống kê</h5>
            <p class="mb-1">Báo cáo doanh thu</p>
            <a href="admin.php?act=statistical" class="btn btn-light btn-sm">Xem thống kê</a>
        </div>
    </div>
</div>

<?php footerAdmin() ?>
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
            <div class="stat-value"><?= isset($todaySchedules) ? count($todaySchedules) : 0 ?></div>
            <div class="mt-3"><a href="index.php?act=today" class="btn btn-light btn-sm">Xem chi tiết</a></div>
        </div>
    </div>

    <div class="col-lg-4 mb-3">
        <div class="stat-box bg-green">
            <h4>🧭 Tour trong tháng</h4>
            <div class="stat-value"><?= isset($monthly_unique_count) ? $monthly_unique_count : (isset($monthlySchedules) ? count($monthlySchedules) : 0) ?></div>
            <div class="mt-3"><a href="index.php?act=my-tours" class="btn btn-light btn-sm">Xem tour</a></div>
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

            <?php if (!empty($todaySchedules) && is_array($todaySchedules)): ?>
                <ul class="today-list mb-0">
                    <?php foreach ($todaySchedules as $ts): ?>
                        <li class="today-list-item">
                            <a href="index.php?act=today&schedule_id=<?= htmlspecialchars($ts['schedule_id']) ?>">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong><?= htmlspecialchars($ts['tour_name'] ?? 'Không rõ') ?></strong>
                                        <div class="muted small"><?= !empty($ts['start_date']) ? date('d/m/Y', strtotime($ts['start_date'])) : '' ?> - <?= !empty($ts['end_date']) ? date('d/m/Y', strtotime($ts['end_date'])) : '' ?></div>
                                    </div>
                                    <div class="text-end">
                                        <span class="badge bg-light text-dark">Xem</span>
                                    </div>
                                </div>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p class="text-muted">Hôm nay bạn không có chuyến nào.</p>
            <?php endif; ?>
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

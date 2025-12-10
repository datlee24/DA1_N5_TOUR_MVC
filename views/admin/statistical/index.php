<?php headerAdmin() ?>

<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger" role="alert"><?= $_SESSION['error'] ?></div>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>
<?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success" role="alert"><?= $_SESSION['success'] ?></div>
    <?php unset($_SESSION['success']); ?>
<?php endif; ?>

<h1 class="mt-4">Báo Cáo Thống Kê</h1>

<form method="get" class="row g-2 align-items-end mt-3 mb-3">
    <input type="hidden" name="act" value="statistical">
    <div class="col-auto">
        <label class="form-label small">Tháng</label>
        <select name="month" class="form-select form-select-sm">
            <?php foreach (($monthOptions ?? range(1, 12)) as $m): ?>
                <option value="<?= $m ?>" <?= (isset($selectedMonth) && $selectedMonth == $m) ? 'selected' : '' ?>>Tháng <?= $m ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-auto">
        <label class="form-label small">Năm</label>
        <select name="year" class="form-select form-select-sm">
            <?php foreach (($yearOptions ?? [date('Y')]) as $y): ?>
                <option value="<?= $y ?>" <?= (isset($selectedYear) && $selectedYear == $y) ? 'selected' : '' ?>><?= $y ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-auto">
        <button class="btn btn-primary btn-sm" type="submit">Lọc</button>
        <a class="btn btn-outline-secondary btn-sm" href="admin.php?act=statistical">Reset</a>
    </div>
</form>

<div class="row mt-4">
    <div class="col-md-3 mb-3">
        <div class="stat-box bg-blue">
            <h5>Tổng Tour</h5>
            <p class="h3"><?= $totalTours ?? 0 ?></p>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="stat-box bg-green">
            <h5>Tour đang hoạt động</h5>
            <p class="h3"><?= $activeTours ?? 0 ?></p>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="stat-box bg-orange">
            <h5>Tổng booking</h5>
            <p class="h3"><?= $totalBookings ?? 0 ?></p>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="stat-box bg-blue">
            <h5>Booking (<?= isset($selectedMonth, $selectedYear) ? 'Tháng ' . $selectedMonth . ' / ' . $selectedYear : 'Tháng này' ?>)</h5>
            <p class="h3"><?= $bookingsThisMonth ?? 0 ?></p>
        </div>
    </div>
</div>

<div class="row mt-3">
    <div class="col-md-6">
        <div class="card p-3">
            <h5>Tổng khách</h5>
            <p class="h3"><?= $totalCustomers ?? 0 ?></p>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card p-3">
            <h5>Tổng doanh thu</h5>
            <p class="h3"><?= number_format($totalRevenue ?? 0, 0, ',', '.') ?> đ</p>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card p-3">
            <h5>Doanh thu (<?= isset($selectedMonth, $selectedYear) ? 'Tháng ' . $selectedMonth . ' / ' . $selectedYear : 'Tháng này' ?>)</h5>
            <p class="h3"><?= number_format($revenueThisMonth ?? 0, 0, ',', '.') ?> đ</p>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card p-3">
            <h5>Top 5 Tour theo số booking</h5>
            <table class="table table-sm mt-2">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tên tour</th>
                        <th>Số booking</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($topTours)): ?>
                        <?php $i = 1;
                        foreach ($topTours as $t): ?>
                            <tr>
                                <td><?= $i++ ?></td>
                                <td><?= htmlspecialchars($t['name']) ?></td>
                                <td><?= $t['cnt'] ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3">Chưa có dữ liệu</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="row mt-3">
    <div class="col-md-12">
        <div class="card p-3">
            <h5>Top 5 Tour theo doanh thu</h5>
            <table class="table table-sm mt-2">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tên tour</th>
                        <th>Doanh thu</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($topToursByRevenue)): ?>
                        <?php $j = 1;
                        foreach ($topToursByRevenue as $tt): ?>
                            <tr>
                                <td><?= $j++ ?></td>
                                <td><?= htmlspecialchars($tt['name']) ?></td>
                                <td><?= number_format($tt['revenue'] ?? 0, 0, ',', '.') ?> đ</td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3">Chưa có dữ liệu</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php footerAdmin() ?>
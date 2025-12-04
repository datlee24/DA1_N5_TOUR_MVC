<?php headerAdmin() ?>

<div class="card p-3 mb-4">
    <h4 class="mb-3">📋 Danh sách điểm danh</h4>

    <form method="get" class="row g-2 align-items-end mb-3">
        <input type="hidden" name="act" value="attendance">
        <div class="col-md-2">
            <label class="form-label small">Schedule ID</label>
            <input class="form-control form-control-sm" type="text" name="schedule_id" value="<?= htmlspecialchars($_GET['schedule_id'] ?? '') ?>">
        </div>
        <div class="col-md-2">
            <label class="form-label small">Guide ID</label>
            <input class="form-control form-control-sm" type="text" name="guide_id" value="<?= htmlspecialchars($_GET['guide_id'] ?? '') ?>">
        </div>
        <div class="col-md-2">
            <label class="form-label small">Customer ID</label>
            <input class="form-control form-control-sm" type="text" name="customer_id" value="<?= htmlspecialchars($_GET['customer_id'] ?? '') ?>">
        </div>
        <div class="col-md-3">
            <label class="form-label small">Date</label>
            <input class="form-control form-control-sm" type="date" name="date" value="<?= htmlspecialchars($_GET['date'] ?? '') ?>">
        </div>
        <div class="col-md-2">
            <button class="btn btn-primary btn-sm w-100" type="submit">Lọc</button>
        </div>
        <div class="col-md-1">
            <a href="admin.php?act=attendance" class="btn btn-outline-secondary btn-sm w-100">Reset</a>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-striped table-hover align-middle">
            <thead>
                <tr>
                    <th>Thời gian</th>
                    <th>Tour / Schedule</th>
                    <th>Khách</th>
                    <th>Guide</th>
                    <th>Trạng thái</th>
                    <th>Ghi chú</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($data)): ?>
                    <?php foreach ($data as $r): ?>
                        <tr>
                            <td><?= htmlspecialchars($r['marked_at'] ? date("d/m/Y H:i", strtotime($r['marked_at'])) : '-') ?></td>
                            <td><?= htmlspecialchars($r['tour_name'] ?? '-') ?> <div class="small text-muted">(ID: <?= htmlspecialchars($r['schedule_id']) ?>)</div>
                            </td>
                            <td><?= htmlspecialchars($r['customer_name'] ?? '-') ?> <div class="small text-muted">(ID: <?= htmlspecialchars($r['customer_id']) ?>)</div>
                            </td>
                            <td><?= htmlspecialchars($r['guide_name'] ?? '-') ?> <div class="small text-muted">(ID: <?= htmlspecialchars($r['guide_id']) ?>)</div>
                            </td>
                            <td>
                                <?php $st = strtolower($r['status'] ?? 'unknown'); ?>
                                <?php if ($st === 'present' || $st === 'đã điểm danh' || $st === 'present'): ?>
                                    <span class="badge bg-success">Có mặt</span>
                                <?php elseif ($st === 'absent' || $st === 'vắng' || $st === 'absent'): ?>
                                    <span class="badge bg-danger">Vắng</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary"><?= htmlspecialchars($r['status'] ?? '-') ?></span>
                                <?php endif; ?>
                            </td>
                            <td><?= nl2br(htmlspecialchars($r['note'] ?? '-')) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center text-muted">Chưa có dữ liệu</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</div>

<?php footerAdmin() ?>
<?php headerAdmin(); ?>
<?php 
$step = 3; 
include __DIR__ . '/progress.php';
?>

<div class="card shadow p-4">
    <h4 class="mb-3">Bước 3: Chọn hướng dẫn viên</h4>

    <?php if (!empty($_SESSION['error'])): ?>
        <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>

    <form action="admin.php?act=booking-step3-save" method="POST">
        <label class="fw-bold mb-1">Hướng dẫn viên</label>

        <?php foreach ($guides as $g): ?>
            <div class="card mb-2 <?= $g['available'] ? 'border-success' : 'border-secondary' ?>">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <input type="radio" name="guide_id" value="<?= $g['guide_id'] ?>" id="g-<?= $g['guide_id'] ?>" <?= $g['available'] ? '' : 'disabled' ?>>
                        <label for="g-<?= $g['guide_id'] ?>" class="fw-bold ms-2">
                            <?= $g['fullname'] ?> (<?= $g['phone'] ?>)
                        </label>
                        <?php if (!$g['available']): ?>
                            <span class="badge bg-danger ms-2">Đã trùng lịch</span>
                        <?php else: ?>
                            <span class="badge bg-success ms-2">Rảnh</span>
                        <?php endif; ?>
                    </div>

                    <div class="text-end small text-muted">
                        <div>Lịch của HDV:</div>
                        <?php if (!empty($g['schedules'])): ?>
                            <?php foreach ($g['schedules'] as $sc): ?>
                                <div>
                                    <?= date("d/m/Y", strtotime($sc['start_date'])) ?> → <?= date("d/m/Y", strtotime($sc['end_date'])) ?>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div>Chưa có lịch</div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>

        <div class="d-flex justify-content-between">
            <a href="admin.php?act=booking-step2" class="btn btn-secondary">← Quay lại</a>
            <button class="btn btn-primary">Tiếp tục →</button>
        </div>
    </form>
</div>

<?php footerAdmin(); ?>

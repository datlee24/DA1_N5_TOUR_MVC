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
        <select name="guide_id" class="form-select form-select-lg mb-3" required>
            <option value="">-- Chọn HDV --</option>
            <?php foreach ($guides as $g): ?>
            <option value="<?= $g['guide_id'] ?>">
                <?= $g['fullname'] ?> (<?= $g['phone'] ?>)
            </option>
            <?php endforeach; ?>
        </select>

        <div class="d-flex justify-content-between">
            <a href="admin.php?act=booking-step2" class="btn btn-secondary">← Quay lại</a>
            <button class="btn btn-primary">Tiếp tục →</button>
        </div>

    </form>
</div>

<?php footerAdmin(); ?>

<?php headerAdmin(); ?>

<?php 
$step = 4; 
include __DIR__ . '/progress.php';
?>


<div class="card shadow p-4">

    <h4 class="mb-3">Bước 4: Chọn khách hàng</h4>

    <?php if (!empty($_SESSION['error'])): ?>
        <div class="alert alert-warning"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>

    <form action="admin.php?act=booking-step4-save" method="POST">

        <label class="fw-bold mb-1">Danh sách khách (nhiều người)</label>
        <select name="customer_ids[]" multiple size="8" class="form-select mb-3" required>
            <?php foreach ($customers as $c): ?>
            <option value="<?= $c['customer_id'] ?>">
                <?= $c['fullname'] ?> – <?= $c['phone'] ?>
            </option>
            <?php endforeach; ?>
        </select>

        <small class="text-muted">Giữ Ctrl để chọn nhiều khách.</small>

        <div class="d-flex justify-content-between mt-3">
            <a href="admin.php?act=booking-step3" class="btn btn-secondary">← Quay lại</a>
            <button class="btn btn-primary">Tiếp tục →</button>
        </div>
    </form>
</div>

<?php footerAdmin(); ?>

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
        <label class="fw-bold mb-2">Danh sách khách (click để chọn)</label>

        <div class="row">
            <?php foreach ($customers as $c): ?>
                <div class="col-md-4">
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" 
                               name="customer_ids[]" 
                               value="<?= $c['customer_id'] ?>" 
                               id="cus-<?= $c['customer_id'] ?>">
                        <label class="form-check-label" for="cus-<?= $c['customer_id'] ?>">
                            <?= $c['fullname'] ?> — <?= $c['phone'] ?>
                        </label>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="d-flex justify-content-between mt-3">
            <a href="admin.php?act=booking-step3" class="btn btn-secondary">← Quay lại</a>
            <button class="btn btn-primary">Tiếp tục →</button>
        </div>
    </form>
</div>

<?php footerAdmin(); ?>

<?php headerAdmin(); ?>

<?php 
$step = 1; 
include __DIR__ . '/progress.php';
?>


<div class="card shadow p-4">
    <h4 class="mb-3">Bước 1: Chọn tour du lịch</h4>

    <form action="admin.php?act=booking-step1-save" method="POST">
        <label class="fw-bold mb-1">Tour</label>
        <select name="tour_id" class="form-select form-select-lg mb-3" required>
            <option value="">-- Chọn tour --</option>
            <?php foreach ($tours as $t): ?>
            <option value="<?= $t['tour_id'] ?>"><?= $t['name'] ?></option>
            <?php endforeach; ?>
        </select>

        <button class="btn btn-primary btn-lg">Tiếp tục →</button>
    </form>
</div>

<?php footerAdmin(); ?>

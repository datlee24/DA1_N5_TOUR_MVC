<?php headerAdmin(); ?>

<?php 
$step = 2; 
include __DIR__ . '/progress.php';
?>


<div class="card shadow p-4">
    <h4 class="mb-3">Bước 2: Chọn lịch khởi hành</h4>

    <form action="admin.php?act=booking-step2-save" method="POST">

        <label class="fw-bold mb-1">Lịch khởi hành</label>
        <select name="schedule_id" class="form-select form-select-lg" required>
            <?php foreach ($schedules as $s): ?>
            <option value="<?= $s['schedule_id'] ?>">
                <?= date("d/m/Y", strtotime($s['start_date'])) ?>
                 → 
                <?= date("d/m/Y", strtotime($s['end_date'])) ?>
                <?= $s['guide_id'] ? "(Đã có HDV)" : "(Chưa có HDV)" ?>
            </option>
            <?php endforeach; ?>
        </select>

        <div class="mt-3 d-flex justify-content-between">
            <a href="admin.php?act=booking-step1" class="btn btn-secondary">← Quay lại</a>
            <button class="btn btn-primary">Tiếp tục →</button>
        </div>
    </form>
</div>

<?php footerAdmin(); ?>

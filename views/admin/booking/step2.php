<?php headerAdmin(); ?>
<?php 
$step = 2; 
include __DIR__ . '/progress.php';
?>

<div class="card shadow p-4">
    <h4 class="mb-3">Bước 2: Chọn lịch khởi hành hoặc tạo lịch mới</h4>

    <?php if (!empty($_SESSION['error'])): ?>
        <div class="alert alert-warning"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>

    <form action="admin.php?act=booking-step2-save" method="POST">
        <label class="fw-bold mb-1">Chọn lịch có sẵn</label>
        <select name="schedule_id" class="form-select form-select-lg mb-3">
            <option value="">-- Chọn lịch có sẵn (nếu có) --</option>
            <?php foreach ($schedules as $s): ?>
            <option value="<?= $s['schedule_id'] ?>">
                <?= date("d/m/Y", strtotime($s['start_date'])) ?> → <?= date("d/m/Y", strtotime($s['end_date'])) ?>
                <?= $s['guide_id'] ? "(Đã có HDV)" : "(Chưa có HDV)" ?>
            </option>
            <?php endforeach; ?>
        </select>

        <hr>

        <label class="fw-bold mb-1">Hoặc: Tạo lịch mới</label>
        <div class="row g-2">
            <div class="col-md-4">
                <input type="date" name="start_date" class="form-control" placeholder="Ngày bắt đầu">
            </div>
            <div class="col-md-4">
                <input type="date" name="end_date" class="form-control" placeholder="Ngày kết thúc">
            </div>
            <div class="col-md-4">
                <input type="text" name="meeting_point" class="form-control" placeholder="Điểm tập trung (tuỳ chọn)">
            </div>
        </div>

        <div class="mt-3 d-flex justify-content-between">
            <a href="admin.php?act=booking-step1" class="btn btn-secondary">← Quay lại</a>
            <button class="btn btn-primary">Tiếp tục →</button>
        </div>
    </form>
</div>

<?php footerAdmin(); ?>

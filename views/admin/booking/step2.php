<?php headerAdmin(); $step = 2; include __DIR__ . '/progress.php'; ?>

<div class="card shadow-sm p-4">
  <h4>Bước 2 — Chọn lịch khởi hành hoặc tạo lịch mới</h4>

  <?php if (!empty($_SESSION['error'])): ?>
    <div class="alert alert-warning"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
  <?php endif; ?>

  <form action="admin.php?act=booking-step2-save" method="POST">
    <div class="mb-3">
      <label class="form-label">Chọn lịch có sẵn</label>
      <select name="schedule_id" class="form-select">
        <option value="">-- Chọn lịch (nếu có) --</option>
        <?php foreach ($schedules as $s): ?>
          <option value="<?= $s['schedule_id'] ?>">
            <?= date("d/m/Y", strtotime($s['start_date'])) ?> → <?= date("d/m/Y", strtotime($s['end_date'])) ?>
            <?= $s['guide_id'] ? ' (Có HDV)' : ' (Chưa HDV)' ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>

    <hr>

    <h6>Tạo lịch mới</h6>
    <div class="row g-2">
      <div class="col-md-4">
        <label class="form-label">Ngày bắt đầu</label>
        <input type="date" name="start_date" class="form-control">
      </div>
      <div class="col-md-4">
        <label class="form-label">Ngày kết thúc</label>
        <input type="date" name="end_date" class="form-control">
      </div>
      <div class="col-md-4">
        <label class="form-label">Điểm tập trung</label>
        <input type="text" name="meeting_point" class="form-control" placeholder="Ví dụ: Sân bay Nội Bài">
      </div>
    </div>

    <div class="mt-3 d-flex justify-content-between">
      <a href="admin.php?act=booking-step1" class="btn btn-outline-secondary">← Quay lại</a>
      <button class="btn btn-primary">Tiếp tục →</button>
    </div>
  </form>
</div>

<?php footerAdmin(); ?>

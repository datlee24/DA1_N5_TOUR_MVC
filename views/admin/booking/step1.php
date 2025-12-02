<?php headerAdmin(); $step = 1; include __DIR__ . '/progress.php'; ?>

<div class="card shadow-sm p-4">
  <h4>Bước 1 — Chọn Tour & (Tuỳ chọn) Tài xế</h4>

  <?php if (!empty($_SESSION['error'])): ?>
    <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
  <?php endif; ?>

  <form action="admin.php?act=booking-step1-save" method="POST" class="mt-3">
    <div class="mb-3">
      <label class="form-label">Chọn Tour</label>
      <select name="tour_id" class="form-select form-select-lg" required>
        <option value="">-- Chọn tour --</option>
        <?php foreach ($tours as $t): ?>
          <option value="<?= $t['tour_id'] ?>"><?= htmlspecialchars($t['name']) ?> — <?= number_format($t['price']) ?>₫</option>
        <?php endforeach; ?>
      </select>
    </div>

    <div class="mb-3">
      <label class="form-label">Chọn tài xế (tuỳ chọn)</label>
      <select name="driver_id" class="form-select">
        <option value="">-- Không chọn --</option>
        <?php foreach ($drivers as $d): ?>
          <option value="<?= $d['driver_id'] ?>"><?= htmlspecialchars($d['fullname']) ?> — <?= htmlspecialchars($d['license_plate']) ?></option>
        <?php endforeach; ?>
      </select>
      <div class="form-text">Nếu chọn tài xế, hệ thống sẽ kiểm tra trùng lịch khi chọn/ tạo lịch ở bước 2.</div>
    </div>

    <div class="d-flex justify-content-between">
      <a href="admin.php?act=booking" class="btn btn-outline-secondary">Hủy</a>
      <button class="btn btn-primary btn-lg">Tiếp tục →</button>
    </div>
  </form>
</div>

<?php footerAdmin(); ?>

<?php headerAdmin(); $step = 5; include __DIR__ . '/progress.php'; ?>

<div class="card shadow-sm p-4">
  <h4>Bước 5 — Xác nhận & Thanh toán</h4>

  <div class="row">
    <div class="col-md-8">
      <div class="card mb-3">
        <div class="card-body">
          <h5 class="mb-2"><?= htmlspecialchars($tour['name'] ?? '-') ?></h5>
          <p class="mb-1"><strong>Thời gian:</strong> <?= date("d/m/Y", strtotime($schedule['start_date'])) ?> → <?= date("d/m/Y", strtotime($schedule['end_date'])) ?></p>
          <p class="mb-1"><strong>Điểm họp:</strong> <?= htmlspecialchars($schedule['meeting_point'] ?? '-') ?></p>
          <p class="mb-1"><strong>Hướng dẫn viên:</strong> <?= $guide ? htmlspecialchars($guide['fullname']) . ' (' . ($guide['phone'] ?? '-') . ')' : 'Chưa phân công' ?></p>
          <p class="mb-1"><strong>Tài xế:</strong> <?= $driver ? htmlspecialchars($driver['fullname']) . ' — ' . htmlspecialchars($driver['license_plate']) : 'Chưa chọn' ?></p>
        </div>
      </div>

      <div class="card mb-3">
        <div class="card-body">
          <h6>Danh sách khách (<?= $num_people ?>)</h6>
          <ul class="list-group list-group-flush">
            <?php foreach ($customers as $c): ?>
              <li class="list-group-item d-flex justify-content-between">
                <div>
                  <div class="fw-bold"><?= htmlspecialchars($c['fullname']) ?></div>
                  <div class="small text-muted"><?= htmlspecialchars($c['phone']) ?> — <?= htmlspecialchars($c['email']) ?></div>
                  <?php if (!empty($c['notes'])): ?><div class="small text-muted">Ghi chú: <?= htmlspecialchars($c['notes']) ?></div><?php endif; ?>
                </div>
                <div class="text-end">
                  <div><?= htmlspecialchars($c['room_number'] ?? '-') ?></div>
                  <div class="small <?= ($c['attendance_status'] ?? 'unknown') === 'present' ? 'text-success' : 'text-muted' ?>"><?= ucfirst($c['attendance_status'] ?? 'unknown') ?></div>
                </div>
              </li>
            <?php endforeach; ?>
          </ul>
        </div>
      </div>

    </div>

    <div class="col-md-4">
      <div class="card p-3 mb-3">
        <div class="mb-2"><strong>Giá 1 khách</strong></div>
        <div class="fs-4 text-danger fw-bold mb-3"><?= number_format($price_per) ?>₫</div>

        <div class="mb-2"><strong>Tổng (ước tính)</strong></div>
        <div class="fs-5 fw-bold mb-3"><?= number_format($total_price) ?>₫</div>

        <form action="admin.php?act=booking-finish" method="POST">
          <input type="hidden" name="total_price" value="<?= htmlspecialchars($total_price) ?>">
          <div class="mb-3">
            <label class="form-label">Trạng thái thanh toán</label>
            <select name="payment_status" class="form-select">
              <option value="unpaid">Chưa thanh toán</option>
              <option value="deposit">Đặt cọc</option>
              <option value="paid">Đã thanh toán</option>
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label">Ghi chú</label>
            <textarea name="note" class="form-control" rows="3"></textarea>
          </div>

          <div class="d-flex justify-content-between">
            <a href="admin.php?act=booking-step4" class="btn btn-outline-secondary">← Quay lại</a>
            <button class="btn btn-success">Hoàn tất & Tạo booking</button>
          </div>
        </form>
      </div>

      <div class="card p-3">
        <h6>Khách sạn (theo tour)</h6>
        <?php if (!empty($hotels)): ?>
          <ul class="list-unstyled small">
            <?php foreach ($hotels as $h): ?>
              <li class="mb-2">
                <strong><?= htmlspecialchars($h['name']) ?></strong><br>
                <span class="text-muted"><?= htmlspecialchars($h['address'] ?? '') ?></span><br>
                <small>Người phụ trách: <?= htmlspecialchars($h['manager_name'] ?? '-') ?> — <?= htmlspecialchars($h['manager_phone'] ?? '-') ?></small>
              </li>
            <?php endforeach; ?>
          </ul>
        <?php else: ?>
          <div class="text-muted small">Chưa có khách sạn cho tour này.</div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<?php footerAdmin(); ?>

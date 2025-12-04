<?php headerGuide(); ?>
<div class="container-fluid px-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h4>Chi tiết Lịch Làm việc  <?= htmlspecialchars($schedule['schedule_id']) ?></h4>
    <div>
      <a href="index.php" class="btn btn-outline-dark me-2">Quay lại</a>
      <?php if ($schedule['status_text'] === 'ongoing'): ?>
        <a href="index.php?act=attendance&schedule_id=<?= $schedule['schedule_id'] ?>" class="btn btn-success">
          Điểm danh
        </a>
      <?php else: ?>
        <button class="btn btn-secondary" disabled>Điểm danh (chỉ khi đang diễn ra)</button>
      <?php endif; ?>
    </div>
  </div>

  <div class="row g-3">
    <div class="col-md-6">
      <div class="card mb-3">
        <div class="card-header">Thông tin lịch</div>
        <div class="card-body">
          <p><strong>Tour:</strong> <?= htmlspecialchars($schedule['tour_name'] ?? '-') ?></p>
          <p><strong>Ngày:</strong> <?= date("d/m/Y", strtotime($schedule['start_date'])) ?> → <?= date("d/m/Y", strtotime($schedule['end_date'])) ?></p>
          <p><strong>Số khách (tổng booking):</strong> <?= (int)($schedule['total_customers'] ?? 0) ?></p>
          <p><strong>Ghi chú:</strong><br><?= nl2br(htmlspecialchars($schedule['notes'] ?? '')) ?></p>
        </div>
      </div>

      <div class="card mb-3">
        <div class="card-header">Tài xế</div>
        <div class="card-body">
          <?php if (!empty($schedule['driver_name'])): ?>
            <p><strong>Tên:</strong> <?= htmlspecialchars($schedule['driver_name']) ?></p>
            <p><strong>Biển số:</strong> <?= htmlspecialchars($schedule['license_plate'] ?? '-') ?></p>
            <p><strong>SĐT:</strong> <?= htmlspecialchars($schedule['driver_phone'] ?? '-') ?></p>
          <?php else: ?>
            <div class="text-muted">Chưa phân công tài xế.</div>
          <?php endif; ?>
        </div>
      </div>

      <div class="card mb-3">
        <div class="card-header">Khách sạn</div>
        <div class="card-body">
          <?php if (!empty($schedule['hotel_name'])): ?>
            <p><strong>Tên:</strong> <?= htmlspecialchars($schedule['hotel_name']) ?></p>
            <p><strong>Địa chỉ:</strong> <?= htmlspecialchars($schedule['hotel_address'] ?? '-') ?></p>
            <p><strong>Người quản lý:</strong> <?= htmlspecialchars($schedule['manager_name'] ?? '-') ?></p>
            <p><strong>SĐT quản lý:</strong> <?= htmlspecialchars($schedule['manager_phone'] ?? '-') ?></p>
          <?php else: ?>
            <div class="text-muted">Chưa gán khách sạn.</div>
          <?php endif; ?>
        </div>
      </div>
    </div>

    <div class="col-md-6">
      <div class="card">
        <div class="card-header">Danh sách khách</div>
        <div class="card-body p-0">
          <table class="table mb-0">
            <thead class="table-light">
              <tr>
                <th>ID</th>
                <th>Họ tên</th>
                <th>SĐT</th>
                <th>Email</th>
                <th>Điểm danh</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($customers as $i => $c): ?>
                <tr>
                  <td><?= $i+1 ?></td>
                  <td><?= htmlspecialchars($c['fullname']) ?></td>
                  <td><?= htmlspecialchars($c['phone']) ?></td>
                  <td><?= htmlspecialchars($c['email'] ?? '-') ?></td>
                  <td><?= htmlspecialchars($c['attendance_status'] ?? 'Chưa') ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
<?php footerGuide(); ?>

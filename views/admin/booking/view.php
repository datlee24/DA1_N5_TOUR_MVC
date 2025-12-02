<?php headerAdmin(); ?>

<div class="container-fluid px-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h4>Chi tiết Booking #<?= htmlspecialchars($booking['booking_id']) ?></h4>
    <div>
      <a href="admin.php?act=booking" class="btn btn-outline-dark">Quay lại</a>
    </div>
  </div>

  <div class="row g-3">
    <div class="col-md-6">
      <div class="card mb-3">
        <div class="card-header">Thông tin booking</div>
        <div class="card-body">
          <p><strong>Tour:</strong> <?= htmlspecialchars($booking['tour_name']) ?></p>
          <p><strong>Ngày:</strong> <?= date("d/m/Y", strtotime($booking['start_date'])) ?> → <?= date("d/m/Y", strtotime($booking['end_date'])) ?></p>
          <p><strong>Số khách:</strong> <?= htmlspecialchars($booking['num_people']) ?></p>
          <p><strong>Tổng tiền:</strong> <?= number_format($booking['total_price']) ?>₫</p>
          <p><strong>Trạng thái:</strong>
            <?php if ($booking['status']=='upcoming') echo '<span class="badge bg-primary">Chưa diễn ra</span>';
                  elseif ($booking['status']=='ongoing') echo '<span class="badge bg-warning text-dark">Đang diễn ra</span>';
                  elseif ($booking['status']=='completed') echo '<span class="badge bg-success">Kết thúc</span>';
                  elseif ($booking['status']=='cancelled') echo '<span class="badge bg-danger">Đã hủy</span>';
            ?>
          </p>
          <p><strong>Thanh toán:</strong> <?= htmlspecialchars($booking['payment_status']) ?></p>
          <p><strong>Ghi chú:</strong><br><?= nl2br(htmlspecialchars($booking['note'] ?? '')) ?></p>
        </div>
      </div>
    </div>

    <div class="col-md-6">
      <div class="card mb-3">
        <div class="card-header">Hướng dẫn viên & Tài xế</div>
        <div class="card-body">
          <p><strong>HDV:</strong> <?= htmlspecialchars($guide['fullname'] ?? 'Chưa phân công') ?> — <?= htmlspecialchars($guide['phone'] ?? '-') ?></p>
          <p><strong>Tài xế:</strong> <?= htmlspecialchars($driver['fullname'] ?? 'Chưa phân công') ?> — <?= htmlspecialchars($driver['license_plate'] ?? '-') ?></p>
        </div>
      </div>
    </div>
  </div>

  <!-- Danh sách khách -->
  <div class="card mt-3">
    <div class="card-header">Danh sách khách theo schedule</div>
    <div class="card-body p-0">
      <table class="table mb-0">
        <thead class="table-light">
          <tr>
            <th>ID</th>
            <th>Họ tên</th>
            <th>Điện thoại</th>
            <th>Email</th>
            <th>Phòng</th>
            <th>Check-in</th>
            <th>Điểm danh</th>
          </tr>
        </thead>
        <tbody>
          <?php $i=1; foreach ($customers as $cus): ?>
            <tr>
              <td><?= $i++ ?></td>
              <td><?= htmlspecialchars($cus['fullname']) ?></td>
              <td><?= htmlspecialchars($cus['phone']) ?></td>
              <td><?= htmlspecialchars($cus['email']) ?></td>
              <td><?= htmlspecialchars($cus['room_number'] ?? '-') ?></td>
              <td>
                <?php if (!empty($cus['checkin_status']) && $cus['checkin_status'] === 'checked_in'): ?>
                  <span class="badge bg-success">Đã nhận phòng</span>
                <?php else: ?>
                  <span class="badge bg-secondary">Chưa</span>
                <?php endif; ?>
              </td>
              <td>
                <?php if (!empty($cus['attendance_status']) && $cus['attendance_status'] === 'present'): ?>
                  <span class="badge bg-success">Có mặt</span>
                <?php elseif (!empty($cus['attendance_status']) && $cus['attendance_status'] === 'absent'): ?>
                  <span class="badge bg-danger">Vắng</span>
                <?php else: ?>
                  <span class="badge bg-secondary">Chưa điểm danh</span>
                <?php endif; ?>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>

</div>

<?php footerAdmin(); ?>

<?php headerAdmin(); ?>

<div class="container-fluid px-4">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">Danh sách Booking</h2>
    <a href="admin.php?act=booking-step1" class="btn btn-success">
      <i class="fa fa-plus me-2"></i> Tạo Booking mới
    </a>
  </div>

  <?php if (!empty($_SESSION['success'])): ?>
    <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
  <?php endif; ?>
  <?php if (!empty($_SESSION['error'])): ?>
    <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
  <?php endif; ?>

  <div class="card shadow-sm">
    <div class="card-body p-0">
      <table class="table table-hover mb-0 align-middle">
        <thead class="table-light">
          <tr>
            <th>ID</th>
            <th>Tour</th>
            <th>Ngày đi</th>
            <th>Ngày về</th>
            <th>Số người</th>
            <th>Thanh toán</th>
            <th>Trạng thái</th>
            <th class="text-end">Hành động</th>
          </tr>
        </thead>
        <tbody>
        <?php foreach ($bookings as $b): ?>
          <tr>
            <td>#<?= htmlspecialchars($b['booking_id']) ?></td>
            <td><?= htmlspecialchars($b['tour_name'] ?? '-') ?></td>
            <td><span class="badge bg-info text-dark"><?= date("d/m/Y", strtotime($b['start_date'])) ?></span></td>
            <td><span class="badge bg-primary"><?= date("d/m/Y", strtotime($b['end_date'])) ?></span></td>
            <td>
              <?php $n = intval($b['num_people']); ?>
              <?php if ($n >= 35): ?>
                <span class="badge bg-danger"><?= $n ?> / 40</span>
              <?php else: ?>
                <span class="badge bg-success"><?= $n ?> / 40</span>
              <?php endif; ?>
            </td>
            <td>
              <?php if ($b['payment_status'] === 'paid'): ?>
                <span class="badge bg-success">Đã thanh toán</span>
              <?php elseif ($b['payment_status'] === 'deposit'): ?>
                <span class="badge bg-warning text-dark">Đặt cọc</span>
              <?php else: ?>
                <span class="badge bg-secondary">Chưa thanh toán</span>
              <?php endif; ?>
            </td>
            <td>
              <?php
                // Normalize status display
                $status = $b['status'] ?? '';
                if ($status === 'upcoming') echo '<span class="badge bg-primary">Chưa diễn ra</span>';
                elseif ($status === 'ongoing') echo '<span class="badge bg-warning text-dark">Đang diễn ra</span>';
                elseif ($status === 'completed') echo '<span class="badge bg-success">Kết thúc</span>';
                elseif ($status === 'cancelled') echo '<span class="badge bg-danger">Đã hủy</span>';
                else echo '<span class="badge bg-secondary">'.htmlspecialchars($status).'</span>';
              ?>
            </td>
            <td class="text-end">
              <a class="btn btn-sm btn-outline-info me-1" href="admin.php?act=booking-view&id=<?= $b['booking_id'] ?>">Xem</a>
            </td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?php footerAdmin(); ?>

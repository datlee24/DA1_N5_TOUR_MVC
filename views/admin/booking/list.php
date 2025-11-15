<?php headerAdmin(); ?>
<h2 class="mt-4">Danh sách Booking</h2>

<?php if(isset($_SESSION['error'])): ?>
  <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
<?php endif; ?>
<?php if(isset($_SESSION['success'])): ?>
  <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
<?php endif; ?>

<a class="btn btn-primary mb-3" href="admin.php?act=booking-create">+ Tạo booking</a>

<table class="table table-bordered">
  <thead><tr>
    <th>ID</th><th>Khách</th><th>Tour</th><th>Khởi hành</th><th>Số người</th><th>Trạng thái</th><th>Hành động</th>
  </tr></thead>
  <tbody>
    <?php foreach($bookings as $b): ?>
      <tr>
        <td><?= $b['booking_id'] ?></td>
        <td><?= htmlspecialchars($b['customer_name']) ?></td>
        <td><?= htmlspecialchars($b['tour_name']) ?></td>
        <td><?= $b['start_date'] ?> → <?= $b['end_date'] ?></td>
        <td><?= $b['num_people'] ?></td>
        <td><?= ucfirst($b['status']) ?></td>
        <td>
          <a class="btn btn-sm btn-warning" href="admin.php?act=booking-edit&id=<?= $b['booking_id'] ?>">Cập nhật</a>
          <a class="btn btn-sm btn-info" href="admin.php?act=booking-history&id=<?= $b['booking_id'] ?>">Lịch sử</a>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<?php footerAdmin(); ?>

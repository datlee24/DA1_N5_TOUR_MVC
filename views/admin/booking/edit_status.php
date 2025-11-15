<?php headerAdmin(); ?>
<h2 class="mt-4">Cập nhật trạng thái Booking #<?= $booking['booking_id'] ?></h2>

<form method="POST" action="admin.php?act=booking-edit&id=<?= $booking['booking_id'] ?>">
  <div class="mb-3">
    <label>Trạng thái hiện tại: </label>
    <strong><?= htmlspecialchars($booking['status']) ?></strong>
  </div>

  <div class="mb-3">
    <label>Chọn trạng thái mới</label>
    <select name="new_status" class="form-select">
      <option value="pending" <?= $booking['status']=='pending'?'selected':'' ?>>Chờ (pending)</option>
      <option value="confirmed" <?= $booking['status']=='confirmed'?'selected':'' ?>>Đã xác nhận (confirmed)</option>
      <option value="deposited" <?= $booking['status']=='deposited'?'selected':'' ?>>Đã cọc (deposited)</option>
      <option value="completed" <?= $booking['status']=='completed'?'selected':'' ?>>Hoàn tất (completed)</option>
      <option value="cancelled" <?= $booking['status']=='cancelled'?'selected':'' ?>>Hủy (cancelled)</option>
    </select>
  </div>

  <div class="mb-3">
    <label>Ghi chú thay đổi</label>
    <textarea name="note" class="form-control"></textarea>
  </div>

  <button class="btn btn-primary">Lưu thay đổi</button>
  <a href="admin.php?act=booking" class="btn btn-secondary">Quay lại</a>
</form>

<?php footerAdmin(); ?>

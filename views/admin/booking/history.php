<?php headerAdmin(); ?>
<h2 class="mt-4">Lịch sử thay đổi Booking <?= intval($_GET['id']) ?></h2>

<table class="table table-bordered">
  <thead>
    <tr>
      <th>Thời gian</th>
      <th>Người thay đổi</th>
      <th>Trước</th>
      <th>Sau</th>
      <th>Ghi chú</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach($rows as $r): ?>
      <tr>
        <td><?= htmlspecialchars($r['changed_at'] ?? '') ?></td>
        <td><?= htmlspecialchars($r['changed_by'] ?? '') ?></td>
        <td><?= htmlspecialchars($r['old_status'] ?? '') ?></td>
        <td><?= htmlspecialchars($r['new_status'] ?? '') ?></td>
        <td><?= htmlspecialchars($r['note'] ?? '') ?></td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<a href="admin.php?act=booking" class="btn btn-secondary">Quay lại</a>
<?php footerAdmin(); ?>

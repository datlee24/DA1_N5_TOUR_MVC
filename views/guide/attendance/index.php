<?php headerGuide(); ?>

<div class="container-fluid px-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h4>Điểm danh - Lịch #<?= htmlspecialchars($_GET['schedule_id'] ?? '') ?></h4>
    <div>
      <a href="index.php" class="btn btn-outline-dark me-2">Quay lại</a>
      <button id="btnSaveAttendance" class="btn btn-primary">Lưu điểm danh</button>
    </div>
  </div>

  <div class="card">
    <div class="card-body p-0">
      <table class="table mb-0">
        <thead class="table-light">
          <tr>
            <th>ID</th>
            <th>Họ tên</th>
            <th>SĐT</th>
            <th>Email</th>
            <th>Trạng thái</th>
            <th>Ghi chú</th>
          </tr>
        </thead>
        <tbody id="attendanceBody">
          <?php foreach ($customers as $i => $c): ?>
            <?php
              // map status
              $current = $c['status'] ?? $c['attendance_status'] ?? 'unknown';
              // normalize
              if (!in_array($current, ['present','absent','unknown'])) $current = 'unknown';
            ?>
            <tr data-tour-customer-id="<?= htmlspecialchars($c['tour_customer_id']) ?>"
                data-customer-id="<?= htmlspecialchars($c['customer_id']) ?>">

              <td><?= $i+1 ?></td>
              <td><?= htmlspecialchars($c['fullname']) ?></td>
              <td><?= htmlspecialchars($c['phone']) ?></td>
              <td><?= htmlspecialchars($c['email'] ?? '-') ?></td>
              <td>
                <button type="button" class="btn btn-sm btn-toggle-status <?= $current === 'present' ? 'btn-success' : 'btn-outline-secondary' ?>">
                    <?= $current === 'present' ? 'Có mặt' : ($current === 'absent' ? 'Vắng mặt' : 'Chưa') ?>
                </button>
              </td>
              <td>
                <input class="form-control form-control-sm note-field" value="<?= htmlspecialchars($c['note'] ?? '') ?>" placeholder="Ghi chú...">
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>

  <div class="mt-3 text-muted small">
    Lưu ý: chỉ có thể điểm danh cho các lịch đang diễn ra. Nhấn nút trạng thái để chuyển Có mặt ⇄ Vắng mặt.
  </div>
</div>

<script>
  // Toggle 1 nút: nếu khác 'present' -> set present, nếu present -> set absent
  document.querySelectorAll('.btn-toggle-status').forEach(btn => {
    btn.addEventListener('click', function() {
      const isPresent = this.classList.contains('btn-success');
      if (isPresent) {
        // from present -> absent
        this.classList.remove('btn-success');
        this.classList.add('btn-outline-secondary');
        this.textContent = 'Vắng mặt';
      } else {
        // from absent/unknown -> present
        this.classList.remove('btn-outline-secondary');
        this.classList.add('btn-success');
        this.textContent = 'Có mặt';
      }
    });
  });

  // Lưu điểm danh
  document.getElementById('btnSaveAttendance').addEventListener('click', function() {
    const rows = Array.from(document.querySelectorAll('#attendanceBody tr'));
    const items = rows.map(r => {
      const tour_customer_id = r.getAttribute('data-tour-customer-id');
      const customer_id = r.getAttribute('data-customer-id');
      const btn = r.querySelector('.btn-toggle-status');
      let status = 'unknown';
      if (btn) {
        status = btn.classList.contains('btn-success') ? 'present' : 'absent';
      }
      const note = r.querySelector('.note-field').value || null;
      return { tour_customer_id: tour_customer_id, customer_id: customer_id, status: status, note: note };
    });

    const payload = { schedule_id: '<?= htmlspecialchars($_GET['schedule_id'] ?? '') ?>', items: items };

    fetch('index.php?act=attendance-save', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(payload)
    })
    .then(r => r.json())
    .then(res => {
      if (res.success) {
        alert('Đã lưu điểm danh (' + res.saved + ' bản ghi).');
        // reload để hiện trạng thái mới (dữ liệu upsert sẽ cập nhật)
        location.reload();
      } else {
        alert('Lỗi: ' + (res.message || 'Lưu thất bại'));
      }
    })
    .catch(err => {
      console.error(err);
      alert('Lỗi kết nối');
    });
  });
</script>

<?php footerGuide(); ?>

<?php headerAdmin(); 
$step = 1; 
include __DIR__ . '/progress.php'; 
?>

<div class="card shadow-sm p-4">
  <h4>Bước 1 — Chọn Tour & (Tuỳ chọn) Khách sạn / Tài xế</h4>

  <?php if (!empty($_SESSION['error'])): ?>
    <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
  <?php endif; ?>

  <form action="admin.php?act=booking-step1-save" method="POST" class="mt-3">

    <!-- TOUR -->
    <div class="mb-3">
      <label class="form-label">Chọn Tour</label>
      <select id="selectTour" name="tour_id" class="form-select form-select-lg" required>
        <option value="">-- Chọn tour --</option>
        <?php foreach ($tours as $t): ?>
          <option value="<?= $t['tour_id'] ?>">
            <?= htmlspecialchars($t['name']) ?> — <?= number_format($t['price']) ?>₫
          </option>
        <?php endforeach; ?>
      </select>
    </div>

    <!-- HOTEL AJAX -->
    <div class="mb-3">
      <label class="form-label">Chọn khách sạn theo tour (tuỳ chọn)</label>
      <select id="selectHotel" name="hotel_id" class="form-select">
        <option value="">-- Chọn khách sạn --</option>
      </select>
      <div class="form-text">Khách sạn</div>
    </div>

    <!-- DRIVER -->
    <div class="mb-3">
      <label class="form-label">Chọn tài xế (tuỳ chọn)</label>
      <select name="driver_id" class="form-select">
        <option value="">-- Không chọn --</option>
        <?php foreach ($drivers as $d): ?>
          <option value="<?= $d['driver_id'] ?>">
            <?= htmlspecialchars($d['fullname']) ?> — <?= htmlspecialchars($d['license_plate']) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>

    <div class="d-flex justify-content-between">
      <a href="admin.php?act=booking" class="btn btn-outline-secondary">Hủy</a>
      <button class="btn btn-primary btn-lg">Tiếp tục →</button>
    </div>
  </form>
</div>

<script>
document.getElementById('selectTour').addEventListener('change', function(){
    const tid = this.value;
    const hotelSelect = document.getElementById('selectHotel');
    hotelSelect.innerHTML = '<option value="">Đang tải...</option>';

    if (!tid) { 
        hotelSelect.innerHTML = '<option value="">-- Chọn khách sạn --</option>'; 
        return; 
    }

    fetch('admin.php?act=ajax-hotels&tour_id=' + tid)
      .then(r => r.json())
      .then(res => {
        if (!res.ok) return hotelSelect.innerHTML = '<option value="">Không có khách sạn</option>';
        let html = '<option value="">-- Chọn khách sạn --</option>';
        res.data.forEach(h => {
           html += `<option value="${h.hotel_id}">
                      ${escapeHtml(h.name)} — ${escapeHtml(h.manager_name||'')}
                    </option>`;
        });
        hotelSelect.innerHTML = html;
      })
      .catch(() => hotelSelect.innerHTML = '<option value="">Lỗi tải</option>');
});

function escapeHtml(s){ 
  if(!s) return ''; 
  return s.replaceAll('&','&amp;')
          .replaceAll('<','&lt;')
          .replaceAll('>','&gt;')
          .replaceAll('"','&quot;');
}
</script>

<?php footerAdmin(); ?>

<?php headerAdmin(); ?>
<h2 class="mt-4">Tạo booking mới</h2>

<?php if(isset($_SESSION['error'])): ?>
  <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
<?php endif; ?>

<form method="POST" action="admin.php?act=booking-create">
  <div class="mb-3">
    <label>Chọn khách có sẵn (nếu có)</label>
    <select name="customer_id" class="form-select">
      <option value="">-- Tạo khách mới --</option>
      <?php foreach($customers as $c): ?>
        <option value="<?= $c['customer_id'] ?>"><?= htmlspecialchars($c['fullname']) ?> (<?= $c['phone'] ?>)</option>
      <?php endforeach; ?>
    </select>
  </div>

  <h5>Hoặc nhập khách mới</h5>
  <div class="row mb-3">
    <div class="col"><input class="form-control" name="fullname" placeholder="Họ tên"></div>
    <div class="col"><input class="form-control" name="phone" placeholder="Điện thoại"></div>
    <div class="col"><input class="form-control" name="email" placeholder="Email"></div>
  </div>

  <div class="mb-3">
    <label>Chọn lịch khởi hành</label>
    <select name="schedule_id" id="schedule_select" class="form-select" required>
      <option value="">-- Chọn lịch --</option>
      <?php foreach($schedules as $s): ?>
        <option value="<?= $s['schedule_id'] ?>" data-tour="<?= $s['tour_id'] ?>">
          <?= htmlspecialchars($s['name']) ?> (<?= $s['start_date'] ?> → <?= $s['end_date'] ?>)
        </option>
      <?php endforeach; ?>
    </select>
  </div>

  <!-- hidden tour_id -> sẽ được JS gán tự động -->
  <input type="hidden" name="tour_id" id="tour_id" value="">

  <div class="mb-3">
    <label>Số lượng người</label>
    <input type="number" name="num_people" class="form-control" min="1" value="1" required>
  </div>

  <!-- Tùy chọn: adult/children/baby -->
  <div class="row mb-3">
    <div class="col"><input type="number" name="adult" class="form-control" min="0" placeholder="Người lớn (adult)"></div>
    <div class="col"><input type="number" name="children" class="form-control" min="0" placeholder="Trẻ em (children)"></div>
    <div class="col"><input type="number" name="baby" class="form-control" min="0" placeholder="Em bé (baby)"></div>
  </div>

  <div class="mb-3">
    <label>Ghi chú</label>
    <textarea name="note" class="form-control"></textarea>
  </div>

  <button class="btn btn-success">Tạo booking</button>
</form>

<script>
document.getElementById('schedule_select').addEventListener('change', function(){
    var op = this.options[this.selectedIndex];
    var tour = op.dataset.tour || '';
    document.getElementById('tour_id').value = tour;
});
</script>

<?php footerAdmin(); ?>

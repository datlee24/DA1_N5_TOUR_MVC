<?php headerAdmin(); $step = 3; include __DIR__ . '/progress.php'; ?>

<div class="card shadow-sm p-4">
  <h4>Bước 3 — Chọn Hướng dẫn viên</h4>

  <?php if (!empty($_SESSION['error'])): ?>
    <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
  <?php endif; ?>

  <div class="mb-3">
    <input id="searchGuide" class="form-control" placeholder="Tìm HDV theo tên / SĐT / ngôn ngữ">
  </div>

  <form action="admin.php?act=booking-step3-save" method="POST" id="formStep3">
    <div id="guidesList" class="row g-3">
      <?php foreach ($guides as $g): ?>
        <div class="col-md-6">
          <div class="card p-3 <?= $g['available'] ? 'border-success' : 'border-secondary' ?>">
            <div class="d-flex justify-content-between align-items-start">
              <div>
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="guide_id" id="g-<?= $g['guide_id'] ?>" value="<?= $g['guide_id'] ?>" <?= $g['available'] ? '' : 'disabled' ?>>
                  <label class="form-check-label fw-bold ms-2" for="g-<?= $g['guide_id'] ?>">
                    <?= htmlspecialchars($g['fullname']) ?> <small class="text-muted">(<?= htmlspecialchars($g['phone']) ?>)</small>
                  </label>
                </div>
                <?php if (!empty($g['language'])): ?>
                  <div class="small text-muted ms-4">Ngôn ngữ: <?= htmlspecialchars($g['language']) ?></div>
                <?php endif; ?>
                <?php if (!empty($g['experience'])): ?>
                  <div class="small text-muted ms-4">Kinh nghiệm: <?= htmlspecialchars($g['experience']) ?></div>
                <?php endif; ?>
              </div>

              <div class="text-end small">
                <?php if ($g['available']): ?>
                  <span class="badge bg-success">Rảnh</span>
                <?php else: ?>
                  <span class="badge bg-danger">Trùng lịch</span>
                <?php endif; ?>
              </div>
            </div>

            <?php if (!empty($g['schedules'])): ?>
              <hr class="my-2">
              <div class="small text-muted">
                <strong>Lịch:</strong>
                <?php foreach ($g['schedules'] as $sc): ?>
                  <div><?= date("d/m/Y", strtotime($sc['start_date'])) ?> → <?= date("d/m/Y", strtotime($sc['end_date'])) ?></div>
                <?php endforeach; ?>
              </div>
            <?php endif; ?>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

    <div class="mt-3 d-flex justify-content-between">
      <a href="admin.php?act=booking-step2" class="btn btn-outline-secondary">← Quay lại</a>
      <button class="btn btn-primary">Tiếp tục →</button>
    </div>
  </form>
</div>

<?php footerAdmin(); ?>

<script>
document.getElementById('searchGuide').addEventListener('input', function(){
  const q = this.value.trim().toLowerCase();
  document.querySelectorAll('#guidesList .col-md-6').forEach(card => {
    card.style.display = card.innerText.toLowerCase().includes(q) ? '' : 'none';
  });
});
</script>

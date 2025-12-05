<?php headerGuide(); ?>

<div class="container-fluid px-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h4>Lịch sử dẫn tour</h4>
    <a href="index.php" class="btn btn-outline-dark">Trang chủ</a>
  </div>

  <?php if (empty($schedules)): ?>
    <div class="alert alert-info">Bạn chưa dẫn tour nào.</div>
  <?php else: ?>

  <div class="row mb-3">
    <div class="col-md-8">
      <div class="card p-3 mb-3">
        <h5>Tổng số lịch đã hoàn thành</h5>
        <p class="display-6"><?= htmlspecialchars($total_completed ?? 0) ?></p>
      </div>

      <div class="row">
        <?php foreach ($schedules as $sch): ?>
          <div class="col-md-6 mb-3">
            <div class="card p-3">
              <h5><?= htmlspecialchars($sch['tour_name'] ?? '-') ?></h5>
              <p class="mb-1"><?= htmlspecialchars($sch['start_date'] ?? '-') ?> → <?= htmlspecialchars($sch['end_date'] ?? '-') ?></p>
              <p class="mb-1">Số booking: <?= count($sch['bookings']) ?></p>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>

    <div class="col-md-4">
      <div class="card p-3 mb-3">
        <h5>Những tour dẫn nhiều nhất</h5>
        <?php if (!empty($tours)): ?>
          <ul class="list-group list-group-flush">
            <?php foreach ($tours as $t): ?>
              <li class="list-group-item d-flex justify-content-between align-items-center">
                <span><?= htmlspecialchars($t['tour_name'] ?? '-') ?></span>
                <span class="badge bg-primary rounded-pill"><?= (int)($t['count'] ?? 0) ?></span>
              </li>
            <?php endforeach; ?>
          </ul>
        <?php else: ?>
          <div class="text-muted">Chưa có tour hoàn thành.</div>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <?php endif; ?>

</div>

<?php footerGuide(); ?>

<h2>Lịch hôm nay</h2>

<?php if (empty($schedules)): ?>
    <p>Hôm nay bạn không có tour nào.</p>
<?php endif; ?>

<?php foreach ($schedules as $s): ?>
    <div class="box">
        <h3><?= $s['tour_name'] ?></h3>
        <p>Ngày đi: <?= $s['start_date'] ?></p>
        <p>Ngày về: <?= $s['end_date'] ?></p>

        <a href="?act=attendance&schedule_id=<?= $s['schedule_id'] ?>"
            class="btn btn-primary">
            Điểm danh khách hàng
        </a>
    </div>
<?php endforeach; ?>
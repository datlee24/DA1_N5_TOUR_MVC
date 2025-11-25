<?php headerAdmin(); ?>
<h2>Chi tiết Tour: <?= $tour['name'] ?></h2>

<h3>Thông tin cơ bản</h3>
<p><strong>Tên tour:</strong> <?= $tour['name'] ?></p>
<p><strong>Mô tả:</strong> <?= $tour['description'] ?></p>
<p><strong>Chính sách:</strong> <?= $tour['policy'] ?></p>
<p><strong>Nhà cung cấp:</strong> <?= $tour['supplier'] ?></p>

<?php if ($tour['image']): ?>
    <img src="upload/tours/<?= $tour['image'] ?>" width="300" style="border-radius:10px">
<?php endif; ?>

<hr>

<h3>Lịch trình</h3>
<?php foreach ($itineraries as $item): ?>
    <div style="margin-bottom:20px; padding:10px; background:#f5faff; border-radius:8px;">
        <p><strong>Hoạt động:</strong> <?= $item['title'] ?></p>
        <p><strong>Địa điểm:</strong> <?= $item['location'] ?></p>
        <p><strong>Mô tả chi tiết:</strong><?= $item['description'] ?></p>
    </div>
<?php endforeach; ?>

<hr>

<h3>Lịch khởi hành</h3>
<?php foreach ($schedules as $sch): ?>
    <div style="margin-bottom:20px; padding:10px; background:#fff2e6; border-radius:8px;">
        <p><strong>Ngày bắt đầu:</strong> <?= $sch['start_date'] ?></p>
        <p><strong>Ngày kết thúc:</strong> <?= $sch['end_date'] ?></p>
        <p><strong>Điểm tập trung:</strong> <?= $sch['meeting_point'] ?></p>
        <p><strong>Tài xế:</strong> <?= $sch['driver'] ?></p>
        <p><strong>Ghi chú:</strong> <?= $sch['notes'] ?></p>

        <h4>Hướng dẫn viên</h4>
        <?php if ($sch['guide_id']): ?>
             <p><strong>Tên:</strong> <?= htmlspecialchars($sch['guide_name'] ?? '—') ?></p>
            <p><strong>Ngôn ngữ:</strong> <?= $sch['guide_language'] ?></p>
            <p><strong>Chứng chỉ:</strong> <?= $sch['guide_certificate'] ?></p>
            <p><strong>Kinh nghiệm:</strong> <?= $sch['guide_experience'] ?></p>
            <p><strong>Chuyên môn:</strong> <?= $sch['guide_specialization'] ?></p>
        <?php else: ?>
            <p>Chưa có hướng dẫn viên</p>
        <?php endif; ?>
    </div>
<?php endforeach; ?>

<a href="admin.php?act=tour_list" style="padding:10px 15px; background:#3498db; color:white; text-decoration:none; border-radius:6px;">Quay lại danh sách</a>

<?php footerAdmin(); ?>

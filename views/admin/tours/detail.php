<?php headerAdmin(); ?>
<h2>Chi tiết Tour: <?= $tour['name'] ?></h2>

<h3>Thông tin cơ bản</h3>
<p><strong>Tên tour:</strong> <?= $tour['name'] ?></p>
<p><strong>Mô tả:</strong> <?= $tour['description'] ?></p>
<p><strong>Chính sách:</strong> <?= $tour['policy'] ?></p>
<p><strong>Giá tour/người</strong><?= number_format($tour['price'], 0, ',', '.') . ' đ' ?></p>
<p><strong>Nhà cung cấp:</strong> <?= $tour['supplier'] ?></p>

<hr>

<h3>Lịch trình tour</h3>
<?php foreach ($itineraries as $item): ?>
    <div style="margin-bottom:20px; padding:10px; background:#f5faff; border-radius:8px;">
        <p><strong>Hoạt động:</strong> <?= $item['title'] ?></p>
        <p><strong>Địa điểm:</strong> <?= $item['location'] ?></p>
        <p><strong>Mô tả chi tiết:</strong><?= $item['description'] ?></p>
    </div>
<?php endforeach; ?>

<a href="admin.php?act=tour_list" style="padding:10px 15px; background:#3498db; color:white; text-decoration:none; border-radius:6px;">Quay lại danh sách</a>

<?php footerAdmin(); ?>

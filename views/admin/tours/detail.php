

<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if(isset($_SESSION['success'])){
    echo '<p id="msg" style="padding:10px; background:#2ecc71; color:white; border-radius:5px;">'
         . $_SESSION['success'] . '</p>';
    unset($_SESSION['success']); // xoá ngay sau khi hiển thị
}

if(isset($_SESSION['error'])){
    echo '<p id="msg" style="padding:10px; background:#e74c3c; color:white; border-radius:5px;">'
         . $_SESSION['error'] . '</p>';
    unset($_SESSION['error']);
}
?>
<?php headerAdmin(); ?>
<h2>Chi tiết: <?= $tour['name'] ?></h2>

<h3>Thông tin cơ bản</h3>
<p><strong>Tên tour : </strong> <?= $tour['name'] ?></p>
<p><strong>Mô tả : </strong> <?= $tour['description'] ?></p>
<p><strong>Chính sách : </strong> <?= $tour['policy'] ?></p>
<p><strong>Giá tour/người : </strong><?= number_format($tour['price'], 0, ',', '.') . ' đ' ?></p>
<p><strong>Nhà cung cấp : </strong> <?= $tour['supplier'] ?></p>

<hr>

<h3>Lịch trình tour</h3>

<!-- Thêm lịch trình -->
<a href="admin.php?act=add_itinerary_form&tour_id=<?= $tour['tour_id'] ?>" 
   style="padding:8px 12px; background:#2ecc71; color:white; text-decoration:none; border-radius:5px; display:inline-block; margin-bottom:10px;">
   Thêm lịch trình
</a>

<?php foreach ($itineraries as $item): ?>
    <div style="position:relative; margin-bottom:20px; padding:10px; background:#f5faff; border-radius:8px;">
        <h4>Ngày : <?= $item['day_number'] ?></h4>
        <p><strong>Hoạt động : </strong><?= $item['title'] ?></p>
        <p><strong>Địa điểm : </strong><?= $item['location'] ?></p>
        <?php if ($item['time_start'] || $item['time_end']): ?>
            <p><strong>Thời gian :</strong> 
                <?= $item['time_start'] ?> - <?= $item['time_end'] ?></p>
        <?php endif; ?>
        <p><strong>Mô tả chi tiết : </strong><?= $item['description'] ?></p>

        <div style="position:absolute; top:10px; right:10px;">
            <a href="admin.php?act=edit_itinerary_form&id=<?= $item['itinerary_id'] ?>" 
               style="padding:5px 10px; background:#3498db; color:white; text-decoration:none; border-radius:5px; margin-right:5px;">
               Sửa
            </a>
            <a href="admin.php?act=delete_itinerary&id=<?= $item['itinerary_id'] ?>&tour_id=<?= $tour['tour_id'] ?>" 
               onclick="return confirm('Bạn có chắc muốn xóa lịch trình này?');"
               style="padding:5px 10px; background:#e74c3c; color:white; text-decoration:none; border-radius:5px;">
               Xóa
            </a>
        </div>
    </div>
<?php endforeach; ?>

<a href="admin.php?act=tour_list" style="padding:10px 15px; background:#3498db; color:white; text-decoration:none; border-radius:6px;">Quay lại danh sách</a>

<?php footerAdmin(); ?>
<script>
    const msg = document.getElementById('msg');
    if(msg){
        setTimeout(() => { msg.style.display = 'none'; }, 3000); // 3 giây
    }
</script>

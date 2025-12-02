

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
<p><strong>Giá tour/người : </strong><?= number_format($tour['price'], 0, ',', '.') . ' đ' ?></p>
<p><strong>Nhà cung cấp : </strong> <?= $tour['supplier'] ?></p>

<hr>
<h3>Lịch trình tour</h3>

<a href="admin.php?act=add_itinerary_form&tour_id=<?= $tour['tour_id'] ?>" 
   class="btn btn-success" style="margin-bottom:10px;">Thêm lịch trình</a>

<?php
$groupedItineraries = [];
foreach($itineraries as $item){
    $groupedItineraries[$item['day_number']][] = $item;
}

foreach($groupedItineraries as $day => $activities): ?>
    <h4>Ngày <?= $day ?></h4>
    <?php foreach($activities as $item): ?>
        <div style="margin-bottom:10px; padding:8px; border:1px solid #ddd;">
            <p><strong>Hoạt động:</strong> <?= $item['title'] ?></p>
            <p><strong>Địa điểm:</strong> <?= $item['location'] ?></p>
            <p><strong>Mô tả:</strong> <?= $item['description'] ?></p>
            <a href="admin.php?act=edit_itinerary_form&id=<?= $item['itinerary_id'] ?>" class="btn btn-primary btn-sm">Sửa</a>
            <a href="admin.php?act=delete_itinerary&id=<?= $item['itinerary_id'] ?>&tour_id=<?= $tour['tour_id'] ?>" 
               onclick="return confirm('Xóa hoạt động này?')" class="btn btn-danger btn-sm">Xóa</a>
        </div>
    <?php endforeach; ?>
<?php endforeach; ?>

<a href="admin.php?act=tour_list" class="btn btn-secondary">Quay lại danh sách</a>



<?php footerAdmin(); ?>
<script>
    const msg = document.getElementById('msg');
    if(msg){
        setTimeout(() => { msg.style.display = 'none'; }, 3000); // 3 giây
    }
</script>

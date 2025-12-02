<?php headerAdmin(); ?>
<h2>Thêm lịch trình cho Tour ID: <?= $tour_id ?></h2>

<form action="admin.php?act=add_itinerary" method="post">
    <input type="hidden" name="tour_id" value="<?= $tour_id ?>">

    <div class="mb-3">
        <label>Ngày thứ</label>
        <input type="number" name="day_number" class="form-control" required>
    </div>

    <div id="activities-container">
        <div class="activity-item" style="margin-bottom:15px; padding:10px; border:1px solid #ccc;">
            <label>Hoạt động</label>
            <input type="text" name="title[]" class="form-control" required>

            <label>Mô tả</label>
            <textarea name="description[]" class="form-control" rows="2"></textarea>

            <label>Địa điểm</label>
            <input type="text" name="location[]" class="form-control">
        </div>
    </div>

    <button type="button" id="add-activity" class="btn btn-info">Thêm hoạt động</button>
    <button class="btn btn-success">Thêm lịch trình</button>
    <a href="admin.php?act=tour_detail&id=<?= $tour_id ?>" class="btn btn-secondary">Hủy</a>
</form>

<script>
document.getElementById('add-activity').addEventListener('click', function(){
    const container = document.getElementById('activities-container');
    const div = document.createElement('div');
    div.classList.add('activity-item');
    div.style = "margin-bottom:15px; padding:10px; border:1px solid #ccc;";
    div.innerHTML = `
        <label>Hoạt động</label>
        <input type="text" name="title[]" class="form-control" required>

        <label>Mô tả</label>
        <textarea name="description[]" class="form-control" rows="2"></textarea>

        <label>Địa điểm</label>
        <input type="text" name="location[]" class="form-control">

        <button type="button" class="remove-activity btn btn-sm btn-danger" style="margin-top:5px;">Xóa</button>
    `;
    container.appendChild(div);

    div.querySelector('.remove-activity').addEventListener('click', function(){
        div.remove();
    });
});
</script>
<?php footerAdmin(); ?>

<div class="form-container">
    <h2>Sửa Lịch Trình cho Tour ID: <?= $tour_id ?></h2>
    <form action="admin.php?act=update_itinerary" method="post">
        <input type="hidden" name="itinerary_id" value="<?= $itinerary['itinerary_id'] ?>">
        <input type="hidden" name="tour_id" value="<?= $tour_id ?>">

        <div class="mb-3">
            <label>Ngày thứ</label>
            <input type="number" name="day_number" class="form-control" value="<?= $itinerary['day_number'] ?>" required>
        </div>

        <div class="mb-3">
            <label>Hoạt động</label>
            <input type="text" name="title" class="form-control" value="<?= $itinerary['title'] ?>" required>
        </div>

        <div class="mb-3">
            <label>Mô tả chi tiết</label>
            <textarea name="description" class="form-control" rows="3"><?= $itinerary['description'] ?></textarea>
        </div>

        <div class="mb-3">
            <label>Địa điểm</label>
            <input type="text" name="location" class="form-control" value="<?= $itinerary['location'] ?>">
        </div>
        <button class="btn btn-primary">Cập nhật lịch trình</button>
        <a href="admin.php?act=tour_detail&id=<?= $tour_id ?>" class="btn btn-secondary">Hủy</a>
    </form>
</div>
<style>
    body {
    font-family: Arial, sans-serif;
    background-color: #e6f2ff; /* xanh nhạt */
    margin: 0;
    padding: 0;
}

h2 {
    text-align: center;
    color: #3498db; /* xanh biển */
    margin: 20px 0;
}

/* Form container */
.form-container {
    background-color: white;
    max-width: 500px;
    margin: 0 auto 50px auto; /* căn giữa */
    padding: 25px;
    border-radius: 10px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
}

/* Nhãn và input */
.form-container label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
    color: #2c3e50;
}

.form-container input,
.form-container textarea {
    width: 100%;
    padding: 8px 10px;
    margin-bottom: 15px;
    border: 1px solid #b3d1ff;
    border-radius: 5px;
    box-sizing: border-box;
}

.form-container textarea {
    resize: vertical;
}

/* Buttons */
.form-container button {
    background-color: #3498db;
    color: white;
    border: none;
    padding: 10px 18px;
    border-radius: 6px;
    cursor: pointer;
    font-size: 14px;
    transition: 0.3s;
}

.form-container button:hover {
    background-color: #217dbb;
}

.form-container a.cancel {
    margin-left: 15px;
    color: #6c5ce7;
    text-decoration: none;
}

.form-container a.cancel:hover {
    text-decoration: underline;
}

</style>

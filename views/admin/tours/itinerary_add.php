<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm lịch trình</title>

    <!-- Flatpickr -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

</head>
<body>
    <div class="form-container">
    <h2>Thêm lịch trình tour</h2>
    <form action="admin.php?act=add_itinerary" method="post">
        <input type="hidden" name="tour_id" value="<?= $tour_id ?>">

        <div class="mb-3">
            <label>Ngày thứ</label>
            <input type="number" name="day_number" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Hoạt động</label>
            <input type="text" name="title" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Mô tả</label>
            <textarea name="description" class="form-control" rows="3"></textarea>
        </div>

        <div class="mb-3">
            <label>Địa điểm</label>
            <input type="text" name="location" class="form-control">
        </div>

        <div class="mb-3">
            <label>Giờ bắt đầu</label>
            <input type="text" id="time_start" name="time_start" class="form-control">
        </div>

        <div class="mb-3">
            <label>Giờ kết thúc</label>
            <input type="text" id="time_end" name="time_end" class="form-control">
        </div>

        <button class="btn btn-success">Thêm lịch trình</button>
        <a href="admin.php?act=tour_detail&id=<?= $tour_id ?>" class="btn btn-secondary">Hủy</a>
    </form>
</div>

<script>
flatpickr("#time_start", {
    enableTime: true,
    noCalendar: true,
    dateFormat: "H:i",   // 24h format
    time_24hr: true
});

flatpickr("#time_end", {
    enableTime: true,
    noCalendar: true,
    dateFormat: "H:i",   // 24h format
    time_24hr: true
});

</script>

<style>
    body {
    font-family: Arial, sans-serif;
    background-color: #e6f2ff;
    margin: 0;
    padding: 0;
}

h2 {
    text-align: center;
    color: #3498db;
    margin: 20px 0;
}

.form-container {
    background-color: white;
    max-width: 500px;
    margin: 0 auto 50px auto;
    padding: 25px;
    border-radius: 10px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
}

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

.form-container button {
    background-color: #3498db;
    color: white;
    border: none;
    padding: 10px 18px;
    border-radius: 6px;
    cursor: pointer;
    font-size: 14px;
}

.form-container button:hover {
    background-color: #217dbb;
}
</style>

</bod

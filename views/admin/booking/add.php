<h2>Tạo Booking</h2>

<form method="POST" action="admin.php?act=booking-store">

    <label>Chọn Tour</label>
    <select name="tour_id" class="form-control" required>
        <?php foreach ($tours as $t): ?>
        <option value="<?= $t['tour_id'] ?>"><?= $t['name'] ?></option>
        <?php endforeach; ?>
    </select>

    <label>Lịch Khởi Hành</label>
    <select name="schedule_id" class="form-control" required>
        <?php 
        $conn = connectDB();
        $ds = $conn->query("SELECT * FROM departure_schedule")->fetchAll();
        foreach ($ds as $s):
        ?>
        <option value="<?= $s['schedule_id'] ?>">
            <?= $s['start_date'] . " → " . $s['end_date'] ?>
        </option>
        <?php endforeach; ?>
    </select>

    <label>Hướng dẫn viên</label>
    <select name="guide_id" class="form-control">
        <option value="">-- Không chọn --</option>
        <?php foreach ($guides as $g): ?>
        <option value="<?= $g['guide_id'] ?>"><?= $g['guide_name'] ?></option>
        <?php endforeach; ?>
    </select>

    <label>Chọn khách (nhiều)</label>
    <select name="customer_ids[]" multiple class="form-control">
        <?php foreach ($customers as $c): ?>
        <option value="<?= $c['customer_id'] ?>"><?= $c['fullname'] ?></option>
        <?php endforeach; ?>
    </select>

    <label>Số người</label>
    <input type="number" name="num_people" class="form-control">

    <button class="btn btn-success mt-3">Tạo Booking</button>

</form>


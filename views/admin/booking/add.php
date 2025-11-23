<?php headerAdmin(); ?>

<h2 class="mt-4 mb-4">Tạo Booking</h2>

<form method="POST" action="admin.php?act=booking-store">

<div class="row">
    <div class="col-md-6">

        <!-- TOUR -->
        <label class="fw-bold">Chọn Tour</label>
        <select name="tour_id" id="tourSelect" class="form-select" required>
            <option value="">-- Chọn tour --</option>
            <?php foreach ($tours as $t): ?>
            <option value="<?= $t['tour_id'] ?>"><?= $t['name'] ?></option>
            <?php endforeach; ?>
        </select>

        <!-- SCHEDULE -->
        <label class="mt-3 fw-bold">Lịch khởi hành</label>
        <select name="schedule_id" id="scheduleSelect" class="form-select" required>
            <option value="">-- Chọn tour trước --</option>
        </select>

        <!-- GUIDE -->
        <label class="mt-3 fw-bold">Hướng dẫn viên</label>
        <select name="guide_id" class="form-select">
            <option value="">-- Không chọn --</option>
            <?php foreach ($guides as $g): ?>
            <option value="<?= $g['guide_id'] ?>"><?= $g['guide_name'] ?></option>
            <?php endforeach; ?>
        </select>

    </div>

    <div class="col-md-6">

        <!-- CUSTOMERS -->
        <label class="fw-bold">Chọn khách (nhiều)</label>
        <select name="customer_ids[]" multiple class="form-select" size="6">
            <?php foreach ($customers as $c): ?>
            <option value="<?= $c['customer_id'] ?>"><?= $c['fullname'] ?></option>
            <?php endforeach; ?>
        </select>

        <!-- NUM PEOPLE -->
        <label class="mt-3 fw-bold">Số người</label>
        <input type="number" name="num_people" id="numPeople" class="form-control" required>

        <!-- AUTO PRICE -->
        <label class="mt-3 fw-bold">Tổng tiền</label>
        <input type="text" name="total_price" id="totalPrice" class="form-control" readonly>

    </div>
</div>

<button class="btn btn-success mt-4">Tạo Booking</button>
</form>

<script>
// AJAX LOAD SCHEDULE
document.getElementById("tourSelect").addEventListener("change", function () {
    let id = this.value;
    fetch("admin.php?act=get-schedule&tour_id=" + id)
        .then(r => r.json())
        .then(list => {
            let html = "";
            list.forEach(s => {
                html += `<option value="${s.schedule_id}">
                            ${s.start_date} → ${s.end_date}
                         </option>`;
            });
            document.getElementById("scheduleSelect").innerHTML = html;
        });
});

// AUTO CALCULATE TOTAL PRICE
document.getElementById("numPeople").addEventListener("keyup", function () {
    let num = this.value;
    let price = 1500000; // giá mẫu
    document.getElementById("totalPrice").value = (num * price).toLocaleString();
});
</script>

<?php footerAdmin(); ?>

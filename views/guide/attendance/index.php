<?php headerGuide(); ?>

<div class="container-fluid px-4">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>Điểm danh lịch #<?= htmlspecialchars($_GET['schedule_id'] ?? '') ?></h4>

        <div>
            <a href="index.php?act=schedule-detail&schedule_id=<?= htmlspecialchars($_GET['schedule_id'] ?? '') ?>" 
               class="btn btn-outline-dark me-2">Quay lại</a>

            <button id="btnSaveAttendance" class="btn btn-primary">
                💾 Lưu điểm danh
            </button>
        </div>
    </div>

    <!-- KHU VỰC THÔNG BÁO -->
    <div id="alertBox"></div>

    <div class="card">
        <div class="card-body p-0">
            <table class="table mb-0">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Họ tên</th>
                        <th>SĐT</th>
                        <th>Email</th>
                        <th>Trạng thái</th>
                        <th>Ghi chú</th>
                    </tr>
                </thead>

                <tbody id="attendanceBody">

                <?php foreach ($customers as $i => $c): ?>

                    <?php
                        // Only two states: 'present' or 'absent'. Default to 'absent'.
                        $current = $c['status'] ?? $c['attendance_status'] ?? 'absent';
                        if ($current !== 'present') $current = 'absent';
                    ?>

                    <tr data-tour-customer-id="<?= $c['tour_customer_id'] ?>"
                        data-customer-id="<?= $c['customer_id'] ?>">

                        <td><?= $i+1 ?></td>
                        <td><?= htmlspecialchars($c['fullname']) ?></td>
                        <td><?= htmlspecialchars($c['phone']) ?></td>
                        <td><?= htmlspecialchars($c['email'] ?? '-') ?></td>

                        <td>
                            <button type="button" class="btn btn-sm btn-toggle-status <?= $current === 'present' ? 'btn-success' : 'btn-outline-secondary' ?>">
                                <?= $current === 'present' ? 'Có mặt' : 'Vắng mặt' ?>
                            </button>
                        </td>

                        <td>
                            <input class="form-control form-control-sm note-field"
                                   value="<?= htmlspecialchars($c['note'] ?? '') ?>"
                                   placeholder="Ghi chú...">
                        </td>
                    </tr>

                <?php endforeach; ?>

                </tbody>

            </table>
        </div>
    </div>

</div>


<script>
document.getElementById('btnSaveAttendance').addEventListener('click', function () {

    const rows = [...document.querySelectorAll('#attendanceBody tr')];

    // Build items: read status from toggle button (present <-> absent)
    const items = rows.map(r => {
        const btn = r.querySelector('.btn-toggle-status');
        const status = btn && btn.classList.contains('btn-success') ? 'present' : 'absent';
        return {
            tour_customer_id: r.dataset.tourCustomerId,
            customer_id: r.dataset.customerId,
            status: status,
            note: r.querySelector('.note-field').value || null
        };
    });

    const payload = {
        schedule_id: "<?= htmlspecialchars($_GET['schedule_id'] ?? '') ?>",
        items: items
    };

    fetch("index.php?act=attendance-save", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(payload)
    })
    .then(res => res.json())
    .then(data => {
        const box = document.getElementById("alertBox");

        if (data.success) {
            box.innerHTML = `
                <div class="alert alert-success mt-3">
                    ✔ Đã điểm danh thành công
                </div>
            `;
        } else {
            box.innerHTML = `
                <div class="alert alert-danger mt-3">
                    ❌ Lỗi: ${data.message}
                </div>
            `;
        }
    })
    .catch(err => {
        document.getElementById("alertBox").innerHTML = `
            <div class="alert alert-danger mt-3">
                ❌ Lỗi kết nối server.
            </div>`;
    });
});

// Attach toggle handlers to buttons (for present <-> absent)
document.querySelectorAll('.btn-toggle-status').forEach(btn => {
    btn.addEventListener('click', function () {
        const isPresent = this.classList.contains('btn-success');
        if (isPresent) {
            this.classList.remove('btn-success');
            this.classList.add('btn-outline-secondary');
            this.textContent = 'Vắng mặt';
        } else {
            this.classList.remove('btn-outline-secondary');
            this.classList.add('btn-success');
            this.textContent = 'Có mặt';
        }
    });
});
</script>

<?php footerGuide(); ?>

<h2>Điểm danh khách hàng</h2>

<table border="1" cellpadding="10" id="att-table">
    <tr>
        <th>Tên khách</th>
        <th>SĐT</th>
        <th>Phòng</th>
        <th>Trạng thái</th>
        <th>Ghi chú</th>
    </tr>

    <?php foreach ($data as $row): ?>
        <tr data-tc="<?= $row['tour_customer_id'] ?>" data-cid="<?= $row['customer_id'] ?>">
            <td><?= $row['fullname'] ?></td>
            <td><?= $row['phone'] ?></td>
            <td><?= $row['room_number'] ?></td>

            <td>
                <select class="status">
                    <option value="present" <?= $row['status'] == 'present' ? 'selected' : '' ?>>Có mặt</option>
                    <option value="absent" <?= $row['status'] == 'absent' ? 'selected' : '' ?>>Vắng</option>
                    <option value="unknown">?</option>
                </select>
            </td>

            <td>
                <input type="text" class="note" value="<?= $row['note'] ?>">
            </td>
        </tr>
    <?php endforeach; ?>
</table>

<br>
<button id="saveBtn">Lưu điểm danh</button>

<script>
    document.getElementById("saveBtn").onclick = function() {
        let rows = document.querySelectorAll("#att-table tr[data-tc]");
        let items = [];
        rows.forEach(r => {
            items.push({
                tour_customer_id: r.dataset.tc,
                customer_id: r.dataset.cid,
                status: r.querySelector(".status").value,
                note: r.querySelector(".note").value
            });
        });

        fetch("?act=attendance-save", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({
                    schedule_id: <?= $_GET['schedule_id'] ?>,
                    items: items
                })
            })
            .then(r => r.json())
            .then(res => {
                alert("Đã lưu " + res.saved + " khách!");
            });
    };
</script>
<?php
// views/admin/attendance/list.php
?>

<h2>Danh sách điểm danh</h2>

<form method="get" class="mb-3">
    <input type="hidden" name="act" value="attendance">
    <div>
        <label>Schedule ID: <input type="text" name="schedule_id" value="<?= htmlspecialchars($_GET['schedule_id'] ?? '') ?>"></label>
        <label>Guide ID: <input type="text" name="guide_id" value="<?= htmlspecialchars($_GET['guide_id'] ?? '') ?>"></label>
        <label>Customer ID: <input type="text" name="customer_id" value="<?= htmlspecialchars($_GET['customer_id'] ?? '') ?>"></label>
        <label>Date: <input type="date" name="date" value="<?= htmlspecialchars($_GET['date'] ?? '') ?>"></label>
        <button type="submit">Lọc</button>
    </div>
</form>

<table border="1" cellpadding="8">
    <tr>
        <th>Thời gian</th>
        <th>Tour / Schedule</th>
        <th>Khách</th>
        <th>Guide</th>
        <th>Trạng thái</th>
        <th>Ghi chú</th>
    </tr>
    <?php foreach ($data as $r): ?>
        <tr>
            <td><?= htmlspecialchars($r['marked_at']) ?></td>
            <td><?= htmlspecialchars($r['tour_name'] ?? '-') ?> (ID: <?= htmlspecialchars($r['schedule_id']) ?>)</td>
            <td><?= htmlspecialchars($r['customer_name'] ?? '-') ?> (ID: <?= htmlspecialchars($r['customer_id']) ?>)</td>
            <td><?= htmlspecialchars($r['guide_name'] ?? '-') ?> (ID: <?= htmlspecialchars($r['guide_id']) ?>)</td>
            <td><?= htmlspecialchars($r['status']) ?></td>
            <td><?= nl2br(htmlspecialchars($r['note'])) ?></td>
        </tr>
    <?php endforeach; ?>
</table>
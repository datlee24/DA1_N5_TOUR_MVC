<h2>Tour của tôi trong tháng</h2>

<table border="1" cellpadding="10">
    <tr>
        <th>Ngày đi</th>
        <th>Ngày về</th>
        <th>Tour</th>
        <th>Điểm danh</th>
    </tr>

    <?php foreach ($schedules as $s): ?>
        <tr>
            <td><?= $s['start_date'] ?></td>
            <td><?= $s['end_date'] ?></td>
            <td><?= $s['tour_name'] ?></td>
            <td>
                <a href="/?url=guide/attendance/index&schedule_id=<?= $s['schedule_id'] ?>">
                    Điểm danh
                </a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

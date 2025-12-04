<h2>Lịch làm việc của tôi</h2>

<table border="1" cellpadding="10">
    <tr>
        <th>Ngày</th>
        <th>Tour</th>
    </tr>

    <?php foreach ($schedules as $s): ?>
        <tr>
            <td><?= $s['start_date'] ?></td>
            <td>
                <?= $s['tour_name'] ?>
                <a href="/?url=guide/today&date=<?= $s['start_date'] ?>">Xem chi tiết</a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

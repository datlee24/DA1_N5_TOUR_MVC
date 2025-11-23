<h2>Danh sách Booking</h2>
<a href="admin.php?act=booking-add" class="btn btn-primary mb-3">Tạo Booking</a>

<table class="table table-bordered">
    <tr>
        <th>ID</th>
        <th>Tour</th>
        <th>Khách đại diện</th>
        <th>Ngày đi</th>
        <th>Số người</th>
        <th>Tình trạng</th>
    </tr>

    <?php foreach ($bookings as $b): ?>
    <tr>
        <td><?= $b['booking_id'] ?></td>
        <td><?= $b['tour_name'] ?></td>
        <td><?= $b['customer_name'] ?></td>
        <td><?= $b['start_date'] ?></td>
        <td><?= $b['num_people'] ?></td>
        <td><?= $b['status'] ?></td>
    </tr>
    <?php endforeach; ?>
</table>

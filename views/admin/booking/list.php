<?php headerAdmin(); ?>

<h2 class="mt-4 mb-4">Danh sách Booking</h2>

<a href="admin.php?act=booking-step1" class="btn btn-primary mb-3">
    <i class="fa fa-plus"></i> Tạo Booking
</a>

<table class="table table-bordered table-striped table-hover align-middle">
    <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Tour</th>
            <th>Ngày đi</th>
            <th>Ngày về</th>
            <th>Số người</th>
            <th>Thanh toán</th>
            <th>Trạng thái</th>
            <th width="130">Hành động</th>
        </tr>
    </thead>

    <tbody>
        <?php foreach ($bookings as $b): ?>
        <tr>
            <td><b>#<?= $b['booking_id'] ?></b></td>

            <td><?= $b['tour_name'] ?></td>

            <!-- NGÀY ĐI -->
            <td>
                <span class="badge bg-info text-dark">
                    <?= date("d/m/Y", strtotime($b['start_date'])) ?>
                </span>
            </td>

            <!-- NGÀY VỀ -->
            <td>
                <span class="badge bg-primary">
                    <?= date("d/m/Y", strtotime($b['end_date'])) ?>
                </span>
            </td>

            <td>
                <?php if ($b['num_people'] >= 35): ?>
                    <span class="badge bg-danger"><?= $b['num_people'] ?>/40</span>
                <?php else: ?>
                    <span class="badge bg-success"><?= $b['num_people'] ?>/40</span>
                <?php endif; ?>
            </td>

            <td>
                <?= $b['payment_status'] === 'paid'
                    ? '<span class="badge bg-success">Đã thanh toán</span>'
                    : '<span class="badge bg-warning text-dark">Chưa thanh toán</span>' ?>
            </td>

            <td>
                <?= $b['status'] === 'confirmed'
                    ? '<span class="badge bg-primary">Đã xác nhận</span>'
                    : ($b['status'] === 'cancelled'
                        ? '<span class="badge bg-danger">Đã hủy</span>'
                        : '<span class="badge bg-secondary">Khác</span>') ?>
            </td>

            <td>
                <a href="admin.php?act=booking-view&id=<?= $b['booking_id'] ?>"
                   class="btn btn-sm btn-info text-white mb-1">Xem</a>

                <?php if ($b['status'] !== 'cancelled'): ?>
                    <a onclick="return confirm('Bạn chắc chắn muốn hủy booking này?')"
                       href="admin.php?act=booking-cancel&id=<?= $b['booking_id'] ?>"
                       class="btn btn-sm btn-danger">Hủy</a>
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php footerAdmin(); ?>

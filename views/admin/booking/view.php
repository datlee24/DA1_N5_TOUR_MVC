<?php headerAdmin(); ?>
<div class="container-fluid px-4">
    <h4 class="mt-4">Chi tiết Booking</h4>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="admin.php?act=booking">Quản lý Booking</a></li>
        <li class="breadcrumb-item active">Chi tiết</li>
    </ol>

    <div class="row">
        <div class="col-md-6">
            <div class="card shadow-sm mb-3">
                <div class="card-header bg-primary text-white">
                    <strong>Thông tin Booking</strong>
                </div>
                <div class="card-body">
                    <p><strong>Mã booking:</strong> #<?= htmlspecialchars($booking['booking_id']) ?></p>
                    <p><strong>Tour:</strong> <?= htmlspecialchars($booking['tour_name']) ?></p>

                    <p><strong>Lịch khởi hành:</strong>
                        <?= date("d/m/Y", strtotime($booking['start_date'])) ?> → <?= date("d/m/Y", strtotime($booking['end_date'])) ?>
                    </p>

                    <p><strong>Số lượng khách:</strong> <?= htmlspecialchars($booking['num_people']) ?></p>

                    <p><strong>Tổng tiền:</strong>
                        <span class="text-danger fw-bold fs-5">
                            <?= number_format($booking['total_price']) ?>₫
                        </span>
                    </p>

                    <p><strong>Trạng thái:</strong>
                        <?php if ($booking['status'] == "confirmed"): ?>
                            <span class="badge bg-success">Đã xác nhận</span>
                        <?php elseif ($booking['status'] == 'cancelled'): ?>
                            <span class="badge bg-danger">Đã hủy</span>
                        <?php else: ?>
                            <span class="badge bg-secondary">Chờ xử lý</span>
                        <?php endif; ?>
                    </p>

                    <p><strong>Thanh toán:</strong>
                        <?php if ($booking['payment_status'] == "paid"): ?>
                            <span class="badge bg-primary">Đã thanh toán</span>
                        <?php else: ?>
                            <span class="badge bg-warning text-dark"><?= ucfirst($booking['payment_status']) ?></span>
                        <?php endif; ?>
                    </p>

                    <p><strong>Ghi chú:</strong><br>
                        <?= nl2br(htmlspecialchars($booking['note'] ?? '')) ?>
                    </p>

                    <div class="mt-3">
                        <?php if ($booking['status'] !== 'confirmed'): ?>
                            <a href="admin.php?act=booking-confirm&id=<?= $booking['booking_id'] ?>" class="btn btn-success">Xác nhận</a>
                        <?php endif; ?>

                        <?php if ($booking['status'] !== 'cancelled'): ?>
                            <a onclick="return confirm('Bạn chắc chắn muốn hủy booking này?')" href="admin.php?act=booking-cancel&id=<?= $booking['booking_id'] ?>" class="btn btn-danger">Hủy</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- HDV -->
        <div class="col-md-6">
            <div class="card shadow-sm mb-3">
                <div class="card-header bg-info text-white">
                    <strong>Hướng dẫn viên</strong>
                </div>
                <div class="card-body">
                    <p><strong>Tên HDV:</strong> <?= htmlspecialchars($guide['fullname'] ?? "Chưa phân công") ?></p>
                    <p><strong>SĐT:</strong> <?= htmlspecialchars($guide['phone'] ?? "-") ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Danh sách khách -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-secondary text-white">
            <strong>Danh sách khách tham gia</strong>
        </div>
        <div class="card-body p-0">
            <table class="table table-bordered mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Họ tên</th>
                        <th>SĐT</th>
                        <th>Email</th>
                        <th>Phòng</th>
                        <th>Check-in</th>
                    </tr>
                </thead>
                <tbody>
                <?php $i=1; foreach ($customers as $cus): ?>
                    <tr>
                        <td><?= $i++ ?></td>
                        <td><?= htmlspecialchars($cus['fullname']) ?></td>
                        <td><?= htmlspecialchars($cus['phone']) ?></td>
                        <td><?= htmlspecialchars($cus['email']) ?></td>
                        <td><?= htmlspecialchars($cus['room_number'] ?? '-') ?></td>
                        <td>
                            <?php if (!empty($cus['checkin_status']) && $cus['checkin_status'] === 'checked_in'): ?>
                                <span class="badge bg-success">Đã nhận phòng</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">Chưa nhận</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <a href="admin.php?act=booking" class="btn btn-dark">Quay lại</a>
</div>
<?php footerAdmin(); ?>

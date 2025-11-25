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
                    <p><strong>Mã booking:</strong> #<?= $booking['booking_id'] ?></p>
                    <p><strong>Tour:</strong> <?= $booking['tour_name'] ?></p>

                    <p><strong>Lịch khởi hành:</strong>
                        <?= date("d/m/Y", strtotime($booking['start_date'])) ?> →
                        <?= date("d/m/Y", strtotime($booking['end_date'])) ?>
                    </p>

                    <p><strong>Số lượng khách:</strong> <?= $booking['num_people'] ?></p>

                    <p><strong>Tổng tiền:</strong>
                        <span class="text-danger fw-bold fs-5">
                            <?= number_format($booking['total_price']) ?>₫
                        </span>
                    </p>

                    <p><strong>Trạng thái:</strong>
                        <?php if ($booking['status'] == "confirmed"): ?>
                            <span class="badge bg-success">Đã xác nhận</span>
                        <?php else: ?>
                            <span class="badge bg-danger">Đã hủy</span>
                        <?php endif; ?>
                    </p>

                    <p><strong>Thanh toán:</strong>
                        <?php if ($booking['payment_status'] == "paid"): ?>
                            <span class="badge bg-primary">Đã thanh toán</span>
                        <?php else: ?>
                            <span class="badge bg-warning text-dark">Chưa thanh toán</span>
                        <?php endif; ?>
                    </p>
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
                    <p><strong>Tên HDV:</strong> <?= $booking['guide_name'] ?? "Chưa phân công" ?></p>
                    <p><strong>SĐT:</strong> <?= $booking['guide_phone'] ?? "-" ?></p>
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
                    </tr>
                </thead>
                <tbody>
                <?php $i=1; foreach ($customers as $cus): ?>
                    <tr>
                        <td><?= $i++ ?></td>
                        <td><?= $cus['fullname'] ?></td>
                        <td><?= $cus['phone'] ?></td>
                        <td><?= $cus['email'] ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <a href="admin.php?act=booking" class="btn btn-dark">Quay lại</a>
</div>
<?php footerAdmin(); ?>

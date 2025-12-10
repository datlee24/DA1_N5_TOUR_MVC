<?php 
$pageTitle = "Chi tiết lịch làm việc";
headerGuide(); 
?>

<div class="container-fluid px-4">

    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>Chi tiết lịch làm việc #<?= htmlspecialchars($schedule['schedule_id']) ?></h4>

        <div>
            <a href="index.php?act=today" class="btn btn-outline-dark me-2">← Quay lại</a>

            <?php if ($schedule['status_text'] === 'ongoing'): ?>
                <a href="index.php?act=attendance&schedule_id=<?= $schedule['schedule_id'] ?>"
                   class="btn btn-success">
                    ✔ Điểm danh
                </a>
            <?php else: ?>
                <button class="btn btn-secondary" disabled>
                    ⛔ Điểm danh (chỉ khi đang diễn ra)
                </button>
            <?php endif; ?>
        </div>
    </div>

    <!-- THÔNG TIN LỊCH -->
    <div class="row g-3">

        <div class="col-md-6">

            <!-- CARD: THÔNG TIN TOUR -->
            <div class="card shadow-sm mb-3">
                <div class="card-header bg-primary text-white">Thông tin lịch</div>
                <div class="card-body">

                    <p><strong>Tour:</strong> 
                        <?= htmlspecialchars($schedule['tour_name'] ?? '-') ?>
                    </p>

                    <p><strong>Ngày đi:</strong> 
                        <?= date("d/m/Y", strtotime($schedule['start_date'])) ?>
                    </p>

                    <p><strong>Ngày về:</strong> 
                        <?= date("d/m/Y", strtotime($schedule['end_date'])) ?>
                    </p>

                    <p><strong>Số khách:</strong> 
                        <?= (int)($schedule['total_customers'] ?? 0) ?> khách
                    </p>

                    <p><strong>Trạng thái lịch:</strong>
                        <?php if ($schedule['status_text'] === 'ongoing'): ?>
                            <span class="badge bg-success">Đang diễn ra</span>
                        <?php elseif ($schedule['status_text'] === 'upcoming'): ?>
                            <span class="badge bg-warning text-dark">Sắp diễn ra</span>
                        <?php else: ?>
                            <span class="badge bg-secondary">Đã kết thúc</span>
                        <?php endif; ?>
                    </p>

                    <p><strong>Ghi chú:</strong><br>
                        <?= nl2br(htmlspecialchars($schedule['notes'] ?? 'Không có')) ?>
                    </p>

                </div>
            </div>

            <!-- CARD: TÀI XẾ -->
            <div class="card shadow-sm mb-3">
                <div class="card-header bg-info text-white">Tài xế</div>

                <div class="card-body">
                    <?php if (!empty($schedule['driver_name'])): ?>

                        <p><strong>Họ tên:</strong> <?= htmlspecialchars($schedule['driver_name']) ?></p>
                        <p><strong>SĐT:</strong> <?= htmlspecialchars($schedule['driver_phone'] ?? '-') ?></p>
                        <p><strong>Loại xe:</strong> <?= htmlspecialchars($schedule['vehicle_type'] ?? '-') ?></p>
                        <p><strong>Biển số:</strong> <?= htmlspecialchars($schedule['license_plate'] ?? '-') ?></p>

                    <?php else: ?>
                        <p class="text-muted">Chưa phân tài xế.</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- CARD: KHÁCH SẠN -->
            <div class="card shadow-sm mb-3">
                <div class="card-header bg-warning">Khách sạn</div>

                <div class="card-body">
                    <?php if (!empty($schedule['hotel_name'])): ?>

                        <p><strong>Tên KS:</strong> <?= htmlspecialchars($schedule['hotel_name']) ?></p>
                        <p><strong>Địa chỉ:</strong> <?= htmlspecialchars($schedule['hotel_address'] ?? '-') ?></p>
                        <p><strong>Quản lý:</strong> <?= htmlspecialchars($schedule['manager_name'] ?? '-') ?></p>
                        <p><strong>SĐT quản lý:</strong> <?= htmlspecialchars($schedule['manager_phone'] ?? '-') ?></p>

                    <?php else: ?>
                        <p class="text-muted">Chưa gán khách sạn.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- DANH SÁCH KHÁCH -->
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white">Danh sách khách</div>

                <div class="card-body p-0">

                    <table class="table table-bordered mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Họ tên</th>
                                <th>SĐT</th>
                                <th>Email</th>
                                <th>Điểm danh</th>
                                <th>Ghi chú</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php foreach ($customers as $i => $c): ?>

                                <?php
                                    $status = $c['attendance_status'] ?? 'unknown';

                                    $status_text = [
                                        'present' => 'Có mặt',
                                        'absent'  => 'Vắng mặt',
                                        'unknown' => 'Chưa'
                                    ][$status] ?? 'Chưa';

                                    $badge_color = [
                                        'present' => 'success',
                                        'absent'  => 'danger',
                                        'unknown' => 'secondary'
                                    ][$status] ?? 'secondary';
                                ?>

                                <tr>
                                    <td><?= $i+1 ?></td>

                                    <td><?= htmlspecialchars($c['fullname']) ?></td>
                                    <td><?= htmlspecialchars($c['phone']) ?></td>
                                    <td><?= htmlspecialchars($c['email'] ?? '-') ?></td>

                                    <td>
                                        <span class="badge bg-<?= $badge_color ?>">
                                            <?= $status_text ?>
                                        </span>
                                    </td>

                                    <td><?= htmlspecialchars($c['note'] ?? '-') ?></td>
                                </tr>

                            <?php endforeach; ?>
                        </tbody>
                    </table>

                </div>
            </div>
        </div>

    </div>

</div>

<?php footerGuide(); ?>

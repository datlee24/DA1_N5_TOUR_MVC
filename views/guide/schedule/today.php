<?php 
$pageTitle = "Lịch hôm nay";

// Gọi header
headerGuide();
?>

<div class="container">

    <h2 class="mb-4">📅 Lịch làm việc hôm nay</h2>

    <?php if (empty($schedules)): ?>
        <div class="alert alert-info">Hôm nay bạn không có tour nào.</div>
    <?php endif; ?>

    <div class="row">
        <?php foreach ($schedules as $s): 
            $tour_name = htmlspecialchars($s['tour_name'] ?? '-');
            $start_date = htmlspecialchars($s['start_date'] ?? '-');
            $end_date = htmlspecialchars($s['end_date'] ?? '-');
            $hotel_name = htmlspecialchars($s['hotel_name'] ?? '');
            $hotel_address = htmlspecialchars($s['hotel_address'] ?? '—');
            $driver_name = htmlspecialchars($s['driver_name'] ?? 'Chưa phân');
            $driver_phone = htmlspecialchars($s['driver_phone'] ?? '—');
            $vehicle_type = htmlspecialchars($s['vehicle_type'] ?? '—');
            $license_plate = htmlspecialchars($s['license_plate'] ?? '—');
            $total_customers = (int)($s['total_customers'] ?? 0);
            $schedule_status = $s['schedule_status'] ?? '';
            $schedule_id = htmlspecialchars($s['schedule_id'] ?? '');
        ?>
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm border-0 rounded-3 p-3">

                    <h4 class="fw-bold text-primary"><?= $tour_name ?></h4>

                    <p class="mb-1"><b>Ngày đi:</b> <?= $start_date ?></p>
                    <p class="mb-1"><b>Ngày về:</b> <?= $end_date ?></p>

                    <hr>

                    <p class="mb-1">
                        <b>Khách sạn:</b>
                        <?= !empty($hotel_name) ? $hotel_name : '<span class="text-danger">Chưa phân</span>' ?>
                    </p>

                    <p class="mb-1">
                        <b>Địa chỉ:</b>
                        <?= $hotel_address ?>
                    </p>

                    <hr>

                    <p><b>Tài xế:</b> 
                        <?= $driver_name ?>
                    </p>

                    <p class="mb-1">
                        <b>SĐT:</b> <?= $driver_phone ?></p>

                    <p class="mb-1">
                        <b>Loại xe:</b> <?= $vehicle_type ?></p>

                    <p class="mb-3">
                        <b>Biển số:</b> <?= $license_plate ?></p>

                    <hr>

                    <p><b>Tổng khách:</b> <?= $total_customers ?></p>

                    <p>
                        <b>Trạng thái:</b>
                        <?php
                            if ($schedule_status === "ongoing") echo '<span class="badge bg-success">Đang diễn ra</span>';
                            elseif ($schedule_status === "upcoming") echo '<span class="badge bg-warning text-dark">Sắp diễn ra</span>';
                            else echo '<span class="badge bg-secondary">Đã kết thúc</span>';
                        ?>
                    </p>

                    <div class="d-flex gap-2 mt-3">

                        <a href="index.php?act=schedule-detail&schedule_id=<?= $schedule_id ?>"
                            class="btn btn-outline-primary btn-sm">
                            🔍 Xem chi tiết
                        </a>
                        <a href="index.php" class="btn btn-dark btn-sm">⬅ Quay lại</a>

                    </div>

                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php 
// Gọi footer
footerGuide();
?>

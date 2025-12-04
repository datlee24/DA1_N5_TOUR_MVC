<?php headerGuide() ?>

<style>
    .tour-row:hover {
        background: #f7faff !important;
        cursor: pointer;
    }
    .status-badge {
        padding: 6px 12px;
        border-radius: 30px;
        font-size: .85rem;
        font-weight: 600;
    }
    .card-title {
        font-weight: 700;
        font-size: 1.4rem;
    }
    .card {
        border-radius: 12px;
    }
    .action-btn {
        padding: 6px 12px;
        border-radius: 6px;
        font-size: .85rem;
        font-weight: 600;
    }
</style>

<div class="container mt-4">

    <h2 class="mb-4 fw-bold">
        🧭 Tour của tôi trong tháng
    </h2>

    <!-- Bộ lọc tháng / năm -->
    <div class="card p-3 shadow-sm mb-4">
        <form method="get" class="row g-2 align-items-end">
            <input type="hidden" name="act" value="my-tours">

            <div class="col-md-3">
                <label class="form-label fw-semibold">Chọn tháng</label>
                <select name="month" class="form-select">
                    <?php for($m=1;$m<=12;$m++): ?>
                        <option value="<?= $m ?>" <?= $m==$month?'selected':'' ?>>
                            Tháng <?= $m ?>
                        </option>
                    <?php endfor; ?>
                </select>
            </div>

            <div class="col-md-3">
                <label class="form-label fw-semibold">Chọn năm</label>
                <select name="year" class="form-select">
                    <?php for($y=date("Y")-1;$y<=date("Y")+1;$y++): ?>
                        <option value="<?= $y ?>" <?= $y==$year?'selected':'' ?>><?= $y ?></option>
                    <?php endfor; ?>
                </select>
            </div>

            <div class="col-md-3">
                <button class="btn btn-primary w-100">🔎 Xem lịch</button>
            </div>
        </form>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">

            <table class="table table-hover mb-0 align-middle">
                <thead class="table-dark">
                    <tr>
                        <th style="width: 40px;">#</th>
                        <th>Tour</th>
                        <th>Ngày đi</th>
                        <th>Ngày về</th>
                        <th>Khách</th>
                        <th>Tài xế</th>
                        <th>Khách sạn</th>
                        <th>Trạng thái</th>
                        <th style="width: 120px;">Hành động</th>
                    </tr>
                </thead>

                <tbody>
                    <?php if (empty($schedules)): ?>
                        <tr>
                            <td colspan="9" class="text-center py-4 text-muted">
                                ❗ Không có tour nào trong tháng này.
                            </td>
                        </tr>
                    <?php endif; ?>

                    <?php foreach($schedules as $i => $s): ?>

                        <?php 
                            $badgeColor = [
                                'upcoming' => 'warning',
                                'ongoing'  => 'success',
                                'completed'=> 'secondary'
                            ][$s['schedule_status']];

                            $badgeText = [
                                'upcoming' => 'Sắp diễn ra',
                                'ongoing'  => 'Đang diễn ra',
                                'completed'=> 'Đã kết thúc'
                            ][$s['schedule_status']];
                        ?>

                        <tr class="tour-row">
                            <td class="fw-bold"><?= $i+1 ?></td>

                            <td>
                                <div class="fw-bold text-primary"><?= htmlspecialchars($s['tour_name']) ?></div>
                                <div class="small text-muted">
                                    Mã lịch: <?= $s['schedule_id'] ?>
                                </div>
                            </td>

                            <td>📅 <?= date('d/m/Y', strtotime($s['start_date'])) ?></td>
                            <td>📅 <?= date('d/m/Y', strtotime($s['end_date'])) ?></td>

                            <td>
                                <span class="badge bg-info px-3 py-2">
                                    <?= $s['total_customers'] ?> KH
                                </span>
                            </td>

                            <td>
                                <?= $s['driver_name'] 
                                    ? '<strong>🚐 '.$s['driver_name'].'</strong>'
                                    : '<span class="text-muted">Chưa gán</span>' ?>
                            </td>

                            <td>
                                <?= $s['hotel_name'] 
                                    ? '<strong>🏨 '.$s['hotel_name'].'</strong>'
                                    : '<span class="text-muted">Chưa có</span>' ?>
                            </td>

                            <td>
                                <span class="badge bg-<?= $badgeColor ?> status-badge">
                                    <?= $badgeText ?>
                                </span>
                            </td>

                            <td>
                                <a href="index.php?act=schedule-detail&schedule_id=<?= $s['schedule_id'] ?>"
                                   class="btn btn-outline-primary btn-sm action-btn">
                                    👁 Chi tiết
                                </a>
                            </td>

                        </tr>

                    <?php endforeach; ?>
                </tbody>

            </table>

        </div>
    </div>

</div>

<?php footerGuide() ?>

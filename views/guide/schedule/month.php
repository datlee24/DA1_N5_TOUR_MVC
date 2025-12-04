<?php headerGuide() ?>
<div class="container mt-4">

    <h2 class="mb-3">🧭 Tour của tôi trong tháng</h2>

    <div class="card p-3 shadow-sm mb-4">
        <form method="get" class="row g-2">
            <input type="hidden" name="act" value="my-tours">

            <div class="col-md-3">
                <select name="month" class="form-select">
                    <?php for($m=1;$m<=12;$m++): ?>
                        <option value="<?= $m ?>" <?= $m==$month?'selected':'' ?>>
                            Tháng <?= $m ?>
                        </option>
                    <?php endfor; ?>
                </select>
            </div>

            <div class="col-md-3">
                <select name="year" class="form-select">
                    <?php for($y=date("Y")-1;$y<=date("Y")+1;$y++): ?>
                        <option value="<?= $y ?>" <?= $y==$year?'selected':'' ?>>
                            <?= $y ?>
                        </option>
                    <?php endfor; ?>
                </select>
            </div>

            <div class="col-md-3">
                <button class="btn btn-primary">Xem lịch</button>
            </div>
        </form>
    </div>


    <div class="card shadow-sm">
        <div class="card-body p-0">

            <table class="table table-hover mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Tour</th>
                        <th>Ngày đi</th>
                        <th>Ngày về</th>
                        <th>Khách</th>
                        <th>Tài xế</th>
                        <th>Khách sạn</th>
                        <th>Trạng thái</th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach($schedules as $i => $s): ?>

                        <?php 
                            $badge = [
                                'upcoming' => 'warning',
                                'ongoing' => 'success',
                                'completed' => 'secondary'
                            ][$s['schedule_status']];
                        ?>

                        <tr>
                            <td><?= $i+1 ?></td>
                            <td><strong><?= $s['tour_name'] ?></strong></td>
                            <td><?= $s['start_date'] ?></td>
                            <td><?= $s['end_date'] ?></td>

                            <td><span class="badge bg-info"><?= $s['total_customers'] ?> KH</span></td>

                            <td>
                                <?= $s['driver_name'] ?? '<span class="text-muted">Chưa gán</span>' ?>
                            </td>

                            <td>
                                <?= $s['hotel_name'] ?? '<span class="text-muted">Chưa có</span>' ?>
                            </td>

                            <td>
                                <span class="badge bg-<?= $badge ?>">
                                    <?= strtoupper($s['schedule_status']) ?>
                                </span>
                            </td>

                            <td>
                            </td>
                        </tr>

                    <?php endforeach; ?>
                </tbody>

            </table>

        </div>
    </div>

</div>
<?php footerGuide() ?>
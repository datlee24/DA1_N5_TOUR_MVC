<?php headerAdmin(); ?>

<?php 
$step = 5; 
include __DIR__ . '/progress.php';
?>


<div class="card shadow p-4">
    <h4 class="mb-3">Bước 5: Xác nhận thông tin booking</h4>

    <div class="card p-3 shadow-sm border-0 mb-3">
        <p><b>Tour:</b> <?= $tour['name'] ?></p>
        <p><b>Lịch:</b>
            <?= date("d/m/Y", strtotime($schedule['start_date'])) ?>
            →
            <?= date("d/m/Y", strtotime($schedule['end_date'])) ?>
        </p>
        <p><b>HDV:</b> <?= $guide['fullname'] ?? "Không có" ?></p>
        <p><b>Số khách:</b> <?= $num_people ?></p>
        <p><b>Tổng tiền (Ước tính):</b> 
            <span class="text-danger fw-bold fs-5">
                <?= number_format($total_price) ?>₫
            </span>
        </p>
    </div>

    <form action="admin.php?act=booking-finish" method="POST">
        <input type="hidden" name="total_price" value="<?= $total_price ?>"> 

        <label class="fw-bold">Ghi chú</label>
        <textarea name="note" class="form-control mb-3" rows="3"></textarea>

        <div class="d-flex justify-content-between">
            <a href="admin.php?act=booking-step4" class="btn btn-secondary">← Quay lại</a>
            <button class="btn btn-success btn-lg">✔ Hoàn tất Booking</button>
        </div>
    </form>
</div>

<?php footerAdmin(); ?>
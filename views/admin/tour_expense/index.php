<?php headerAdmin(); ?>

<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger mt-3" role="alert">
        <?= $_SESSION['error']; ?>
    </div>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>

<div class="d-flex justify-content-between align-items-center mt-4">
    <h1 class="h3">Danh sách chi phí tour</h1>
</div>

<div class="card mt-4">
    <div class="card-body table-responsive">
        <table class="table table-bordered table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Lịch trình</th>
                    <th>Loại chi phí</th>
                    <th>Mô tả</th>
                    <th>Số tiền</th>
                    <th>Ngày ghi nhận</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($expenses)): ?>
                    <?php foreach ($expenses as $expense): ?>
                        <tr>
                            <td><?= htmlspecialchars($expense['expense_id']); ?></td>
                            <td><?= htmlspecialchars($expense['schedule_id']); ?></td>
                            <td><?= htmlspecialchars($expense['expense_type']); ?></td>
                            <td><?= htmlspecialchars($expense['description']); ?></td>
                            <td><?= number_format((float)$expense['amount'], 0, ',', '.'); ?> đ</td>
                            <td><?= htmlspecialchars($expense['expense_date']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center">Chưa có bản ghi chi phí</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php footerAdmin(); ?>


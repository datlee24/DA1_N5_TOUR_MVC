<?php headerAdmin(); ?>

<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger mt-3" role="alert">
        <?= $_SESSION['error']; ?>
    </div>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success mt-3" role="alert">
        <?= $_SESSION['success']; ?>
    </div>
    <?php unset($_SESSION['success']); ?>
<?php endif; ?>

<div class="d-flex justify-content-between align-items-center mt-4">
    <h1 class="h3">Danh sách khách hàng</h1>
</div>

<div class="card mt-4">
    <div class="card-body table-responsive">
        <table class="table table-bordered table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Họ và tên</th>
                    <th>Giới tính</th>
                    <th>Ngày sinh</th>
                    <th>Số điện thoại</th>
                    <th>Email</th>
                    <th>CMND/CCCD</th>
                    <th>Ghi chú</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($customers)): ?>
                    <?php foreach ($customers as $customer): ?>
                        <tr>
                            <td><?= htmlspecialchars($customer['customer_id']); ?></td>
                            <td><?= htmlspecialchars($customer['fullname']); ?></td>
                            <td><?= htmlspecialchars($customer['gender']); ?></td>
                            <td><?= htmlspecialchars($customer['birthdate']); ?></td>
                            <td><?= htmlspecialchars($customer['phone']); ?></td>
                            <td><?= htmlspecialchars($customer['email']); ?></td>
                            <td><?= htmlspecialchars($customer['id_number']); ?></td>
                            <td><?= htmlspecialchars($customer['notes']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" class="text-center">Chưa có khách hàng nào</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php footerAdmin(); ?>



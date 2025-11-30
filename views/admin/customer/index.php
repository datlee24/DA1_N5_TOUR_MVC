<?php headerAdmin(); ?>

<div class="container-fluid mt-4">

    <div class="d-flex justify-content-between align-items-end flex-wrap gap-3">
        <h1 class="h3 m-0">Danh sách khách hàng</h1>

        <form method="GET" action="admin.php" class="d-flex" style="max-width: 360px;">
            <input type="hidden" name="act" value="customer">
            <input type="text" 
                   name="keyword" 
                   class="form-control me-2"
                   placeholder="Tìm tên, SĐT, email..."
                   value="<?= htmlspecialchars($_GET['keyword'] ?? '') ?>">
            <button class="btn btn-primary">Tìm</button>
        </form>

        <a href="admin.php?act=customer-create" class="btn btn-success">
            + Thêm khách hàng
        </a>
    </div>

    <div class="card mt-4 shadow-sm">
        <div class="card-body table-responsive p-0">
            <table class="table table-hover table-bordered mb-0 align-middle">
                <thead class="table-light text-center">
                    <tr>
                        <th>ID</th>
                        <th>Họ và tên</th>
                        <th>Giới tính</th>
                        <th>Ngày sinh</th>
                        <th>SĐT</th>
                        <th>Email</th>
                        <th>CMND/CCCD</th>
                        <th>Ghi chú</th>
                    </tr>
                </thead>

                <tbody>
                    <?php if (!empty($customers)): ?>
                        <?php foreach ($customers as $c): ?>
                            <tr>
                                <td class="text-center"><?= $c['customer_id']; ?></td>
                                <td><?= htmlspecialchars($c['fullname'] ?? '—'); ?></td>
                                <td class="text-center"><?= htmlspecialchars($c['gender'] ?? '—'); ?></td>
                                <td class="text-center"><?= htmlspecialchars($c['birthdate'] ?? '—'); ?></td>
                                <td class="fw-bold text-primary"><?= htmlspecialchars($c['phone'] ?? '—'); ?></td>
                                <td><?= htmlspecialchars($c['email'] ?? '—'); ?></td>
                                <td><?= htmlspecialchars($c['id_number'] ?? '—'); ?></td>
                                <td><?= htmlspecialchars($c['notes'] ?? '—'); ?></td>
                            </tr>
                        <?php endforeach; ?>

                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">
                                <i>Không có khách hàng nào</i>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>

            </table>
        </div>
    </div>

</div>

<?php footerAdmin(); ?>

<?php headerAdmin(); ?>

<div class="container mt-4">

    <h1 class="h3 mb-3">Sửa khách hàng</h1>

    <div class="card shadow-sm">
        <div class="card-body">

            <form action="admin.php?act=customer-update&id=<?= $customer['customer_id']; ?>" method="POST">

                <div class="mb-3">
                    <label class="form-label">Họ và tên</label>
                    <input type="text" name="fullname" class="form-control" value="<?= htmlspecialchars($customer['fullname'] ?? ''); ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Giới tính</label>
                    <select name="gender" class="form-select">
                        <option value="">— Chọn —</option>
                        <option value="Nam" <?= ($customer['gender'] ?? '') === 'Nam' ? 'selected' : ''; ?>>Nam</option>
                        <option value="Nữ" <?= ($customer['gender'] ?? '') === 'Nữ' ? 'selected' : ''; ?>>Nữ</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Ngày sinh</label>
                    <input type="date" name="birthdate" class="form-control" value="<?= htmlspecialchars($customer['birthdate'] ?? ''); ?>">
                </div>

                <div class="mb-3">
                    <label class="form-label">Số điện thoại</label>
                    <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($customer['phone'] ?? ''); ?>">
                </div>

                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($customer['email'] ?? ''); ?>">
                </div>

                <div class="mb-3">
                    <label class="form-label">CMND/CCCD</label>
                    <input type="text" name="id_number" class="form-control" value="<?= htmlspecialchars($customer['id_number'] ?? ''); ?>">
                </div>

                <div class="mb-3">
                    <label class="form-label">Ghi chú</label>
                    <textarea name="notes" class="form-control" rows="3"><?= htmlspecialchars($customer['notes'] ?? ''); ?></textarea>
                </div>

                <button class="btn btn-primary">Cập nhật</button>
                <a href="admin.php?act=customer" class="btn btn-secondary">Quay lại</a>

            </form>

        </div>
    </div>

</div>

<?php footerAdmin(); ?>

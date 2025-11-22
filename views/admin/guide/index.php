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
    <h1 class="h3">Quản lý hướng dẫn viên</h1>
    <a href="admin.php?act=guide-create" class="btn btn-primary">+ Thêm hướng dẫn viên</a>
    </div>

<div class="card mt-4">
    <div class="card-body table-responsive">
        <table class="table table-bordered table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Nhân sự</th>
                    <th>Ngôn ngữ</th>
                    <th>Giấy phép</th>
                    <th>Chuyên môn/Khu vực</th>
                    <th>Thù lao cơ bản</th>
                    <th class="text-center">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($guides)): ?>
                    <?php foreach ($guides as $guide): ?>
                        <tr>
                            <td><?= htmlspecialchars($guide['guide_id']); ?></td>
                            <td>
                                <strong><?= htmlspecialchars($guide['username'] ?? 'N/A'); ?></strong><br>
                                <small><?= htmlspecialchars($guide['email'] ?? ''); ?></small>
                            </td>
                            <td><?= htmlspecialchars($guide['language']); ?></td>
                            <td><?= htmlspecialchars($guide['certificate']); ?></td>
                            <td><?= htmlspecialchars($guide['specialization']); ?></td>
                            <td><?= number_format((float)$guide['base_fee'], 0, ',', '.'); ?> đ</td>
                            <td class="text-center">
                                <a href="admin.php?act=guide-edit&id=<?= $guide['guide_id']; ?>" class="btn btn-sm btn-warning">Sửa</a>
                                <a href="admin.php?act=guide-delete&id=<?= $guide['guide_id']; ?>"
                                   class="btn btn-sm btn-danger"
                                   onclick="return confirm('Bạn chắc chắn muốn xóa hướng dẫn viên này?');">
                                    Xóa
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center">Chưa có dữ liệu hướng dẫn viên</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php footerAdmin(); ?>


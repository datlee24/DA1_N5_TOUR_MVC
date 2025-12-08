<?php headerAdmin(); ?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="h4 mb-0">Danh sách danh mục</h2>
        <a href="admin.php?act=category_add_form" class="btn btn-primary">Thêm danh mục</a>
    </div>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success" id="flash-message"><?= $_SESSION['success'] ?></div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger" id="flash-message"><?= $_SESSION['error'] ?></div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th style="width:60px">ID</th>
                            <th>Tên danh mục</th>
                            <th>Mô tả</th>
                            <th style="width:180px">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($categories as $index => $cate): ?>
                            <tr>
                                <td class="fw-bold"><?= $index + 1 ?></td>
                                <td><?= htmlspecialchars($cate['name']) ?></td>
                                <td><?= htmlspecialchars($cate['description'] ?? '') ?></td>
                                <td>
                                    <a class="btn btn-sm btn-success" href="admin.php?act=category_edit_form&id=<?= $cate['category_id'] ?>">Sửa</a>
                                    <a class="btn btn-sm btn-danger" href="admin.php?act=category_delete&id=<?= $cate['category_id'] ?>" onclick="return confirm('Xóa danh mục này?')">Xóa</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (empty($categories)): ?>
                            <tr>
                                <td colspan="4" class="text-center text-muted">Chưa có danh mục</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php footerAdmin() ?>

<script>
    const flash = document.getElementById('flash-message');
    if (flash) {
        setTimeout(() => {
            flash.style.transition = "opacity 0.5s ease";
            flash.style.opacity = "0";
            setTimeout(() => flash.remove(), 500);
        }, 2000);
    }
</script>
<?php
headerAdmin();
?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="h4 mb-0">Danh sách Tour</h2>
        <a href="admin.php?act=form_add_tour" class="btn btn-primary">+ Thêm tour</a>
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
            <form class="row g-2 mb-3" method="GET" action="admin.php">
                <input type="hidden" name="act" value="tour_list">
                <div class="col-md-8">
                    <div class="input-group">
                        <input type="text" name="q" class="form-control" placeholder="Tìm tour ..." value="<?= isset($_GET['q']) ? htmlspecialchars($_GET['q']) : '' ?>">
                        <button class="btn btn-outline-secondary" type="submit">Tìm kiếm</button>
                    </div>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-bordered table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th style="width:60px">#</th>
                            <th>Tên tour</th>
                            <th>Danh mục</th>
                            <th>Nhà cung cấp</th>
                            <th style="width:140px">Giá tour/người</th>
                            <th style="width:120px">Trạng thái</th>
                            <th style="width:220px">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($tours as $index => $tour): ?>
                            <tr>
                                <td class="fw-bold"><?= $index + 1 ?></td>
                                <td><?= htmlspecialchars($tour['name']) ?></td>
                                <td><?= htmlspecialchars($tour['category_name'] ?? '') ?></td>
                                <td><?= htmlspecialchars($tour['supplier'] ?? '') ?></td>
                                <td><?= number_format($tour['price'] ?? 0, 0, ',', '.') ?> đ</td>
                                <td>
                                    <?php if (!empty($tour['status'])): ?>
                                        <span class="badge bg-success">Hiển thị</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Ẩn</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a class="btn btn-sm btn-info" href="admin.php?act=tour_detail&id=<?= $tour['tour_id'] ?>">Chi tiết</a>
                                    <a class="btn btn-sm btn-success" href="admin.php?act=form_edit_tour&id=<?= $tour['tour_id'] ?>">Sửa</a>
                                    <a class="btn btn-sm btn-danger" href="admin.php?act=delete_tour&id=<?= $tour['tour_id'] ?>" onclick="return confirm('Xóa tour này?')">Xóa</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php footerAdmin(); ?>

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
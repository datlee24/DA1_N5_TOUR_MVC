<?php headerAdmin(); ?>

<div class="d-flex justify-content-between mt-4">
    <h2>Danh sách Hướng dẫn viên</h2>
    <a href="admin.php?act=guide-create" class="btn btn-primary">+ Thêm mới</a>
</div>

<div class="card mt-3">
    <div class="card-body table-responsive">
        <table class="table table-bordered">
    <thead class="table-light">
        <tr>
            <th>ID</th>
            <th>Hướng dẫn viên</th>
            <th>Ngôn ngữ</th>
            <th>Giấy phép</th>
            <th>Kinh nghiệm</th>
            <th>Chuyên môn</th>
            <th>Thao tác</th>
        </tr>
    </thead>

    <tbody>
        <?php foreach ($guides as $guide): ?>
        <tr>
            <td><?= $guide['guide_id'] ?></td>

            <td>
                <strong><?= $guide['fullname'] ?></strong> <br>
            </td>

            <td><?= $guide['language'] ?></td>
            <td><?= $guide['certificate'] ?></td>
            <td><?= $guide['experience'] ?></td>
            <td><?= $guide['specialization'] ?></td>

            <td>
                <a href="admin.php?act=guide-edit&id=<?= $guide['guide_id'] ?>" class="btn btn-warning btn-sm">Sửa</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

    </div>
</div>

<?php footerAdmin(); ?>

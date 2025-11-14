<?php headerAdmin() ?>
<h2>Danh sách Tour</h2>
<a href="admin.php?act=form_add_tour">+ Thêm tour</a>
<table border="1" cellpadding="8" cellspacing="0">
    <tr>
        <th>ID</th>
        <th>Tên tour</th>
        <th>Danh mục</th>
        <th>Nhà cung cấp</th>
        <th>Ảnh</th>
        <th>Trạng thái</th>
        <th>Hành động</th>
    </tr>
    <?php foreach ($tours as $tour): ?>
    <tr>
        <td><?= $tour['tour_id'] ?></td>
        <td><?= $tour['name'] ?></td>
        <td><?= $tour['category_name'] ?></td>
        <td><?= $tour['supplier'] ?></td>
        <td><img src="upload/tours/<?= $tour['image'] ?>" width="100"></td>
        <td><?= $tour['status'] ? 'Hiển thị' : 'Ẩn' ?></td>
        <td>
            <a href="admin.php?act=form_edit_tour&id=<?= $tour['tour_id'] ?>">Sửa</a> |
            <a href="admin.php?act=delete_tour&id=<?= $tour['tour_id'] ?>" onclick="return confirm('Xóa tour này?')">Xóa</a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
<?php footerAdmin() ?>

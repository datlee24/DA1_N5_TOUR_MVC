<?php headerAdmin() ?>

<h2 class="page-title">Danh sách danh mục</h2>

<?php 
if(isset($_SESSION['success'])){
    echo '<div class="alert alert-success">'.$_SESSION['success'].'</div>';
    unset($_SESSION['success']);
}

if(isset($_SESSION['error'])){
    echo '<div class="alert alert-danger">'.$_SESSION['error'].'</div>';
    unset($_SESSION['error']);
}
?>

<a class="btn-add" href="admin.php?act=category_add_form">+ Thêm danh mục</a>

<table class="admin-table">
    <tr>
        <th>ID</th>
        <th>Tên danh mục</th>
        <th>Mô tả</th>
        <th>Hành động</th>
    </tr>

    <?php foreach($categories as $index => $cate): ?>
        <tr>
            <td><?= $index+1 ?></td>
            <td><?= $cate['name'] ?></td>
            <td><?= $cate['description'] ?></td>
            <td>
                <a class="btn-edit" href="admin.php?act=category_edit_form&id=<?= $cate['category_id'] ?>">Sửa</a>
                <a class="btn-delete" href="admin.php?act=category_delete&id=<?= $cate['category_id'] ?>" onclick="return confirm('Xóa danh mục này?')">Xóa</a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

<?php footerAdmin() ?>

<style>
/* Giao diện chung (giống tour list) */
body {
    font-family: Arial, sans-serif;
    background-color: #f0f8ff;
}

/* Tiêu đề */
.page-title {
    text-align: center;
    color: #1e90ff;
    margin-bottom: 20px;
}

/* Nút thêm */
.btn-add {
    display: inline-block;
    margin-bottom: 15px;
    padding: 8px 15px;
    background: #1e90ff;
    color: #fff;
    border-radius: 8px;
    text-decoration: none;
}
.btn-add:hover { background: #63b3ff; }

/* Bảng chung */
.admin-table {
    width: 100%;
    border-collapse: collapse;
    background: #fff;
    overflow: hidden;
    border-radius: 10px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
}
.admin-table th {
    padding: 12px;
    background: #1e90ff;
    color: #fff;
}
.admin-table td {
    padding: 10px;
    border-bottom: 1px solid #cce7ff;
}

/* Nút sửa */
.btn-edit {
    background: #28a745;
    color: #fff;
    padding: 6px 10px;
    border-radius: 5px;
    text-decoration: none;
}
.btn-edit:hover { background: #63d168; }

/* Nút xóa */
.btn-delete {
    background: #dc3545;
    color: white;
    padding: 6px 10px;
    border-radius: 5px;
    text-decoration: none;
}
.btn-delete:hover { background: #e86c75; }
</style>

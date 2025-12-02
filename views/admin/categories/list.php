<?php headerAdmin() ?>
<h2 class="">Danh sách danh mục</h2>
<?php 
if(isset($_SESSION['success'])){
    echo '<div class="alert alert-success" id="flash-message">'.$_SESSION['success'].'</div>';
    unset($_SESSION['success']);
}

if(isset($_SESSION['error'])){
    echo '<div class="alert alert-danger" id="flash-message">'.$_SESSION['error'].'</div>';
    unset($_SESSION['error']);
}
?>

<a href="admin.php?act=category_add_form">Thêm danh mục</a>
<table border="1" cellpadding="10">
    <tr>
        <th>ID</th>
        <th>Tên danh mục</th>
        <th>Mô tả</th>
        <th>Hành động</th>
    </tr>
    <?php  foreach($categories as $index=> $cate):?>
        <tr>
            <td><?=$index +1 ?></td>
            <td><?=$cate['name'] ?></td>
            <td><?=$cate['description']?></td>
            <td>
                <a href="admin.php?act=category_edit_form&id=<?= $cate['category_id']?>">Sửa</a>
                <a href="admin.php?act=category_delete&id=<?= $cate['category_id']?>"onclick="return confirm('Xóa danh mục này?')">Xóa</a>
            </td>
        </tr>
         <?php endforeach; ?>
</table>
<?php footerAdmin() ?>
<style>
    /* Background & text */
body {
    background-color: #f8faff; /* trắng xanh nhạt */
    color: #333;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

/* Header */
header, h2 {
    color: #0d6efd; /* xanh Bootstrap */
}

/* Buttons */
.btn-primary {
    background-color: #0d6efd;
    border-color: #0d6efd;
}
.btn-primary:hover {
    background-color: #0b5ed7;
    border-color: #0a58ca;
}

.btn-success {
    background-color: #198754;
    border-color: #198754;
}
.btn-success:hover {
    background-color: #157347;
    border-color: #146c43;
}

.btn-secondary {
    background-color: #6c757d;
    border-color: #6c757d;
}
.btn-secondary:hover {
    background-color: #5c636a;
    border-color: #565e64;
}

/* Form inputs */
input.form-control, textarea.form-control {
    border: 1px solid #0d6efd;
    border-radius: 5px;
}

input.form-control:focus, textarea.form-control:focus {
    border-color: #0b5ed7;
    box-shadow: 0 0 5px rgba(13, 110, 253, 0.3);
}

/* Table */
table {
    width: 100%;
    border-collapse: collapse;
    background-color: #ffffff;
}

table th, table td {
    border: 1px solid #0d6efd;
    padding: 10px;
    text-align: left;
}

table th {
    background-color: #0d6efd;
    color: #fff;
}

table tr:nth-child(even) {
    background-color: #e9f2ff;
}

/* Alerts */
.alert-success {
    background-color: #d1e7dd;
    color: #0f5132;
    border-color: #badbcc;
    padding: 10px;
    border-radius: 5px;
    margin-bottom: 15px;
}

.alert-danger {
    background-color: #f8d7da;
    color: #842029;
    border-color: #f5c2c7;
    padding: 10px;
    border-radius: 5px;
    margin-bottom: 15px;
}

/* Links in table */
table a {
    text-decoration: none;
    color: #0d6efd;
}

table a:hover {
    text-decoration: underline;
}

</style>
<script>
    const flash = document.getElementById('flash-message');
    if(flash){
        setTimeout(() => {
            flash.style.transition = "opacity 0.5s ease";
            flash.style.opacity = "0";
            setTimeout(() => flash.remove(), 500); // xóa khỏi DOM
        }, 2000); // 2000ms = 2s
    }
</script>

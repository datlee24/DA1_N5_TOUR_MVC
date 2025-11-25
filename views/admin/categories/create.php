<?php headerAdmin() ?>
<h2>Thêm danh mục tour</h2>

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

<form action="admin.php?act=category_add" method="POST" class="mt-3">

    <div class="mb-3">
        <label class="form-label">Tên danh mục</label>
        <input type="text" name="name" class="form-control" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Mô tả</label>
        <textarea name="description" class="form-control" rows="3"></textarea>
    </div>

    <button type="submit" class="btn btn-primary">Thêm mới</button>
    <a href="admin.php?act=category_list" class="btn btn-secondary">Quay lại</a>

</form>
<?php footerAdmin() ?>
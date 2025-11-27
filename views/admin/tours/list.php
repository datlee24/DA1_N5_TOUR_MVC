<?php 
headerAdmin(); 
?>
<h2>Danh sách Tour</h2>
<a href="admin.php?act=form_add_tour">+ Thêm tour</a>
<table>
    <tr>
        <th>ID</th>
        <th>Tên tour</th>
        <th>Danh mục</th>
        <th>Nhà cung cấp</th>
        <th>Giá tour/người</th>
        <th>Trạng thái</th>
        <th>Hành động</th>
    </tr>
    <?php foreach ($tours as $index=>$tour): ?>
    <tr>
        <td><?= $index+1 ?></td>
        <td><?= $tour['name'] ?></td>
        <td><?= $tour['category_name'] ?></td>
        <td><?= $tour['supplier'] ?></td>
        <td><?= number_format($tour['price'], 0, ',', '.') . ' đ' ?></td>
        
        <td><?= $tour['status'] ? 'Hiển thị' : 'Ẩn' ?></td>
        <td>
            <a class="detail" href="admin.php?act=tour_detail&id=<?= $tour['tour_id'] ?>">Chi tiết</a>


            <a class="edit" href="admin.php?act=form_edit_tour&id=<?= $tour['tour_id'] ?>">Sửa</a>
            <a class="delete" href="admin.php?act=delete_tour&id=<?= $tour['tour_id'] ?>" onclick="return confirm('Xóa tour này?')">Xóa</a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
<?php 
footerAdmin(); 
?>

<style>
body {
    font-family: Arial, sans-serif;
    background-color: #f0f8ff;
    margin: 20px;
}
/* Nút Chi tiết */
table a.detail {
    text-decoration: none;
    color: white;
    background-color: #007bff; /* xanh dương */
    padding: 5px 10px;
    border-radius: 5px;
    transition: background-color 0.3s ease;
    margin-right: 5px;
}

table a.detail:hover {
    background-color: #3399ff;
}


/* Tiêu đề */
h2 {
    color: #1e90ff;
    text-align: center;
    margin-bottom: 20px;
}

/* Link thêm tour */
a[href*="form_add_tour"] {
    display: inline-block;
    margin-bottom: 15px;
    text-decoration: none;
    color: white;
    background-color: #1e90ff;
    padding: 8px 15px;
    border-radius: 8px;
    transition: background-color 0.3s ease;
}

a[href*="form_add_tour"]:hover {
    background-color: #63b3ff;
}

/* Bảng */
table {
    width: 100%;
    border-collapse: collapse;
    background-color: #ffffff;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
}

/* Header bảng */
table th {
    background-color: #1e90ff;
    color: white;
    padding: 12px;
    text-align: left;
}

/* Dòng dữ liệu */
table td {
    padding: 10px;
    border-bottom: 1px solid #cce7ff;
    vertical-align: middle;
}

/* Ảnh tour */
table img {
    border-radius: 6px;
}

/* Nút Sửa */
table a.edit {
    text-decoration: none;
    color: white;
    background-color: #28a745; /* xanh lá */
    padding: 5px 10px;
    border-radius: 5px;
    transition: background-color 0.3s ease;
    margin-right: 5px;
}

table a.edit:hover {
    background-color: #63d168;
}

/* Nút Xóa */
table a.delete {
    text-decoration: none;
    color: white;
    background-color: #dc3545; /* đỏ */
    padding: 5px 10px;
    border-radius: 5px;
    transition: background-color 0.3s ease;
}

table a.delete:hover {
    background-color: #e86c75;
}

/* Dòng cuối không có border */
table tr:last-child td {
    border-bottom: none;
}
</style>

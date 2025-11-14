<h2>Thêm tour</h2>
<form method="POST" enctype="multipart/form-data"action="admin.php?act=add_tour">
    <label>Danh mục ID:</label>
    <input type="number" name="category_id" required><br>

    <label>Tên tour:</label>
    <input type="text" name="name" required><br>

    <label>Mô tả:</label>
    <textarea name="description"></textarea><br>

    <label>Chính sách:</label>
    <textarea name="policy"></textarea><br>

    <label>Nhà cung cấp:</label>
    <input type="text" name="supplier"><br>

    <label>Ảnh:</label>
    <input type="file" name="image"><br>

    <label>Trạng thái:</label>
    <select name="status">
        <option value="1">Hiển thị</option>
        <option value="0">Ẩn</option>
    </select><br><br>

    <button type="submit">Lưu</button>
</form>

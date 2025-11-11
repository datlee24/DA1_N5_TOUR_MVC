<h2>Sửa tour</h2>
<form method="POST" enctype="multipart/form-data">
    <label>Danh mục ID:</label>
    <input type="number" name="category_id" value="<?= $tour['category_id'] ?>" required><br>

    <label>Tên tour:</label>
    <input type="text" name="name" value="<?= $tour['name'] ?>" required><br>

    <label>Mô tả:</label>
    <textarea name="description"><?= $tour['description'] ?></textarea><br>

    <label>Chính sách:</label>
    <textarea name="policy"><?= $tour['policy'] ?></textarea><br>

    <label>Nhà cung cấp:</label>
    <input type="text" name="supplier" value="<?= $tour['supplier'] ?>"><br>

    <label>Ảnh hiện tại:</label><br>
    <img src="<?= $tour['image'] ?>" width="120"><br>

    <label>Chọn ảnh mới (nếu muốn thay):</label>
    <input type="file" name="image"><br>

    <label>Trạng thái:</label>
    <select name="status">
        <option value="1" <?= $tour['status'] ? 'selected' : '' ?>>Hiển thị</option>
        <option value="0" <?= !$tour['status'] ? 'selected' : '' ?>>Ẩn</option>
    </select><br><br>

    <button type="submit">Cập nhật</button>
</form>

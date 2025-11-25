<?php headerAdmin(); ?>
<h2>Sửa tour</h2>
<form method="POST" enctype="multipart/form-data" action="admin.php?act=update_tour">

    <input type="hidden" name="tour_id" value="<?= $tour['tour_id'] ?>">
    <input type="hidden" name="old_image" value="<?= $tour['image'] ?>">
 <label>Danh mục:</label>
    <select name="category_id" required>
        <?php foreach ($categories as $cate): ?>
            <option value="<?= $cate['category_id'] ?>"
                <?= ($cate['category_id'] == $tour['category_id']) ? 'selected' : '' ?>>
                <?= $cate['name'] ?>
            </option>
        <?php endforeach; ?>
    </select>

    <label>Tên tour:</label>
    <input type="text" name="name" value="<?= $tour['name'] ?>" required>

    <label>Mô tả:</label>
    <textarea name="description"><?= $tour['description'] ?></textarea>

    <label>Chính sách:</label>
    <textarea name="policy"><?= $tour['policy'] ?></textarea>

    <label>Nhà cung cấp:</label>
    <input type="text" name="supplier" value="<?= $tour['supplier'] ?>">

    <label>Ảnh hiện tại:</label><br>
    <img src="upload/tours/<?= $tour['image'] ?>" width="120" style="border-radius:6px; margin-bottom:10px;"><br>

    <label>Chọn ảnh mới (nếu muốn thay):</label>
    <input type="file" name="image">

    <label>Trạng thái:</label>
    <select name="status">
        <option value="1" <?= $tour['status'] ? 'selected' : '' ?>>Hiển thị</option>
        <option value="0" <?= !$tour['status'] ? 'selected' : '' ?>>Ẩn</option>
    </select>

    <button type="submit">Cập nhật</button>
</form>

<style>
body {
    font-family: Arial, sans-serif;
    background-color: #f0f8ff;
    margin: 20px;
}

/* Tiêu đề */
h2 {
    color: #1e90ff;
    text-align: center;
    margin-bottom: 20px;
}

/* Form container */
form {
    background-color: #ffffff;
    max-width: 500px;
    margin: 0 auto;
    padding: 25px;
    border-radius: 12px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
}

/* Nhãn */
label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
    color: #333333;
    margin-top: 10px;
}

/* Input, textarea, select */
input[type="text"],
input[type="number"],
input[type="file"],
textarea,
select {
    width: 100%;
    padding: 10px;
    margin-bottom: 10px;
    border: 1px solid #cce7ff;
    border-radius: 8px;
    box-sizing: border-box;
    font-size: 14px;
}

/* Textarea resize */
textarea {
    resize: vertical;
    min-height: 80px;
}

/* Button */
button {
    background-color: #1e90ff;
    color: white;
    border: none;
    padding: 12px 25px;
    border-radius: 8px;
    cursor: pointer;
    font-size: 16px;
    transition: background-color 0.3s ease;
    margin-top: 15px;
}

button:hover {
    background-color: #63b3ff;
}
</style>

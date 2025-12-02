<h2>Thêm tour</h2>
<form method="POST" enctype="multipart/form-data"action="admin.php?act=add_tour">
    <label>Danh mục:</label>
    <select name="category_id" required>
        <?php foreach ($categories as $cate): ?>
            <option value="<?= $cate['category_id'] ?>">
                <?= $cate['name'] ?>
            </option>
        <?php endforeach; ?>
    </select><br>

    <label>Tên tour:</label>
    <input type="text" name="name" required><br>

    <label>Mô tả:</label>
    <textarea name="description"></textarea><br>
    
    <label>Nhà cung cấp:</label>
    <input type="text" name="supplier"><br>

    <label>Giá tiền:</label>
    <input type="number" name="price" value="<?= $tour['price'] ?? '' ?>" required step="1000">


    <label>Trạng thái:</label>
    <select name="status">
        <option value="1">Hiển thị</option>
        <option value="0">Ẩn</option>
    </select><br><br>

    <button type="submit">Lưu</button>
</form>
<style>
/* Nền tổng thể */
body {
    font-family: Arial, sans-serif;
    background-color: #f0f8ff; /* xanh nhạt */
    margin: 0;
    padding: 20px;
}

/* Tiêu đề */
h2 {
    color: #1e90ff; /* xanh trời đậm */
    text-align: center;
    margin-bottom: 20px;
}

/* Form container */
form {
    background-color: #ffffff; /* trắng */
    max-width: 500px;
    margin: 0 auto;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}

/* Nhãn */
label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
    color: #333333;
}

/* Input, textarea, select */
input[type="text"],
input[type="number"],
input[type="file"],
textarea,
select {
    width: 100%;
    padding: 10px;
    margin-bottom: 15px;
    border: 1px solid #cce7ff; /* xanh nhạt viền */
    border-radius: 8px;
    box-sizing: border-box;
    font-size: 14px;
}

/* Button */
button {
    background-color: #1e90ff; /* xanh trời */
    color: white;
    border: none;
    padding: 12px 25px;
    border-radius: 8px;
    cursor: pointer;
    font-size: 16px;
    transition: background-color 0.3s ease;
}

button:hover {
    background-color: #63b3ff; /* xanh sáng khi hover */
}

/* Chỉnh textarea cho dễ nhìn */
textarea {
    resize: vertical;
    min-height: 80px;
}
</style>


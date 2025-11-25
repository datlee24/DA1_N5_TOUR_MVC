<?php

// Kết nối CSDL qua PDO
function connectDB() {
    // Kết nối CSDL
    $host = DB_HOST;
    $port = DB_PORT;
    $dbname = DB_NAME;

    try {
        $conn = new PDO("mysql:host=$host;port=$port;dbname=$dbname", DB_USERNAME, DB_PASSWORD);

        // cài đặt chế độ báo lỗi là xử lý ngoại lệ
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // cài đặt chế độ trả dữ liệu
        $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    
        return $conn;
    } catch (PDOException $e) {
        echo ("Connection failed: " . $e->getMessage());
    }
}

function uploadFile($file, $folderSave){
    $file_upload = $file;
    $pathStorage = $folderSave . rand(10000, 99999) . $file_upload['name'];

    $tmp_file = $file_upload['tmp_name'];
    $pathSave = PATH_ROOT . $pathStorage; // Đường dãn tuyệt đối của file

    if (move_uploaded_file($tmp_file, $pathSave)) {
        return $pathStorage;
    }
    return null;
}

function deleteFile($file){
    $pathDelete = PATH_ROOT . $file;
    if (file_exists($pathDelete)) {
        unlink($pathDelete); // Hàm unlink dùng để xóa file
    }
}
// HÀm hỗ trợ hiển thị header, footer, sldebar cho admin hoặc hdv
// Giúp dễ dàng include phần header/footer chung mà không cần viết lại nhiều lần

function headerAdmin(){
    include PATH_ADMIN . "layout/header.php";
}
function footerAdmin(){
    include PATH_ADMIN . "layout/footer.php";
}
// Kiểm Tra xem người dùng có phải quản trị hay không nếu là quản trị thì đi tiếp 
function checkIsAdmin(){
    if (isset($_SESSION['admin']) && $_SESSION['admin']['role'] === 'admin') {
        return true;
         // Ngược lại, nếu không có session admin hoặc là hướng dẫn viên (hdv) thì chặn lại
    } elseif (!isset($_SESSION['hdv']) || !isset($_SESSION['admin']) || $_SESSION['users']['role'] === 'hdv') {
        // Nếu không phải admin, chuyển hướng về trang đăng nhập
                // Chuyển hướng người dùng về trang đăng nhập của admin
        header('Location: admin.php?act=login');
        exit;// Dừng toàn bộ chương trình sau khi chuyển hướng
    }
}

<?php 
// Require toàn bộ các file khai báo môi trường, thực thi,...(không require view)

// Require file Common
require_once './commons/env.php'; // Khai báo biến môi trường
require_once './commons/function.php'; // Hàm hỗ trợ

# Tải các controller hưỡng dẫn viên (trong thư mục controllers/guide)
foreach (glob('./controllers/guide/*.php') as $controllerFile) {
    require_once $controllerFile;
}

# Tải các model (sử dụng chung cho cả người dùng và quản trị)
foreach (glob('./models/*.php') as $modelFile) {
    require_once $modelFile;
}

// Route
$act = $_GET['act'] ?? '/';


// Để bảo bảo tính chất chỉ gọi 1 hàm Controller để xử lý request thì mình sử dụng match

match ($act) {
    // Trang chủ
    '/'=>(new ProductController())->Home(),

};
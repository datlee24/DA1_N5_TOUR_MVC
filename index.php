<?php 
// Require toàn bộ các file khai báo môi trường, thực thi,...(không require view)

// Khởi tạo session
session_start();
// Require file Common
require_once './commons/env.php'; // Khai báo biến môi trường
require_once './commons/function.php'; // Hàm hỗ trợ

# Tải các controller hưỡng dẫn viên (trong thư mục controllers/guide)
foreach (glob('./controllers/guide/*.php') as $controllerFile) {
    require_once $controllerFile;
}

# Tải các model (sử dụng chung cho cả HDV và quản trị)
foreach (glob('./models/*.php') as $modelFile) {
    require_once $modelFile;
}

// Route
$act = $_GET['act'] ?? '/';


// Để bảo bảo tính chất chỉ gọi 1 hàm Controller để xử lý request thì mình sử dụng match

match ($act) {
    // Trang chủ
    '/'=>(new GuideController())->Home(),
    // Đăng nhập HDV
    'login' => (new GuideAuthController())->login(),
    'logout' => (new GuideAuthController())->logout(),

     // Hồ sơ HDV
    'profile'         => (new GuideProfileController())->edit(),
    'update-profile'  => (new GuideProfileController())->update(),

     // LỊCH LÀM VIỆC
    'schedule-month' => (new GuideScheduleController())->month(),   // lịch 1 tháng
    'today'          => (new GuideScheduleController())->today(),   // lịch hôm nay
    'my-tours'       => (new GuideScheduleController())->myToursMonth(), // tour của tôi trong tháng
    'schedule-detail' => (new GuideScheduleController())->detail(),
     // DANH SÁCH KHÁCH HÀNG
    'customers'       => (new GuideCustomerController())->index(),

     // ĐIỂM DANH KHÁCH HÀNG
'attendance' => (new GuideAttendanceController())->index(),
'attendance-save' => (new GuideAttendanceController())->save(),



    default     => (new GuideController())->Home()
};
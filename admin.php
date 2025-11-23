<?php 
// Require toàn bộ các file khai báo môi trường, thực thi,...(không require view)

// Khởi tạo session
 session_start();
// Require file Common
require_once './commons/env.php'; // Khai báo biến môi trường
require_once './commons/function.php'; // Hàm hỗ trợ

# Tải các controller admin
foreach (glob('./controllers/admin/*.php') as $controllerFile) {
    require_once $controllerFile;
}
# Tải các model
foreach (glob('./models/*.php') as $modelFile) {
    require_once $modelFile;
}
// Route
$act = $_GET['act'] ?? '/';


// Để bảo bảo tính chất chỉ gọi 1 hàm Controller để xử lý request thì mình sử dụng match
match ($act) {
    // Trang chủ
    '/'=>(new AdminController())->dashboard(),
    'dashboard'=>(new AdminController())->dashboard(),
    'login'=>(new AuthController())->login(),
    'logout'=>(new AuthController())->logout(),

    // booking routes
   'booking'               => (new BookingController())->list(),
   'booking-add'           => (new BookingController())->add(),
   'booking-store'         => (new BookingController())->store(),
   'booking-view'          => (new BookingController())->view(),
   'booking-cancel'        => (new BookingController())->cancel(),

   'get-schedule'          => (new BookingController())->ajaxSchedule(),
   'get-guides-by-tour'    => (new BookingController())->ajaxGuide(),
   'customer-store-ajax'   => (new BookingController())->ajaxCreateCustomer(),
};
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


// Booking 
'booking'               => (new BookingController)->list(),

'booking-step1'         => (new BookingController)->step1(),
'booking-step1-save'    => (new BookingController)->step1Save(),

'booking-step2'         => (new BookingController)->step2(),
'booking-step2-save'    => (new BookingController)->step2Save(),

'booking-step3'         => (new BookingController)->step3(),
'booking-step3-save'    => (new BookingController)->step3Save(),

'booking-step4'         => (new BookingController)->step4(),
'booking-step4-save'    => (new BookingController)->step4Save(),

'booking-step5'         => (new BookingController)->step5(),
'booking-finish'        => (new BookingController)->finish(),

'booking-view'          => (new BookingController)->view(),
'booking-cancel'        => (new BookingController)->cancel(),
'booking-confirm'        => (new BookingController)->confirm(),

'ajax-schedule'         => (new BookingController)->ajaxSchedule(),
'ajax-guides'           => (new BookingController)->ajaxGuide(),
'ajax-customer-create'  => (new BookingController)->ajaxCreateCustomer(),


    //Tour
    'tour_list' => (new TourController())->tour_list(),
    'form_add_tour' => (new TourController())->FormAdd(),
    'add_tour'=> (new TourController())->addTour(),
    'delete_tour'=> (new TourController())->deleteTour(),
    'form_edit_tour'=> (new TourController())->FormEdit(),
    'update_tour'=> (new TourController())->updateTour(),
    // category
     'category_list'=> (new CategoryController())->listCategory(),
 'category_add_form'=>(new CategoryController())->addCategoryForm(),
  'category_add'=> (new CategoryController())->addCategory(),
   'category_edit_form'=> (new CategoryController())->editCategoryForm(),
    'category_update'=> (new CategoryController())->updateCategory(),
    'category_delete'=> (new CategoryController())->deleteCategory(),

    

    'guide'=>(new GuideController())->index(),
    'guide-create'=>(new GuideController())->create(),
    'guide-store'=>(new GuideController())->store(),
    'guide-edit'=>(new GuideController())->edit(),
    'guide-update'=>(new GuideController())->update(),
    'guide-delete'=>(new GuideController())->delete(),
    'tour-expense'=>(new TourExpenseController())->index(),
};
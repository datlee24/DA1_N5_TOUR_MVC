<?php
// KHỞI TẠO SESSION
session_start();

// REQUIRE COMMON
require_once './commons/env.php';
require_once './commons/function.php';

// AUTO-LOAD CONTROLLER (Admin)
foreach (glob('./controllers/admin/*.php') as $file) {
    require_once $file;
}

// AUTO-LOAD MODELS
foreach (glob('./models/*.php') as $file) {
    require_once $file;
}

// LẤY ACTION
$act = $_GET['act'] ?? '/';

// ROUTE XỬ LÝ
match ($act) {

    /* ===============================
        AUTH
       =============================== */
    '/'         => (new AdminController())->dashboard(),
    'dashboard' => (new AdminController())->dashboard(),
    'login'     => (new AuthController())->login(),
    'logout'    => (new AuthController())->logout(),


    /* ===============================
        BOOKING
       =============================== */
    'booking'            => (new BookingController())->list(),

    'booking-step1'      => (new BookingController())->step1(),
    'booking-step1-save' => (new BookingController())->step1Save(),

    'booking-step2'      => (new BookingController())->step2(),
    'booking-step2-save' => (new BookingController())->step2Save(),

    'booking-step3'      => (new BookingController())->step3(),
    'booking-step3-save' => (new BookingController())->step3Save(),

    'booking-step4'      => (new BookingController())->step4(),
    'booking-step4-save' => (new BookingController())->step4Save(),

    'booking-step5'      => (new BookingController())->step5(),
    'booking-finish'     => (new BookingController())->finish(),

    'booking-view'       => (new BookingController())->view(),

    // AJAX booking
    'ajax-schedule'        => (new BookingController())->ajaxSchedule(),
    // 'ajax-guides'          => (new BookingController())->ajaxGuide(),
    'ajax-customer-create' => (new BookingController())->ajaxCreateCustomer(),


    /* ===============================
        TOUR
       =============================== */
    'tour_list'       => (new TourController())->tour_list(),
    'form_add_tour'   => (new TourController())->FormAdd(),
    'add_tour'        => (new TourController())->addTour(),
    'delete_tour'     => (new TourController())->deleteTour(),
    'form_edit_tour'  => (new TourController())->FormEdit(),
    'update_tour'     => (new TourController())->updateTour(),
    'tour_detail'     => (new TourController())->tour_detail(),

    // Itinerary
    'add_itinerary_form' => (new TourController())->addItineraryForm(),
    'add_itinerary'      => (new TourController())->addItinerary(),
    'edit_itinerary_form'=> (new TourController())->editItineraryForm(),
    'update_itinerary'   => (new TourController())->updateItinerary(),
    'delete_itinerary'   => (new TourController())->deleteItinerary(),


    /* ===============================
        CATEGORY
       =============================== */
    'category_list'       => (new CategoryController())->listCategory(),
    'category_add_form'   => (new CategoryController())->addCategoryForm(),
    'category_add'        => (new CategoryController())->addCategory(),
    'category_edit_form'  => (new CategoryController())->editCategoryForm(),
    'category_update'     => (new CategoryController())->updateCategory(),
    'category_delete'     => (new CategoryController())->deleteCategory(),


    /* ===============================
        GUIDE
       =============================== */
    'guide'        => (new GuideController())->index(),
    'guide-create' => (new GuideController())->create(),
    'guide-store'  => (new GuideController())->store(),
    'guide-edit'   => (new GuideController())->edit(),
    'guide-update' => (new GuideController())->update(),


    /* ===============================
        CUSTOMER
       =============================== */
    'customer'        => (new CustomerController())->index(),
    'customer-create' => (new CustomerController())->create(),
    'customer-store'  => (new CustomerController())->store(),

};

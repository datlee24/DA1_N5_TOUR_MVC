<?php
session_start();

// COMMON
require_once './commons/env.php';
require_once './commons/function.php';

// LOAD CONTROLLERS
foreach (glob('./controllers/admin/*.php') as $f) require_once $f;

// LOAD MODELS
foreach (glob('./models/*.php') as $f) require_once $f;

// ROUTE
$act = $_GET['act'] ?? 'dashboard';

match ($act) {

    /* ===============================
        AUTH
    =============================== */
    'login'      => (new AuthController())->login(),
    'logout'     => (new AuthController())->logout(),
    'dashboard'  => (new AdminController())->dashboard(),


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

    // AJAX (đã đồng bộ theo view)
    'ajax-hotels'             => (new BookingController())->ajaxHotels(),
    'booking-ajax-create-customer' => (new BookingController())->ajaxCreateCustomer(),
    'booking-add-customer'    => (new BookingController())->addCustomerToBooking(),
    'booking-update-payment'  => (new BookingController())->updatePaymentStatus(),


    /* ===============================
        CUSTOMER
    =============================== */
    'customer'         => (new CustomerController())->index(),
    'customer-create'  => (new CustomerController())->create(),
    'customer-store'   => (new CustomerController())->store(),
    'customer-edit'    => (new CustomerController())->edit(),
    'customer-update'  => (new CustomerController())->update(),
    // AJAX search đúng route VIEW đang gọi
    // 'customer-search'  => (new CustomerController())->searchAjax(),


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

    'add_itinerary_form' => (new TourController())->addItineraryForm(),
    'add_itinerary'      => (new TourController())->addItinerary(),
    'edit_itinerary_form' => (new TourController())->editItineraryForm(),
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
        ATTENDANCE (Admin)
    =============================== */
    'attendance'   => (new AttendanceController())->index(),
    'statistical'  => (new StatisticalController())->index(),


    default        => (new AdminController())->dashboard()
};

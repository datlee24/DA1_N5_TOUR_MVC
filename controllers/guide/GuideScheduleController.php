<?php
// controllers/guide/GuideScheduleController.php
class GuideScheduleController {
    protected $scheduleModel;

    public function __construct() {
        if (!isset($_SESSION)) session_start();
        checkIsGuide();

        $this->scheduleModel = new ScheduleModel();
    }

    // Lịch toàn tháng
    public function month() {
        // Lấy user_id của guide từ session
        $guide_user_id = $_SESSION['guide']['user_id'] ?? null;

        $year = $_GET['year'] ?? date("Y");
        $month = $_GET['month'] ?? date("m");

        $schedules = $this->scheduleModel->getByMonth($guide_user_id, $year, $month);

        require_once PATH_GUIDE . "calendar.php";
    }

    // Lịch ngày hôm nay
    public function today() {
        $guide_user_id = $_SESSION['guide']['user_id'] ?? null;
        $date = $_GET['date'] ?? date("Y-m-d");

        $schedules = $this->scheduleModel->getByDate($guide_user_id, $date);

        require_once PATH_GUIDE . "today.php";
    }

    // Tour của tôi trong 1 tháng
    public function myToursMonth() {
        $guide_user_id = $_SESSION['guide']['user_id'] ?? null;

        $year = $_GET['year'] ?? date("Y");
        $month = $_GET['month'] ?? date("m");

        $schedules = $this->scheduleModel->getByMonth($guide_user_id, $year, $month);

        require_once PATH_GUIDE . "my_tours_month.php";
    }
}

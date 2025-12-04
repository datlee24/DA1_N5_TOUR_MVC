<?php
// controllers/guide/GuideScheduleController.php
class GuideScheduleController {
    protected $scheduleModel;

    public function __construct() {
        if (!isset($_SESSION)) session_start();
        checkIsGuide();

        $this->scheduleModel = new ScheduleModel();
    }

    // Lịch toàn tháng (không đổi)
    public function month() {
        $guide_user_id = $_SESSION['guide']['user_id'] ?? null;
        $year = $_GET['year'] ?? date("Y");
        $month = $_GET['month'] ?? date("m");
        $schedules = $this->scheduleModel->getGuideMonthSchedules($guide_user_id, $year, $month);
        require_once PATH_GUIDE . "schedule/month.php";
    }

    // Lịch ngày hôm nay -> hiển thị danh sách chuyến trong ngày; mỗi chuyến có nút Xem chi tiết
    public function today() {
        $guide_user_id = $_SESSION['guide']['user_id'] ?? null;
        $date = $_GET['date'] ?? date("Y-m-d");

        // Trả về các schedule có ngày bao phủ date
        $schedules = $this->scheduleModel->getByDate($guide_user_id, $date);

        // Nếu có param schedule_id muốn xem chi tiết trực tiếp, chuyển sang detail
        if (!empty($_GET['schedule_id'])) {
            return $this->detail();
        }

        require_once PATH_GUIDE . "schedule/today.php";
    }

    // Tour của tôi trong 1 tháng
    public function myToursMonth() {
        $guide_user_id = $_SESSION['guide']['user_id'];
        $year = $_GET['year'] ?? date("Y");
        $month = $_GET['month'] ?? date("m");
        $schedules = $this->scheduleModel->getGuideMonthSchedules($guide_user_id, $year, $month);
        require_once PATH_GUIDE . "schedule/month.php";
    }

    // Chi tiết 1 schedule
    public function detail() {
        $schedule_id = $_GET['schedule_id'] ?? null;
        if (!$schedule_id) {
            $_SESSION['error'] = "Schedule ID không hợp lệ.";
            header("Location: index.php?act=today");
            exit;
        }

        $schedule = $this->scheduleModel->getScheduleDetail($schedule_id);
        $customers = $this->scheduleModel->getScheduleCustomers($schedule_id);

        // tính trạng thái schedule hiện tại (upcoming/ongoing/completed)
        $today = date('Y-m-d');
        if ($today >= $schedule['start_date'] && $today <= $schedule['end_date']) {
            $schedule['status_text'] = 'ongoing';
        } elseif ($schedule['start_date'] > $today) {
            $schedule['status_text'] = 'upcoming';
        } else {
            $schedule['status_text'] = 'completed';
        }

        require_once PATH_GUIDE . "schedule/detail.php";
    }
}

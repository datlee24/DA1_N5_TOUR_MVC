<?php
// controllers/guide/GuideController.php
class GuideController
{
    public function __construct()
    {
        if (!isset($_SESSION)) session_start();
        // Không check ở constructor để cho trang login có thể truy cập
    }

    public function Home()
    {
        // Nếu chưa login guide -> redirect login
        if (!isset($_SESSION['guide'])) {
            header("Location: index.php?act=login");
            exit;
        }

        $guide = $_SESSION['guide'];

        // Prepare dashboard data: schedules today and month
        $guide_user_id = $guide['user_id'] ?? null;
        $scheduleModel = new ScheduleModel();

        $today = date('Y-m-d');
        $todaySchedules = $scheduleModel->getByDate($guide_user_id, $today);

        $year = date('Y');
        $month = date('m');
        $monthlySchedules = $scheduleModel->getGuideMonthSchedules($guide_user_id, $year, $month);

        // number of unique tours in month
        $monthly_unique_tours = [];
        foreach ($monthlySchedules as $ms) {
            $monthly_unique_tours[$ms['tour_name']] = true;
        }
        $monthly_unique_count = count($monthly_unique_tours);

        require_once PATH_GUIDE . "trangchu.php";
    }
}

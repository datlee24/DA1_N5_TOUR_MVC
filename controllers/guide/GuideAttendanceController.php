<?php
// controllers/guide/GuideAttendanceController.php
class GuideAttendanceController {
    protected $attendanceModel;
    protected $scheduleModel;

    public function __construct() {
        if (!isset($_SESSION)) session_start();
        checkIsGuide();

        $this->attendanceModel = new AttendanceModel();
        $this->scheduleModel = new ScheduleModel();
    }

    // Hiển thị giao diện điểm danh
    public function index() {
        $schedule_id = $_GET['schedule_id'] ?? null;
        if (!$schedule_id) {
            $_SESSION['error'] = "Schedule ID không hợp lệ.";
            header("Location: index.php?act=today");
            exit;
        }

        // lấy schedule để kiểm tra trạng thái
        $schedule = $this->scheduleModel->getScheduleDetail($schedule_id);
        if (!$schedule) {
            $_SESSION['error'] = "Không tìm thấy lịch";
            header("Location: index.php?act=today");
            exit;
        }

        // tính trạng thái schedule hiện tại
        $today = date('Y-m-d');
        if ($today >= $schedule['start_date'] && $today <= $schedule['end_date']) {
            $schedule['status_text'] = 'ongoing';
        } elseif ($schedule['start_date'] > $today) {
            $schedule['status_text'] = 'upcoming';
        } else {
            $schedule['status_text'] = 'completed';
        }

        // khách kèm trạng thái điểm danh gần nhất
        $customers = $this->attendanceModel->listBySchedule($schedule_id);

        require_once PATH_GUIDE . "attendance/index.php";
    }

    // Lưu điểm danh (POST json)
    public function save() {
        $payload = json_decode(file_get_contents("php://input"), true);
        header('Content-Type: application/json; charset=utf-8');

        if (!$payload) {
            echo json_encode(['success'=>false, 'message'=>'Dữ liệu không hợp lệ']);
            exit;
        }

        $schedule_id = $payload['schedule_id'] ?? null;
        $items = $payload['items'] ?? [];
        $guide_id = $_SESSION['guide']['user_id'] ?? null;

        if (!$schedule_id || !$guide_id) {
            echo json_encode(['success'=>false, 'message'=>'Thiếu thông tin cần thiết']);
            exit;
        }

        // Chỉ cho phép điểm danh nếu lịch đang diễn ra
        $schedule = $this->scheduleModel->getScheduleDetail($schedule_id);
        $today = date('Y-m-d');
        if (!($schedule && $today >= $schedule['start_date'] && $today <= $schedule['end_date'])) {
            echo json_encode(['success'=>false, 'message'=>'Không thể điểm danh cho lịch chưa diễn ra hoặc đã kết thúc']);
            exit;
        }

        $count = 0;
        foreach ($items as $item) {
            // chuẩn hóa giá trị
            $tcid = $item['tour_customer_id'] ?? null;
            $cid = $item['customer_id'] ?? null;
            $status = in_array($item['status'], ['present','absent','unknown']) ? $item['status'] : 'unknown';
            $note = isset($item['note']) ? $item['note'] : null;

            if (!$tcid || !$cid) continue;

            $ok = $this->attendanceModel->upsert([
                'schedule_id' => $schedule_id,
                'tour_customer_id' => $tcid,
                'customer_id' => $cid,
                'guide_id' => $guide_id,
                'status' => $status,
                'note' => $note
            ]);

            if ($ok) $count++;
        }

        echo json_encode(['success'=>true, 'saved'=>$count]);
    }
}
?>

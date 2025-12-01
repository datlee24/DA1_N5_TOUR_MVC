<?php
// controllers/guide/GuideAttendanceController.php
class GuideAttendanceController {
    protected $attendanceModel;

    public function __construct() {
        if (!isset($_SESSION)) session_start();
        checkIsGuide();

        $this->attendanceModel = new AttendanceModel();
    }

    // Hiển thị giao diện điểm danh
    public function index() {
        $schedule_id = $_GET['schedule_id'] ?? null;
        if (!$schedule_id) {
            $_SESSION['error'] = "Schedule ID không hợp lệ.";
            header("Location: index.php?act=today");
            exit;
        }

        $data = $this->attendanceModel->listBySchedule($schedule_id);

        require_once PATH_GUIDE . "attendance.php";
    }

    // Lưu điểm danh (POST json)
    public function save() {
        // Chỉ chấp nhận JSON body
        $payload = json_decode(file_get_contents("php://input"), true);
        if (!$payload) {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['success'=>false, 'message'=>'Dữ liệu không hợp lệ']);
            exit;
        }

        $schedule_id = $payload['schedule_id'] ?? null;
        $items = $payload['items'] ?? [];
        $guide_id = $_SESSION['guide']['user_id'] ?? null;

        if (!$schedule_id || !$guide_id) {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['success'=>false, 'message'=>'Thiếu thông tin cần thiết']);
            exit;
        }

        $count = 0;
        foreach ($items as $item) {
            $ok = $this->attendanceModel->create([
                'schedule_id' => $schedule_id,
                'tour_customer_id' => $item['tour_customer_id'],
                'customer_id' => $item['customer_id'],
                'guide_id' => $guide_id,
                'status' => $item['status'],
                'note' => $item['note'] ?? null
            ]);
            if ($ok) $count++;
        }

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['success'=>true, 'saved'=>$count]);
    }
}

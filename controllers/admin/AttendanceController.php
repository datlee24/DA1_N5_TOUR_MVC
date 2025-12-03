<?php
// controllers/admin/AttendanceController.php
class AttendanceController
{
    protected $attendanceModel;

    public function __construct()
    {
        if (!isset($_SESSION)) session_start();
        checkIsAdmin();
        $this->attendanceModel = new AttendanceModel();
    }

    // Danh sách điểm danh (với lọc)
    public function index()
    {
        $filters = [];
        if (!empty($_GET['schedule_id'])) $filters['schedule_id'] = $_GET['schedule_id'];
        if (!empty($_GET['guide_id'])) $filters['guide_id'] = $_GET['guide_id'];
        if (!empty($_GET['customer_id'])) $filters['customer_id'] = $_GET['customer_id'];
        if (!empty($_GET['date'])) $filters['date'] = $_GET['date'];

        $data = $this->attendanceModel->search($filters);

        require_once PATH_ADMIN . 'attendance/list.php';
    }
}

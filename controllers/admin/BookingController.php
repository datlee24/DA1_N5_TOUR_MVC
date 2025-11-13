<?php
class BookingController {
    protected $bookingModel;
    protected $customerModel;
    protected $historyModel;

    public function __construct() {
        checkIsAdmin(); // Bảo vệ: chỉ admin mới vào được
        $this->bookingModel = new BookingModel();
        $this->customerModel = new CustomerModel();
        $this->historyModel = new BookingHistoryModel();
    }

    // 1️⃣ HIỂN THỊ DANH SÁCH BOOKING
    public function list() {
        $bookings = $this->bookingModel->getAll();
        require_once PATH_ADMIN . 'booking/list.php';
    }

    // 2️⃣ TẠO BOOKING MỚI
    public function add() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Lấy dữ liệu từ form
            $customerName = $_POST['customer_name'];
            $numPeople = $_POST['num_people'];
            $tourId = $_POST['tour_id'];
            $scheduleId = $_POST['schedule_id'];
            $note = $_POST['note'];

            // Tạo hoặc tìm khách hàng
            $customerId = $this->customerModel->findOrCreate($customerName, $_POST);

            // Kiểm tra xem tour còn chỗ trống không
            if ($this->bookingModel->checkAvailable($scheduleId, $numPeople)) {
                $this->bookingModel->create($customerId, $tourId, $scheduleId, $numPeople, $note);
                $_SESSION['success'] = "Tạo booking thành công!";
            } else {
                $_SESSION['error'] = "Tour đã hết chỗ!";
            }

            header('Location: admin.php?act=booking-list');
            exit;
        }

        // Hiển thị form tạo booking
        require_once PATH_ADMIN . 'booking/add.php';
    }

    // 3️⃣ CẬP NHẬT TRẠNG THÁI BOOKING
    public function edit_status() {
        $id = $_GET['id'] ?? 0;
        $booking = $this->bookingModel->findById($id);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $statusNew = $_POST['status'];
            $note = $_POST['note'];
            $statusOld = $booking['status'];

            // Cập nhật trạng thái và lưu lịch sử thay đổi
            $this->bookingModel->updateStatus($id, $statusNew);
            $this->historyModel->logChange($id, $statusOld, $statusNew, $_SESSION['admin']['username'], $note);

            $_SESSION['success'] = "Cập nhật trạng thái thành công!";
            header('Location: admin.php?act=booking-list');
            exit;
        }

        require_once PATH_ADMIN . 'booking/edit_status.php';
    }
}
?>

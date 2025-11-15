<?php
// controllers/admin/BookingController.php
class BookingController {
    protected $bookingModel;
    protected $customerModel;
    protected $historyModel;

    public function __construct(){
        checkIsAdmin(); // function bạn đã có
        $this->bookingModel = new BookingModel();
        $this->customerModel = new CustomerModel();
        $this->historyModel = new BookingHistoryModel();
    }

    // LIST
    public function list(){
        $bookings = $this->bookingModel->getAll();
        require_once PATH_ADMIN . 'booking/list.php';
    }

    // CREATE (form + xử lý)
    public function create(){
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            // Lấy dữ liệu an toàn
            $customer_id = intval($_POST['customer_id'] ?? 0);
            $fullname    = trim($_POST['fullname'] ?? '');
            $phone       = trim($_POST['phone'] ?? '');
            $email       = trim($_POST['email'] ?? '');
            $schedule_id = intval($_POST['schedule_id'] ?? 0);
            $tour_id     = intval($_POST['tour_id'] ?? 0);
            $num_people  = intval($_POST['num_people'] ?? 0);
            $note        = trim($_POST['note'] ?? '');

            if($num_people <= 0){
                $_SESSION['error'] = "Số lượng người phải lớn hơn 0.";
                header('Location: admin.php?act=booking-create');
                exit;
            }
            if($schedule_id <= 0 || $tour_id <= 0){
                $_SESSION['error'] = "Vui lòng chọn lịch khởi hành hợp lệ.";
                header('Location: admin.php?act=booking-create');
                exit;
            }

            // Nếu không chọn khách có sẵn, tạo mới
            if(!$customer_id){
                if(empty($fullname)){
                    $_SESSION['error'] = "Vui lòng nhập tên khách hoặc chọn khách có sẵn.";
                    header('Location: admin.php?act=booking-create');
                    exit;
                }
                $customer_id = $this->customerModel->getOrCreate($fullname, $phone, $email, $note);
            }

            // Check capacity
            $booked = $this->bookingModel->countBookedCustomers($schedule_id);
            $capacity = $this->bookingModel->getTourCapacity($tour_id);
            if ($booked + $num_people > $capacity) {
                $_SESSION['error'] = "Không đủ chỗ cho lịch này. (Đã đặt: $booked / Sức chứa: $capacity)";
                header('Location: admin.php?act=booking-create');
                exit;
            }

            // Insert booking: chuẩn bị data (adult/children/baby mặc định 0 nếu không có form)
            $data = [
                'customer_id' => $customer_id,
                'tour_id'     => $tour_id,
                'schedule_id' => $schedule_id,
                'num_people'  => $num_people,
                'status'      => 'pending',
                'note'        => $note,
                'adult'       => intval($_POST['adult'] ?? 0),
                'children'    => intval($_POST['children'] ?? 0),
                'baby'        => intval($_POST['baby'] ?? 0),
            ];

            try {
                $booking_id = $this->bookingModel->create($data);
                // add history
                $this->historyModel->add($booking_id, null, 'pending', $_SESSION['admin']['username'] ?? 'admin', 'Tạo booking');
                $_SESSION['success'] = "Tạo booking thành công.";
                header('Location: admin.php?act=booking');
                exit;
            } catch (Exception $e){
                $_SESSION['error'] = "Lỗi khi tạo booking: " . $e->getMessage();
                header('Location: admin.php?act=booking-create');
                exit;
            }
        }

        // Hiển thị form
        $schedules = $this->bookingModel->getSchedulesWithTours();
        $customers = $this->customerModel->getAll();
        require_once PATH_ADMIN . 'booking/add.php';
    }

    // Edit status (form + xử lý)
    public function edit_status(){
        $id = intval($_GET['id'] ?? 0);
        $booking = $this->bookingModel->find($id);
        if(!$booking){
            $_SESSION['error'] = "Booking không tồn tại.";
            header('Location: admin.php?act=booking');
            exit;
        }

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $new_status = $_POST['new_status'] ?? $booking['status'];
            $note = trim($_POST['note'] ?? '');
            $old_status = $booking['status'];

            if($new_status === $old_status){
                $_SESSION['success'] = "Không có thay đổi trạng thái.";
            } else {
                // Cập nhật status
                $ok = $this->bookingModel->updateStatus($id, $new_status);
                if($ok){
                    $this->historyModel->add($id, $old_status, $new_status, $_SESSION['admin']['username'] ?? 'admin', $note);
                    $_SESSION['success'] = "Cập nhật trạng thái thành công.";
                } else {
                    $_SESSION['error'] = "Không thể cập nhật trạng thái.";
                }
            }
            header('Location: admin.php?act=booking');
            exit;
        }

        require_once PATH_ADMIN . 'booking/edit_status.php';
    }

    // Lịch sử
    public function history(){
        $id = intval($_GET['id'] ?? 0);
        $rows = $this->historyModel->getByBooking($id);
        require_once PATH_ADMIN . 'booking/history.php';
    }
}

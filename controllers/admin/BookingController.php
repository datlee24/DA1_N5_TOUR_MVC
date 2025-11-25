<?php
class BookingController {
    protected $bookingModel;
    protected $tourModel;
    protected $scheduleModel;
    protected $guideModel;
    protected $customerModel;
    protected $tourCustomerModel;

    public function __construct() {
        checkIsAdmin();

        if (!isset($_SESSION)) session_start();

        $this->bookingModel      = new BookingModel();
        $this->tourModel         = new TourModel();
        $this->scheduleModel     = new ScheduleModel();
        $this->guideModel        = new GuideModel();
        $this->customerModel     = new CustomerModel();
        $this->tourCustomerModel = new TourCustomerModel();
    }

    public function list() {
        $bookings = $this->bookingModel->getAll();
        require_once PATH_ADMIN . "booking/list.php";
    }

    // Reset wizard
    protected function resetWizard() {
        unset($_SESSION['booking']);
    }

    // =============================
    // STEP 1 — Chọn tour
    // =============================
    public function step1() {
        $this->resetWizard();
        $tours = $this->tourModel->getAllActive();
        require_once PATH_ADMIN . "booking/step1.php";
    }

    public function step1Save() {
        $_SESSION['booking']['tour_id'] = intval($_POST['tour_id'] ?? 0);
        header("Location: admin.php?act=booking-step2");
        exit;
    }

    // =============================
    // STEP 2 — Chọn lịch khởi hành
    // =============================
    public function step2() {
        $tour_id = $_SESSION['booking']['tour_id'] ?? null;
        if (!$tour_id) {
            header("Location: admin.php?act=booking-step1");
            exit;
        }

        $schedules = $this->scheduleModel->getByTour($tour_id);
        require_once PATH_ADMIN . "booking/step2.php";
    }

    public function step2Save() {
        $schedule_id = intval($_POST['schedule_id'] ?? 0);
        $_SESSION['booking']['schedule_id'] = $schedule_id;

        $s = $this->scheduleModel->find($schedule_id);
        if (!$s) {
            $_SESSION['error'] = "Lịch không tồn tại!";
            header("Location: admin.php?act=booking-step2");
            exit;
        }

        // Nếu lịch đã có HDV → bỏ qua Step 3
        if (!empty($s['guide_id'])) {
            $_SESSION['booking']['guide_id'] = intval($s['guide_id']);
            header("Location: admin.php?act=booking-step4");
        } else {
            header("Location: admin.php?act=booking-step3");
        }
        exit;
    }

    // =============================
    // STEP 3 — Chọn HDV
    // =============================
    public function step3() {
        $schedule_id = $_SESSION['booking']['schedule_id'] ?? null;
        if (!$schedule_id) {
            header("Location: admin.php?act=booking-step2");
            exit;
        }

        $schedule = $this->scheduleModel->find($schedule_id);
        $guides = $this->guideModel->getAllWithUser();
        require_once PATH_ADMIN . "booking/step3.php";
    }

    public function step3Save() {
        $guide_id = intval($_POST['guide_id'] ?? 0);
        $schedule = $this->scheduleModel->find($_SESSION['booking']['schedule_id']);

        // Check trùng lịch
        if ($this->guideModel->isBusy($guide_id, $schedule['start_date'], $schedule['end_date'])) {
            $_SESSION['error'] = "HDV đã trùng lịch!";
            header("Location: admin.php?act=booking-step3");
            exit;
        }

        $_SESSION['booking']['guide_id'] = $guide_id;
        header("Location: admin.php?act=booking-step4");
        exit;
    }

    // =============================
    // STEP 4 — Chọn khách hàng
    // =============================
    public function step4() {
        $schedule_id = $_SESSION['booking']['schedule_id'];
        $schedule = $this->scheduleModel->find($schedule_id);
        $customers = $this->customerModel->getAll();

        require_once PATH_ADMIN . "booking/step4.php";
    }

    public function step4Save() {
        $customer_ids = array_map('intval', $_POST['customer_ids'] ?? []);
        $num_people   = count($customer_ids);

        $schedule = $this->scheduleModel->find($_SESSION['booking']['schedule_id']);

        // Check sức chứa tour
        $booked = $this->scheduleModel->getBookedSeats($schedule['schedule_id']);
        if ($booked + $num_people > 40) {
            $_SESSION['error'] = "Vượt quá sức chứa. Còn " . (40 - $booked) . " chỗ.";
            header("Location: admin.php?act=booking-step4");
            exit;
        }

        // Check khách hàng trùng lịch
        foreach ($customer_ids as $cid) {
            if ($this->customerModel->customerIsBusy($cid, $schedule['start_date'], $schedule['end_date'])) {
                $_SESSION['error'] = "Khách #$cid đã có booking trùng lịch";
                header("Location: admin.php?act=booking-step4");
                exit;
            }
        }

        $_SESSION['booking']['customer_ids'] = $customer_ids;
        $_SESSION['booking']['num_people']   = $num_people;

        header("Location: admin.php?act=booking-step5");
        exit;
    }

    // =============================
    // STEP 5 — Xác nhận booking
    // =============================
public function step5() {
    $booking = $_SESSION['booking'] ?? [];

    $tour     = $this->tourModel->find($booking['tour_id']);
    $schedule = $this->scheduleModel->find($booking['schedule_id']);
    $guide    = !empty($booking['guide_id']) ? $this->guideModel->findWithUser($booking['guide_id']) : null;

    // Danh sách khách hàng
    $customers = [];
    if (!empty($booking['customer_ids'])) {
        foreach ($booking['customer_ids'] as $cid) {
            $customers[] = $this->customerModel->find($cid);
        }
    }

    // Gán biến để view không bị lỗi
    $num_people  = $booking['num_people'] ?? 0;
    $total_price = $booking['total_price'] ?? 0;

    require_once PATH_ADMIN . "booking/step5.php";
}


    // =============================
    // FINISH — Lưu booking vào DB
    // =============================
    public function finish() {
        $b = $_SESSION['booking'];

        $data = [
            'customer_id'   => $b['customer_ids'][0] ?? null,
            'tour_id'       => $b['tour_id'],
            'schedule_id'   => $b['schedule_id'],
            'booking_date'  => date("Y-m-d H:i:s"),
            'num_people'    => $b['num_people'],
            'total_price'   => floatval($_POST['total_price'] ?? 0),
            'status'        => 'pending',
            'payment_status'=> 'unpaid',
            'note'          => $_POST['note'] ?? ""
        ];

        $booking_id = $this->bookingModel->create($data);

        // LƯU KHÁCH HÀNG VÀO tour_customer (không dùng booking_id)
        foreach ($b['customer_ids'] as $cid) {
            $this->tourCustomerModel->addCustomer($b['schedule_id'], $cid);
        }

        // Lưu HDV vào schedule
        if (!empty($b['guide_id'])) {
            $this->scheduleModel->assignGuide($b['schedule_id'], $b['guide_id']);
        }

        unset($_SESSION['booking']);
        $_SESSION['success'] = "Tạo booking thành công!";
        header("Location: admin.php?act=booking");
        exit;
    }

    // =============================
    // VIEW BOOKING DETAILS
    // =============================
    public function view() {
        $id = intval($_GET['id'] ?? 0);
        $booking = $this->bookingModel->find($id);

        $schedule = $this->scheduleModel->find($booking['schedule_id']);
        $guide    = (!empty($schedule['guide_id'])) ? $this->guideModel->findWithUser($schedule['guide_id']) : null;

        $customers = $this->tourCustomerModel->getBySchedule($booking['schedule_id']);

        require_once PATH_ADMIN . "booking/view.php";
    }

    // =============================
    // CANCEL
    // =============================
    public function cancel() {
        $id = intval($_GET['id'] ?? 0);
        $this->bookingModel->cancel($id);
        $_SESSION['success'] = "Đã hủy booking";
        header("Location: admin.php?act=booking");
        exit;
    }

    // =============================
    // AJAX — Lấy lịch tour
    // =============================
    public function ajaxSchedule() {
        header("Content-Type: application/json; charset=utf-8");
        $tour_id = intval($_GET['tour_id'] ?? 0);
        $data = $this->scheduleModel->getByTour($tour_id);
        echo json_encode(['ok' => true, 'data' => $data]);
        exit;
    }

    // AJAX — Lấy danh sách HDV + tình trạng rảnh/bận
    public function ajaxGuide() {
        header("Content-Type: application/json; charset=utf-8");

        $schedule_id = intval($_GET['schedule_id'] ?? 0);
        $schedule = $this->scheduleModel->find($schedule_id);

        $guides = $this->guideModel->getAllWithUser();
        foreach ($guides as &$g) {
            $g['available'] = !$this->guideModel->isBusy(
                $g['guide_id'],
                $schedule['start_date'],
                $schedule['end_date']
            );
        }

        echo json_encode(['ok' => true, 'data' => $guides]);
        exit;
    }

    // AJAX — Tạo khách hàng
    public function ajaxCreateCustomer() {
        header("Content-Type: application/json; charset=utf-8");

        $fullname = trim($_POST['fullname'] ?? "");
        if ($fullname == "") {
            echo json_encode(['ok' => false, 'msg' => "Tên không được để trống"]);
            exit;
        }

        $data = [
            'fullname' => $fullname,
            'phone'    => trim($_POST['phone'] ?? ""),
            'email'    => trim($_POST['email'] ?? "")
        ];

        $id = $this->customerModel->create($data);
        $customer = $this->customerModel->find($id);

        echo json_encode(['ok' => true, 'data' => $customer]);
        exit;
    }
}
?>

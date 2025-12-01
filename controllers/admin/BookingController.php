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

    protected function resetWizard() {
        unset($_SESSION['booking']);
    }

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

    // Step 2: CHO PHÉP CHỌN SCHEDULE TỪ DANH SÁCH HOẶC TẠO LỊCH MỚI
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
        $tour_id = $_SESSION['booking']['tour_id'] ?? null;
        if (!$tour_id) { header("Location: admin.php?act=booking-step1"); exit; }

        // Nếu người dùng gửi start_date + end_date -> tạo lịch mới
        $start = trim($_POST['start_date'] ?? '');
        $end   = trim($_POST['end_date'] ?? '');
        $schedule_id = intval($_POST['schedule_id'] ?? 0);

        if ($start && $end) {
            // tạo schedule mới
            $sid = $this->scheduleModel->create([
                'tour_id' => $tour_id,
                'start_date' => $start,
                'end_date' => $end,
                'meeting_point' => $_POST['meeting_point'] ?? null
            ]);
            $_SESSION['booking']['schedule_id'] = $sid;
        } elseif ($schedule_id) {
            $_SESSION['booking']['schedule_id'] = $schedule_id;
        } else {
            $_SESSION['error'] = "Vui lòng chọn lịch hoặc nhập ngày để tạo lịch mới.";
            header("Location: admin.php?act=booking-step2");
            exit;
        }

        $s = $this->scheduleModel->find($_SESSION['booking']['schedule_id']);
        if (!$s) {
            $_SESSION['error'] = "Lịch không tồn tại!";
            header("Location: admin.php?act=booking-step2");
            exit;
        }

        if (!empty($s['guide_id'])) {
            $_SESSION['booking']['guide_id'] = intval($s['guide_id']);
            header("Location: admin.php?act=booking-step4");
        } else {
            header("Location: admin.php?act=booking-step3");
        }
        exit;
    }

    // Step3: chọn HDV, hiển thị lịch bận của từng HDV
    public function step3() {
        $schedule_id = $_SESSION['booking']['schedule_id'] ?? null;
        if (!$schedule_id) {
            header("Location: admin.php?act=booking-step2");
            exit;
        }

        $schedule = $this->scheduleModel->find($schedule_id);
        $guides = $this->guideModel->getAllWithUser();

        // Thu thêm lịch của từng hdv để hiển thị
        foreach ($guides as &$g) {
            $g['schedules'] = $this->guideModel->getSchedule($g['guide_id']);
            $g['available'] = !$this->guideModel->isBusy($g['guide_id'], $schedule['start_date'], $schedule['end_date']);
        }

        require_once PATH_ADMIN . "booking/step3.php";
    }

    public function step3Save() {
        $guide_id = intval($_POST['guide_id'] ?? 0);
        $schedule = $this->scheduleModel->find($_SESSION['booking']['schedule_id']);

        if ($this->guideModel->isBusy($guide_id, $schedule['start_date'], $schedule['end_date'])) {
            $_SESSION['error'] = "HDV đã trùng lịch!";
            header("Location: admin.php?act=booking-step3");
            exit;
        }

        $_SESSION['booking']['guide_id'] = $guide_id;
        header("Location: admin.php?act=booking-step4");
        exit;
    }

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

        $booked = $this->scheduleModel->getBookedSeats($schedule['schedule_id']);
        if ($booked + $num_people > 40) {
            $_SESSION['error'] = "Vượt quá sức chứa. Còn " . (40 - $booked) . " chỗ.";
            header("Location: admin.php?act=booking-step4");
            exit;
        }

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

    public function step5() {
        $booking = $_SESSION['booking'] ?? [];

        $tour     = $this->tourModel->find($booking['tour_id']);
        $schedule = $this->scheduleModel->find($booking['schedule_id']);
        $guide    = !empty($booking['guide_id']) ? $this->guideModel->findWithUser($booking['guide_id']) : null;

        $customers = [];
        if (!empty($booking['customer_ids'])) {
            foreach ($booking['customer_ids'] as $cid) {
                $customers[] = $this->customerModel->find($cid);
            }
        }

        $num_people  = $booking['num_people'] ?? 0;
        $total_price = $booking['total_price'] ?? 0;

        require_once PATH_ADMIN . "booking/step5.php";
    }

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

        foreach ($b['customer_ids'] as $cid) {
            $this->tourCustomerModel->addCustomer($b['schedule_id'], $cid);
        }

        if (!empty($b['guide_id'])) {
            $this->scheduleModel->assignGuide($b['schedule_id'], $b['guide_id']);
        }

        unset($_SESSION['booking']);
        $_SESSION['success'] = "Tạo booking thành công!";
        header("Location: admin.php?act=booking");
        exit;
    }

    public function view() {
        $id = intval($_GET['id'] ?? 0);
        $booking = $this->bookingModel->find($id);

        // nếu booking rỗng, chuyển về list
        if (!$booking) {
            $_SESSION['error'] = "Booking không tồn tại";
            header("Location: admin.php?act=booking");
            exit;
        }

        $schedule = $this->scheduleModel->find($booking['schedule_id']);
        $guide    = null;
        if (!empty($schedule['guide_id'])) {
            $guide = $this->guideModel->findWithUser($schedule['guide_id']);
        } elseif (!empty($booking['guide_id'])) {
            // fallback: booking row might contain guide fields from join
            $guide = [
                'fullname' => $booking['guide_name'] ?? null,
                'phone' => $booking['guide_phone'] ?? null
            ];
        }

        $customers = $this->tourCustomerModel->getBySchedule($booking['schedule_id']);

        require_once PATH_ADMIN . "booking/view.php";
    }

    public function cancel() {
        $id = intval($_GET['id'] ?? 0);
        $this->bookingModel->cancel($id);
        $_SESSION['success'] = "Đã hủy booking";
        header("Location: admin.php?act=booking");
        exit;
    }

    // Mới: xác nhận booking
    public function confirm() {
        $id = intval($_GET['id'] ?? 0);
        $this->bookingModel->updateStatus($id, 'confirmed');
        $_SESSION['success'] = "Đã xác nhận booking";
        header("Location: admin.php?act=booking");
        exit;
    }

    public function ajaxSchedule() {
        header("Content-Type: application/json; charset=utf-8");
        $tour_id = intval($_GET['tour_id'] ?? 0);
        $data = $this->scheduleModel->getByTour($tour_id);
        echo json_encode(['ok' => true, 'data' => $data]);
        exit;
    }

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
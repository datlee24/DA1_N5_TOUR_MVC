
<?php
// controllers/BookingController.php
class BookingController
{
    protected $bookingModel;
    protected $tourModel;
    protected $scheduleModel;
    protected $guideModel;
    protected $driverModel;
    protected $customerModel;
    protected $tourCustomerModel;
    protected $attendanceModel;
    protected $hotelModel;

    public function __construct()
    {
        checkIsAdmin();
        if (!isset($_SESSION)) session_start();

        $this->bookingModel      = new BookingModel();
        $this->tourModel         = new TourModel();
        $this->scheduleModel     = new ScheduleModel();
        $this->guideModel        = new GuideModel();
        $this->driverModel       = new DriverModel();
        $this->customerModel     = new CustomerModel();
        $this->tourCustomerModel = new TourCustomerModel();
        $this->attendanceModel   = new AttendanceModel();
        $this->hotelModel        = new HotelModel();

        // Auto update booking status on each controller init
        $this->bookingModel->autoUpdateStatus();
    }

    public function list()
    {
        $bookings = $this->bookingModel->getAll();
        require_once PATH_ADMIN . "booking/list.php";
    }

    protected function resetWizard()
    {
        unset($_SESSION['booking']);
    }

    // STEP1: chọn tour (và có thể chọn driver ban đầu)
    public function step1()
    {
        $this->resetWizard();
        $tours = $this->tourModel->getAllActive();
        $drivers = $this->driverModel->getAll();
        require_once PATH_ADMIN . "booking/step1.php";
    }

    public function step1Save()
    {
        $tour_id = intval($_POST['tour_id'] ?? 0);
        $driver_id = intval($_POST['driver_id'] ?? 0);
        $hotel_id = intval($_POST['hotel_id'] ?? 0); // mới

        // Server-side: require hotel and driver
        if (!$hotel_id) {
            $_SESSION['error'] = "Vui lòng chọn khách sạn.";
            header("Location: admin.php?act=booking-step1");
            exit;
        }
        if (!$driver_id) {
            $_SESSION['error'] = "Vui lòng chọn tài xế.";
            header("Location: admin.php?act=booking-step1");
            exit;
        }

        // validate hotel and driver exist
        $hotel = $this->hotelModel->find($hotel_id);
        if (!$hotel) {
            $_SESSION['error'] = "Khách sạn không tồn tại.";
            header("Location: admin.php?act=booking-step1");
            exit;
        }
        $driver = $this->driverModel->find($driver_id);
        if (!$driver) {
            $_SESSION['error'] = "Tài xế không tồn tại.";
            header("Location: admin.php?act=booking-step1");
            exit;
        }

        if (!$tour_id) {
            $_SESSION['error'] = "Vui lòng chọn tour.";
            header("Location: admin.php?act=booking-step1");
            exit;
        }

        $_SESSION['booking']['tour_id'] = $tour_id;
        if ($driver_id) $_SESSION['booking']['driver_id'] = $driver_id;
        if ($hotel_id) $_SESSION['booking']['hotel_id'] = $hotel_id; // lưu hotel

        header("Location: admin.php?act=booking-step2");
        exit;
    }

    // STEP2
    public function step2()
    {
        $tour_id = $_SESSION['booking']['tour_id'] ?? null;
        if (!$tour_id) {
            header("Location: admin.php?act=booking-step1");
            exit;
        }
        $schedules = $this->scheduleModel->getByTour($tour_id);
        require_once PATH_ADMIN . "booking/step2.php";
    }

    public function step2Save()
    {
        $tour_id = $_SESSION['booking']['tour_id'] ?? null;
        if (!$tour_id) {
            header("Location: admin.php?act=booking-step1");
            exit;
        }

        $schedule_id = intval($_POST['schedule_id'] ?? 0);
        $driver_id = intval($_POST['driver_id'] ?? ($_SESSION['booking']['driver_id'] ?? 0));
        $hotel_id = $_SESSION['booking']['hotel_id'] ?? null;

        if ($schedule_id) {
            $s = $this->scheduleModel->find($schedule_id);
            if (!$s) {
                $_SESSION['error'] = "Lịch không tồn tại.";
                header("Location: admin.php?act=booking-step2");
                exit;
            }
            // nếu chọn driver và driver bận -> lỗi
            if ($driver_id) {
                if ($this->driverModel->isBusy($driver_id, $s['start_date'], $s['end_date'])) {
                    $_SESSION['error'] = "Tài xế đã có lịch trùng vào khoảng thời gian này. Vui lòng chọn tài xế khác.";
                    header("Location: admin.php?act=booking-step2");
                    exit;
                }
                // gán driver vào schedule
                $this->scheduleModel->assignDriver($schedule_id, $driver_id);
            }
            // nếu step1 có chọn hotel, gán vào schedule
            if (!empty($hotel_id)) {
                $this->scheduleModel->assignHotel($schedule_id, $hotel_id);
            }

            $_SESSION['booking']['schedule_id'] = $schedule_id;
        } else {
            // tạo lịch mới
            $start = trim($_POST['start_date'] ?? '');
            $end   = trim($_POST['end_date'] ?? '');
            if (!$start || !$end) {
                $_SESSION['error'] = "Vui lòng nhập ngày bắt đầu và kết thúc để tạo lịch mới.";
                header("Location: admin.php?act=booking-step2");
                exit;
            }

            // check driver trùng cho lịch mới
            if ($driver_id) {
                if ($this->driverModel->isBusy($driver_id, $start, $end)) {
                    $_SESSION['error'] = "Tài xế đã có lịch trùng vào khoảng thời gian này. Vui lòng chọn tài xế khác.";
                    header("Location: admin.php?act=booking-step2");
                    exit;
                }
            }

            $sid = $this->scheduleModel->create([
                'tour_id' => $tour_id,
                'start_date' => $start,
                'end_date' => $end,
                'meeting_point' => $_POST['meeting_point'] ?? null,
                'guide_id' => null,
                'driver_id' => $driver_id,
                'hotel_id' => $hotel_id,
                'notes' => $_POST['notes'] ?? null
            ]);
            $_SESSION['booking']['schedule_id'] = $sid;
        }

        // if schedule has guide already, go to step4 (choose customers), else choose guide step3
        $s = $this->scheduleModel->find($_SESSION['booking']['schedule_id']);
        if (!empty($s['guide_id'])) {
            $_SESSION['booking']['guide_id'] = intval($s['guide_id']);
            header("Location: admin.php?act=booking-step4");
        } else {
            header("Location: admin.php?act=booking-step3");
        }
        exit;
    }

    // STEP3
    public function step3()
    {
        $schedule_id = $_SESSION['booking']['schedule_id'] ?? null;
        if (!$schedule_id) {
            header("Location: admin.php?act=booking-step2");
            exit;
        }

        $schedule = $this->scheduleModel->find($schedule_id);
        $guides = $this->guideModel->getAllWithUser();

        foreach ($guides as &$g) {
            $g['available'] = !$this->guideModel->isBusy($g['guide_id'], $schedule['start_date'], $schedule['end_date']);
            // load guide schedules optionally (hiện chỉ các lịch có booking)
            $g['schedules'] = $this->guideModel->getSchedule($g['guide_id'], true);
        }
        unset($g);

        require_once PATH_ADMIN . "booking/step3.php";
    }

    public function step3Save()
    {
        $guide_id = intval($_POST['guide_id'] ?? 0);
        $schedule = $this->scheduleModel->find($_SESSION['booking']['schedule_id']);

        if ($this->guideModel->isBusy($guide_id, $schedule['start_date'], $schedule['end_date'])) {
            $_SESSION['error'] = "HDV đã trùng lịch!";
            header("Location: admin.php?act=booking-step3");
            exit;
        }

        $_SESSION['booking']['guide_id'] = $guide_id;
        // assign guide to schedule
        $this->scheduleModel->assignGuide($_SESSION['booking']['schedule_id'], $guide_id);

        header("Location: admin.php?act=booking-step4");
        exit;
    }

    // STEP4
    public function step4()
    {
        $schedule_id = $_SESSION['booking']['schedule_id'] ?? null;
        if (!$schedule_id) {
            header("Location: admin.php?act=booking-step2");
            exit;
        }

        $schedule = $this->scheduleModel->find($schedule_id);
        $customers = $this->customerModel->getAll();

        $busyIds = $this->customerModel->getBusyCustomers($schedule['start_date'], $schedule['end_date']);
        $filteredCustomers = array_values(array_filter($customers, function ($c) use ($busyIds) {
            return !in_array($c['customer_id'], $busyIds);
        }));

        require_once PATH_ADMIN . "booking/step4.php";
    }

    public function step4Save()
    {
        $customer_ids = array_map('intval', $_POST['customer_ids'] ?? []);
        $num_people   = count($customer_ids);

        if ($num_people <= 0) {
            $_SESSION['error'] = "Vui lòng chọn ít nhất 1 khách.";
            header("Location: admin.php?act=booking-step4");
            exit;
        }

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

        // calculate total price automatically
        $tour = $this->tourModel->find($_SESSION['booking']['tour_id']);
        $pricePer = floatval($tour['price'] ?? 0);
        $_SESSION['booking']['price_per_person'] = $pricePer;
        $_SESSION['booking']['total_price'] = $pricePer * $num_people;

        header("Location: admin.php?act=booking-step5");
        exit;
    }

    // STEP5
    public function step5()
    {
        $booking = $_SESSION['booking'] ?? [];

        $tour     = $this->tourModel->find($booking['tour_id']);
        $schedule = $this->scheduleModel->find($booking['schedule_id']);
        $guide    = !empty($booking['guide_id']) ? $this->guideModel->findWithUser($booking['guide_id']) : null;
        $driver   = !empty($schedule['driver_id']) ? $this->driverModel->find($schedule['driver_id']) : null;

        $customers = [];
        if (!empty($booking['customer_ids'])) {
            foreach ($booking['customer_ids'] as $cid) {
                $customers[] = $this->customerModel->find($cid);
            }
        }

        $num_people  = $booking['num_people'] ?? 0;
        $price_per   = $booking['price_per_person'] ?? ($tour['price'] ?? 0);
        $total_price = $booking['total_price'] ?? ($price_per * $num_people);

        $hotels = $this->hotelModel->getByTour($tour['tour_id']);
        $assignedHotel = null;
        if (!empty($schedule['hotel_id'])) {
            $assignedHotel = $this->hotelModel->find($schedule['hotel_id']);
        } elseif (!empty($booking['hotel_id'])) {
            $assignedHotel = $this->hotelModel->find($booking['hotel_id']);
        }

        require_once PATH_ADMIN . "booking/step5.php";
    }

    public function finish()
    {
        if (empty($_SESSION['booking'])) {
            $_SESSION['error'] = "Không có thông tin booking.";
            header("Location: admin.php?act=booking-step1");
            exit;
        }
        $b = $_SESSION['booking'];

        // pick a main customer (first selected) as booking.customer_id
        $main_customer = $b['customer_ids'][0] ?? null;

        $data = [
            'customer_id'   => $main_customer,
            'tour_id'       => $b['tour_id'],
            'schedule_id'   => $b['schedule_id'],
            'booking_date'  => date("Y-m-d H:i:s"),
            'num_people'    => $b['num_people'],
            'total_price'   => floatval($_POST['total_price'] ?? $b['total_price'] ?? 0),
            'status'        => 'upcoming',
            'payment_status' => $_POST['payment_status'] ?? 'unpaid',
            'note'          => $_POST['note'] ?? ""
        ];

        $booking_id = $this->bookingModel->create($data);

        // add tour_customer entries for each customer
        foreach ($b['customer_ids'] as $cid) {
            $this->tourCustomerModel->addCustomer($b['schedule_id'], $cid);
        }

        // assign guide + driver to schedule if present
        if (!empty($b['guide_id'])) {
            $this->scheduleModel->assignGuide($b['schedule_id'], $b['guide_id']);
        }
        if (!empty($b['driver_id'])) {
            $this->scheduleModel->assignDriver($b['schedule_id'], $b['driver_id']);
        }
        // assign hotel if present
        if (!empty($b['hotel_id'])) {
            $this->scheduleModel->assignHotel($b['schedule_id'], $b['hotel_id']);
        }

        unset($_SESSION['booking']);
        $_SESSION['success'] = "Tạo booking thành công!";
        header("Location: admin.php?act=booking");
        exit;
    }

    // view booking detail
    public function view()
    {
        $id = intval($_GET['id'] ?? 0);
        $booking = $this->bookingModel->find($id);

        if (!$booking) {
            $_SESSION['error'] = "Booking không tồn tại";
            header("Location: admin.php?act=booking");
            exit;
        }

        $schedule = $this->scheduleModel->find($booking['schedule_id']);
        $guide    = null;
        if (!empty($schedule['guide_id'])) {
            $guide = $this->guideModel->findWithUser($schedule['guide_id']);
        }

        $driver = null;
        if (!empty($schedule['driver_id'])) {
            $driver = $this->driverModel->find($schedule['driver_id']);
        }

        // Use attendance records (latest) when available so admin sees guide's marks
        $customers = $this->attendanceModel->listBySchedule($booking['schedule_id']);
        // normalize field name expected by views
        foreach ($customers as &$c) {
            if (isset($c['status'])) $c['attendance_status'] = $c['status'];
            $c['attendance_status'] = $c['attendance_status'] ?? 'unknown';
        }
        unset($c);
        $assignedHotel = null;
        if (!empty($schedule['hotel_id'])) {
            $assignedHotel = $this->hotelModel->find($schedule['hotel_id']);
        }

        require_once PATH_ADMIN . "booking/view.php";
    }

    // AJAX endpoints
    public function ajaxSchedule()
    {
        header("Content-Type: application/json; charset=utf-8");
        $tour_id = intval($_GET['tour_id'] ?? 0);
        $data = $this->scheduleModel->getByTour($tour_id);
        echo json_encode(['ok' => true, 'data' => $data]);
        exit;
    }

    public function ajaxDriverAvailability()
    {
        header("Content-Type: application/json; charset=utf-8");
        $driver_id = intval($_GET['driver_id'] ?? 0);
        $start = $_GET['start'] ?? null;
        $end = $_GET['end'] ?? null;
        if (!$driver_id || !$start || !$end) {
            echo json_encode(['ok' => false, 'msg' => 'Thiếu tham số']);
            exit;
        }
        $busy = $this->driverModel->isBusy($driver_id, $start, $end);
        echo json_encode(['ok' => true, 'busy' => $busy]);
        exit;
    }

    public function ajaxCreateCustomer()
    {
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

    // AJAX: Lấy hotels theo tour (dùng ở step1 khi chọn tour)
    public function ajaxHotels()
    {
        header("Content-Type: application/json; charset=utf-8");
        $tour_id = intval($_GET['tour_id'] ?? 0);
        if (!$tour_id) {
            echo json_encode(['ok' => false, 'data' => []]);
            exit;
        }
        $hotels = $this->hotelModel->getByTour($tour_id);
        echo json_encode(['ok' => true, 'data' => $hotels]);
        exit;
    }

    // POST AJAX: thêm khách (customer_id) vào schedule/booking hiện tại
    public function addCustomerToBooking()
    {
        header("Content-Type: application/json; charset=utf-8");
        $schedule_id = intval($_POST['schedule_id'] ?? 0);
        $customer_id = intval($_POST['customer_id'] ?? 0);

        if (!$schedule_id || !$customer_id) {
            echo json_encode(['ok' => false, 'msg' => 'Thiếu tham số']);
            exit;
        }

        // kiểm tra trùng lịch cho khách
        $schedule = $this->scheduleModel->find($schedule_id);
        if (!$schedule) {
            echo json_encode(['ok' => false, 'msg' => 'Lịch không tồn tại']);
            exit;
        }

        if ($this->customerModel->customerIsBusy($customer_id, $schedule['start_date'], $schedule['end_date'])) {
            echo json_encode(['ok' => false, 'msg' => 'Khách đã có lịch trùng.']);
            exit;
        }

        // check nếu đã tồn tại trong tour_customer -> ignore
        if (!$this->tourCustomerModel->exists($schedule_id, $customer_id)) {
            $this->tourCustomerModel->addCustomer($schedule_id, $customer_id);
        }

        // trả về danh sách khách cập nhật
        $customers = $this->tourCustomerModel->getBySchedule($schedule_id);
        echo json_encode(['ok' => true, 'data' => $customers]);
        exit;
    }

    // AJAX endpoint: update payment status (used in booking view)
    public function updatePaymentStatus()
    {
        header("Content-Type: application/json; charset=utf-8");
        $booking_id = intval($_POST['booking_id'] ?? 0);
        $ps = $_POST['payment_status'] ?? '';

        if (!$booking_id || !$ps) {
            echo json_encode(['ok' => false, 'msg' => 'Thiếu tham số']);
            exit;
        }

        $ok = $this->bookingModel->updatePaymentStatus($booking_id, $ps);
        echo json_encode(['ok' => $ok]);
        exit;
    }
}

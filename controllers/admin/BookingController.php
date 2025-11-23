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
        $this->bookingModel = new BookingModel();
        $this->tourModel = new TourModel();
        $this->scheduleModel = new ScheduleModel();
        $this->guideModel = new GuideModel();
        $this->customerModel = new CustomerModel();
        $this->tourCustomerModel = new TourCustomerModel();
    }

    public function list() {
        $bookings = $this->bookingModel->getAll();
        require_once PATH_ADMIN . 'booking/list.php';
    }

    public function add() {
        $tours = $this->tourModel->getAll();
        $guides = $this->guideModel->getAll();
        $customers = $this->customerModel->getAll();
        require_once PATH_ADMIN . 'booking/add.php';
    }

    public function store() {
        $tour_id = $_POST['tour_id'];
        $schedule_id = $_POST['schedule_id'];
        $guide_id = $_POST['guide_id'] ?? null;
        $customer_ids = $_POST['customer_ids'] ?? [];
        $num_people = $_POST['num_people'];

        $schedule = $this->scheduleModel->find($schedule_id);

        if (!$schedule) {
            $_SESSION['error'] = "Lịch khởi hành không tồn tại!";
            header("Location: admin.php?act=booking-add");
            exit;
        }

        $start = $schedule['start_date'];
        $end = $schedule['end_date'];

        // Kiểm tra xung đột HDV
        if ($guide_id && $this->guideModel->hasConflict($guide_id, $start, $end)) {
            $_SESSION['error'] = "HDV đã bận tour khác trong thời gian này!";
            header("Location: admin.php?act=booking-add");
            exit;
        }

        // Kiểm tra capacity 40
        $booked = $this->scheduleModel->getBookedSeats($schedule_id);
        if ($booked + $num_people > 40) {
            $_SESSION['error'] = "Tour đã đầy. Chỉ còn " . (40 - $booked) . " chỗ.";
            header("Location: admin.php?act=booking-add");
            exit;
        }

        // Tạo booking
        $data = [
            'customer_id' => $customer_ids[0], // người đại diện
            'tour_id' => $tour_id,
            'schedule_id' => $schedule_id,
            'booking_date'=> date('Y-m-d H:i:s'),
            'num_people'=> $num_people,
            'total_price'=> $_POST['total_price'] ?? 0,
            'status'=>'confirmed',
            'payment_status'=>'unpaid',
            'note'=>''
        ];

        $booking_id = $this->bookingModel->create($data);

        // Gắn từng khách vào tour_customer
        foreach ($customer_ids as $cid) {
            $this->tourCustomerModel->addCustomer($schedule_id, $cid);
        }

        // Gắn HDV vào departure_schedule
        if ($guide_id) {
            $conn = connectDB();
            $stmt = $conn->prepare("UPDATE departure_schedule SET guide_id = :gid WHERE schedule_id = :sid");
            $stmt->execute(['gid'=>$guide_id, 'sid'=>$schedule_id]);
        }

        $_SESSION['success'] = "Tạo booking thành công!";
        header("Location: admin.php?act=booking");
        exit;
    }
}

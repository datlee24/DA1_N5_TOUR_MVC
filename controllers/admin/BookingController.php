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
        $this->bookingModel      = new BookingModel();
        $this->tourModel         = new TourModel();
        $this->scheduleModel     = new ScheduleModel();
        $this->guideModel        = new GuideModel();
        $this->customerModel     = new CustomerModel();
        $this->tourCustomerModel = new TourCustomerModel();
    }

    // --- LIST ---
    public function list() {
        $bookings = $this->bookingModel->getAll();
        require_once PATH_ADMIN . "booking/list.php";
    }

    // --- ADD FORM ---
    public function add() {
        $tours      = $this->tourModel->getAll();
        $guides     = $this->guideModel->getAll();
        $customers  = $this->customerModel->getAll();
        require_once PATH_ADMIN . "booking/add.php";
    }

    // --- STORE BOOKING ---
    public function store() {

        $tour_id      = $_POST['tour_id'];
        $schedule_id  = $_POST['schedule_id'];
        $guide_id     = $_POST['guide_id'] ?? null;
        $customer_ids = $_POST['customer_ids'] ?? [];
        $num_people   = $_POST['num_people'];
        $total_price  = $_POST['total_price'] ?? 0;

        $schedule = $this->scheduleModel->find($schedule_id);

        if (!$schedule) {
            $_SESSION['error'] = "Lịch khởi hành không tồn tại!";
            header("Location: admin.php?act=booking-add");
            exit;
        }

        $start = $schedule['start_date'];
        $end   = $schedule['end_date'];

        // HDV conflict
        if ($guide_id && $this->guideModel->hasConflict($guide_id, $start, $end)) {
            $_SESSION['error'] = "HDV đã bận tour khác!";
            header("Location: admin.php?act=booking-add");
            exit;
        }

        // CAPACITY CHECK = 40
        $booked = $this->scheduleModel->getBookedSeats($schedule_id);

        if ($booked + $num_people > 40) {
            $_SESSION['error'] = "Tour đầy, còn " . (40 - $booked) . " chỗ.";
            header("Location: admin.php?act=booking-add");
            exit;
        }

        // Create booking
        $data = [
            'customer_id'   => $customer_ids[0] ?? null,
            'tour_id'       => $tour_id,
            'schedule_id'   => $schedule_id,
            'booking_date'  => date("Y-m-d H:i:s"),
            'num_people'    => $num_people,
            'total_price'   => $total_price,
            'status'        => "confirmed",
            'payment_status'=> "unpaid",
            'note'          => ""
        ];

        $booking_id = $this->bookingModel->create($data);

        // Add customer to tour
        foreach ($customer_ids as $cid) {
            $this->tourCustomerModel->addCustomer($schedule_id, $cid);
        }

        // Assign guide
        if ($guide_id) {
            $this->scheduleModel->assignGuide($schedule_id, $guide_id);
        }

        $_SESSION['success'] = "Tạo booking thành công!";
        header("Location: admin.php?act=booking");
        exit;
    }

    // --- VIEW BOOKING ---
public function view() {
    $id = $_GET['id'];
    $booking = $this->bookingModel->find($id);

    if (!$booking) {
        $_SESSION['error'] = "Booking không tồn tại!";
        header("Location: admin.php?act=booking");
        exit;
    }
    // Lấy danh sách khách theo schedule_id
    $customers = $this->customerModel->getBySchedule($booking['schedule_id']);

    require_once PATH_ADMIN . "booking/view.php";
}

    // --- CANCEL BOOKING ---
    public function cancel() {
        $id = $_GET['id'];
        $this->bookingModel->cancel($id);

        $_SESSION['success'] = "Booking đã bị hủy!";
        header("Location: admin.php?act=booking");
    }

    // --- AJAX: get schedule by tour ---
    public function ajaxSchedule() {
        $tour_id = $_GET['tour_id'];
        echo json_encode($this->scheduleModel->getByTour($tour_id));
    }

    // --- AJAX: get guides by tour ---
    public function ajaxGuide() {
        $tour_id = $_GET['tour_id'];
        echo json_encode($this->guideModel->getByTour($tour_id));
    }

    // --- AJAX: create customer ---
    public function ajaxCreateCustomer() {
        $data = [
            'fullname' => $_POST['fullname'],
            'phone'    => $_POST['phone'],
            'email'    => $_POST['email']
        ];

        $id = $this->customerModel->create($data);
        $data['customer_id'] = $id;

        echo json_encode($data);
    }
}
?>

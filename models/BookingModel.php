<?php
class BookingModel {
    protected $conn;
    public function __construct() { $this->conn = connectDB(); }

    // Lấy tất cả booking (JOIN để hiển thị tên khách & tour)
    public function getAll() {
        $sql = "SELECT b.*, c.fullname AS customer_name, t.name AS tour_name
                FROM booking b
                JOIN customer c ON b.customer_id = c.customer_id
                JOIN tour t ON b.tour_id = t.tour_id
                ORDER BY b.booking_id DESC";
        return $this->conn->query($sql)->fetchAll();
    }

    // Lấy 1 booking theo ID
    public function findById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM booking WHERE booking_id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    // Kiểm tra tour còn chỗ trống
    public function checkAvailable($scheduleId, $numPeople) {
        $sql = "SELECT ds.max_capacity - COALESCE(SUM(b.num_people),0) AS available
                FROM departure_schedule ds
                LEFT JOIN booking b ON ds.schedule_id = b.schedule_id
                WHERE ds.schedule_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$scheduleId]);
        $row = $stmt->fetch();
        return $row && $row['available'] >= $numPeople;
    }

    // Tạo booking mới
    public function create($customerId, $tourId, $scheduleId, $numPeople, $note) {
        $stmt = $this->conn->prepare("INSERT INTO booking 
            (customer_id, tour_id, schedule_id, num_people, status, note, created_at)
            VALUES (?, ?, ?, ?, 'pending', ?, NOW())");
        return $stmt->execute([$customerId, $tourId, $scheduleId, $numPeople, $note]);
    }

    // Cập nhật trạng thái booking
    public function updateStatus($id, $status) {
        $stmt = $this->conn->prepare("UPDATE booking SET status = ? WHERE booking_id = ?");
        return $stmt->execute([$status, $id]);
    }
}
?>

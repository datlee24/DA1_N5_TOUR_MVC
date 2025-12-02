
<?php
// models/TourCustomerModel.php
class TourCustomerModel {
    protected $conn;
    public function __construct() { $this->conn = connectDB(); }

    // trả về id mới
    public function addCustomer($schedule_id, $customer_id, $room_number = null) {
        $sql = "INSERT INTO tour_customer (schedule_id, customer_id, room_number, checkin_status, note, attendance_status) 
                VALUES (:sid, :cid, :room, 'not_checked_in', NULL, 'unknown')";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['sid'=>$schedule_id, 'cid'=>$customer_id, 'room'=>$room_number]);
        return $this->conn->lastInsertId();
    }

    public function exists($schedule_id, $customer_id) {
        $sql = "SELECT COUNT(*) FROM tour_customer WHERE schedule_id = :sid AND customer_id = :cid";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['sid'=>$schedule_id, 'cid'=>$customer_id]);
        return $stmt->fetchColumn() > 0;
    }

    public function getBySchedule($schedule_id) {
        $sql = "SELECT c.*, tc.room_number, tc.checkin_status, tc.note AS tc_note, tc.attendance_status
                FROM tour_customer tc
                JOIN customer c ON tc.customer_id = c.customer_id
                WHERE tc.schedule_id = :sid";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['sid'=>$schedule_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>

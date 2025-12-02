<?php
// models/TourCustomerModel.php
class TourCustomerModel {
    protected $conn;
    public function __construct() { $this->conn = connectDB(); }

    public function addCustomer($schedule_id, $customer_id, $room_number = null) {
        $sql = "INSERT INTO tour_customer (schedule_id, customer_id, room_number) VALUES (:sid, :cid, :room)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['sid'=>$schedule_id, 'cid'=>$customer_id, 'room'=>$room_number]);
        return $this->conn->lastInsertId();
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

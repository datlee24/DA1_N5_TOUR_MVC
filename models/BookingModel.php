<?php
// models/BookingModel.php
class BookingModel {
    protected $conn;

    public function __construct(){
        $this->conn = connectDB();
    }

    // Lấy tất cả booking cùng thông tin liên quan
    public function getAll(){
        $sql = "SELECT b.*, c.fullname AS customer_name, t.name AS tour_name, d.start_date, d.end_date
                FROM booking b
                JOIN customer c ON b.customer_id = c.customer_id
                JOIN tour t ON b.tour_id = t.tour_id
                JOIN departure_schedule d ON b.schedule_id = d.schedule_id
                ORDER BY b.booking_id DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find($id){
        $stmt = $this->conn->prepare("SELECT * FROM booking WHERE booking_id = :id LIMIT 1");
        $stmt->execute([':id'=>$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Tạo booking (sử dụng transaction để an toàn)
    public function create(array $data){
        try {
            $this->conn->beginTransaction();

            $sql = "INSERT INTO booking 
                    (customer_id, tour_id, schedule_id, num_people, status, note, adult, children, baby) 
                    VALUES (:customer_id, :tour_id, :schedule_id, :num_people, :status, :note, :adult, :children, :baby)";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':customer_id' => $data['customer_id'],
                ':tour_id'     => $data['tour_id'],
                ':schedule_id' => $data['schedule_id'],
                ':num_people'  => $data['num_people'],
                ':status'      => $data['status'],
                ':note'        => $data['note'],
                ':adult'       => $data['adult'] ?? 0,
                ':children'    => $data['children'] ?? 0,
                ':baby'        => $data['baby'] ?? 0,
            ]);

            $booking_id = $this->conn->lastInsertId();
            $this->conn->commit();
            return $booking_id;
        } catch (Exception $e) {
            $this->conn->rollBack();
            throw $e;
        }
    }

    public function updateStatus($booking_id, $status){
        $stmt = $this->conn->prepare("UPDATE booking SET status = :status WHERE booking_id = :id");
        return $stmt->execute([':status'=>$status, ':id'=>$booking_id]);
    }

    public function getSchedulesWithTours(){
        $sql = "SELECT d.schedule_id, d.tour_id, d.start_date, d.end_date, t.name
                FROM departure_schedule d
                JOIN tour t ON d.tour_id = t.tour_id
                WHERE t.status = 'active'
                ORDER BY d.start_date";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Tính tổng số người đã đặt (loại trừ các booking hủy)
    public function countBookedCustomers($schedule_id){
        $sql = "SELECT COALESCE(SUM(num_people),0) AS total
                FROM booking
                WHERE schedule_id = :sid AND status != 'cancelled'";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':sid' => $schedule_id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return intval($row['total'] ?? 0);
    }

    public function getTourCapacity($tour_id){
        $stmt = $this->conn->prepare("SELECT capacity FROM tour WHERE tour_id = :tid");
        $stmt->execute([':tid' => $tour_id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return intval($row['capacity'] ?? 0);
    }
}

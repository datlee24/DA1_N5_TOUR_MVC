<?php
class BookingModel {
    protected $conn;

    public function __construct() { $this->conn = connectDB(); }

    public function getAll() {
        $sql = "SELECT b.*, c.fullname AS customer_name,
                         t.name AS tour_name, ds.start_date, ds.end_date
                FROM booking b
                LEFT JOIN customer c ON b.customer_id = c.customer_id
                LEFT JOIN tour t ON b.tour_id = t.tour_id
                LEFT JOIN departure_schedule ds ON b.schedule_id = ds.schedule_id
                ORDER BY booking_id DESC";
        return $this->conn->query($sql)->fetchAll();
    }

public function find($id) {
    $sql = "SELECT 
                b.*, 
                t.name AS tour_name,
                ds.start_date, 
                ds.end_date,
                u.fullname AS guide_name, -- Lấy tên từ bảng users
                u.phone AS guide_phone   -- Lấy SĐT từ bảng users
            FROM booking b
            LEFT JOIN tour t ON b.tour_id = t.tour_id
            LEFT JOIN departure_schedule ds ON b.schedule_id = ds.schedule_id
            LEFT JOIN guide g ON ds.guide_id = g.guide_id
            LEFT JOIN users u ON g.user_id = u.user_id -- Thêm JOIN với bảng users
            WHERE b.booking_id = :id";

    $stmt = $this->conn->prepare($sql);
    $stmt->execute(['id'=>$id]);

    return $stmt->fetch();
}


    public function create($data) {
        $sql = "INSERT INTO booking 
                (customer_id,tour_id,schedule_id,booking_date,num_people,total_price,status,payment_status,note)
                VALUES
                (:customer_id,:tour_id,:schedule_id,:booking_date,:num_people,:total_price,:status,:payment_status,:note)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($data);
        return $this->conn->lastInsertId();
    }

    public function cancel($id) {
        $stmt = $this->conn->prepare("UPDATE booking SET status='cancelled' WHERE booking_id=:id");
        $stmt->execute(['id'=>$id]);
    }
}
?>
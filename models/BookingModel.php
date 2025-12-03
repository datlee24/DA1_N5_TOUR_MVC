<?php
// models/BookingModel.php
class BookingModel
{
    protected $conn;
    public function __construct()
    {
        $this->conn = connectDB();
    }

    public function getAll()
    {
        // Join để lấy thông tin schedule, tour, customer
        $sql = "SELECT b.*, c.fullname AS customer_name,
                       t.name AS tour_name, ds.start_date, ds.end_date,
                       u.fullname AS guide_name, d.fullname AS driver_name
                FROM booking b
                LEFT JOIN customer c ON b.customer_id = c.customer_id
                LEFT JOIN tour t ON b.tour_id = t.tour_id
                LEFT JOIN departure_schedule ds ON b.schedule_id = ds.schedule_id
                LEFT JOIN guide g ON ds.guide_id = g.guide_id
                LEFT JOIN users u ON g.user_id = u.user_id
                LEFT JOIN driver d ON ds.driver_id = d.driver_id
                ORDER BY b.booking_id DESC";
        return $this->conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find($id)
    {
        $sql = "SELECT b.*, c.fullname AS customer_name, t.name AS tour_name,
                       ds.start_date, ds.end_date, ds.meeting_point, ds.driver_id, ds.guide_id,
                       u.fullname as guide_name, u.phone as guide_phone,
                       dr.fullname as driver_name, dr.phone as driver_phone, dr.license_plate
                FROM booking b
                LEFT JOIN customer c ON b.customer_id = c.customer_id
                LEFT JOIN tour t ON b.tour_id = t.tour_id
                LEFT JOIN departure_schedule ds ON b.schedule_id = ds.schedule_id
                LEFT JOIN guide g ON ds.guide_id = g.guide_id
                LEFT JOIN users u ON g.user_id = u.user_id
                LEFT JOIN driver dr ON ds.driver_id = dr.driver_id
                WHERE b.booking_id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data)
    {
        $sql = "INSERT INTO booking
                (customer_id,tour_id,schedule_id,booking_date,num_people,total_price,status,payment_status,note)
                VALUES
                (:customer_id,:tour_id,:schedule_id,:booking_date,:num_people,:total_price,:status,:payment_status,:note)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($data);
        return $this->conn->lastInsertId();
    }

    public function cancel($id)
    {
        $stmt = $this->conn->prepare("UPDATE booking SET status='cancelled' WHERE booking_id=:id");
        $stmt->execute(['id' => $id]);
    }

    public function updateStatus($id, $status)
    {
        $allowed = ['upcoming', 'ongoing', 'completed', 'cancelled'];
        if (!in_array($status, $allowed)) return false;
        $stmt = $this->conn->prepare("UPDATE booking SET status=:status WHERE booking_id=:id");
        return $stmt->execute(['status' => $status, 'id' => $id]);
    }

    public function updatePaymentStatus($id, $pstatus)
    {
        $allowed = ['unpaid', 'deposit', 'paid'];
        if (!in_array($pstatus, $allowed)) return false;
        $stmt = $this->conn->prepare("UPDATE booking SET payment_status=:ps WHERE booking_id=:id");
        return $stmt->execute(['ps' => $pstatus, 'id' => $id]);
    }

    public function autoUpdateStatus()
    {
        $today = date('Y-m-d');

        // Ongoing: start <= today <= end
        $sql = "UPDATE booking b
                JOIN departure_schedule ds ON b.schedule_id = ds.schedule_id
                SET b.status = 'ongoing'
                WHERE :today BETWEEN ds.start_date AND ds.end_date
                  AND b.status != 'cancelled'";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['today' => $today]);

        // Completed: end_date < today
        $sql2 = "UPDATE booking b
                 JOIN departure_schedule ds ON b.schedule_id = ds.schedule_id
                 SET b.status = 'completed'
                 WHERE ds.end_date < :today
                   AND b.status != 'cancelled'";
        $stmt2 = $this->conn->prepare($sql2);
        $stmt2->execute(['today' => $today]);

        // Upcoming: start_date > today
        $sql3 = "UPDATE booking b
                 JOIN departure_schedule ds ON b.schedule_id = ds.schedule_id
                 SET b.status = 'upcoming'
                 WHERE ds.start_date > :today
                   AND b.status != 'cancelled'";
        $stmt3 = $this->conn->prepare($sql3);
        $stmt3->execute(['today' => $today]);
    }
}

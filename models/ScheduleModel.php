<?php
// models/ScheduleModel.php
class ScheduleModel
{
    protected $conn;
    public function __construct()
    {
        $this->conn = connectDB();
    }

    public function getByTour($tour_id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM departure_schedule WHERE tour_id=:id ORDER BY start_date ASC");
        $stmt->execute(['id' => $tour_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find($id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM departure_schedule WHERE schedule_id=:id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getBookedSeats($schedule_id)
    {
        $stmt = $this->conn->prepare("SELECT COALESCE(SUM(num_people),0) FROM booking WHERE schedule_id=:id AND status!='cancelled'");
        $stmt->execute(['id' => $schedule_id]);
        return (int)$stmt->fetchColumn();
    }

    /**
     * Lấy lịch theo ngày (lọc theo guide user id)
     * Bổ sung join driver + hotel + tổng khách + trạng thái + vehicle info
     */
    public function getByDate($guide_user_id, $date)
    {
        $sql = "SELECT 
                    ds.schedule_id,
                    t.name AS tour_name,
                    ds.start_date,
                    ds.end_date,
                    d.fullname AS driver_name,
                    d.phone AS driver_phone,
                    d.vehicle_type,
                    d.license_plate,
                    h.name AS hotel_name,
                    h.address AS hotel_address,
                    COALESCE((SELECT SUM(b.num_people) FROM booking b WHERE b.schedule_id = ds.schedule_id AND b.status != 'cancelled'),0) AS total_customers,
                    CASE 
                        WHEN :now BETWEEN ds.start_date AND ds.end_date THEN 'ongoing'
                        WHEN ds.start_date > :now THEN 'upcoming'
                        ELSE 'completed'
                    END AS schedule_status
                FROM departure_schedule ds
                LEFT JOIN tour t ON ds.tour_id = t.tour_id
                LEFT JOIN guide g ON ds.guide_id = g.guide_id
                LEFT JOIN driver d ON ds.driver_id = d.driver_id
                LEFT JOIN hotel h ON ds.hotel_id = h.hotel_id
                WHERE :date BETWEEN ds.start_date AND ds.end_date";

        if ($guide_user_id) {
            $sql .= " AND g.user_id = :uid";
        }

        $sql .= " ORDER BY ds.start_date ASC";

        $stmt = $this->conn->prepare($sql);
        $params = [
            ':date' => $date,
            ':now' => date('Y-m-d')
        ];
        if ($guide_user_id) $params[':uid'] = $guide_user_id;
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy lịch theo tháng cho guide (năm, tháng) — giữ nguyên nhưng thêm vehicle/driver/hotel chọn
    public function getByMonth($guide_user_id, $year, $month, $only_with_bookings = false)
    {
        // Tính ngày bắt đầu và kết thúc của tháng
        $first = sprintf('%04d-%02d-01', (int)$year, (int)$month);
        $last = date('Y-m-t', strtotime($first));

        $sql = "SELECT ds.schedule_id, t.name AS tour_name, ds.start_date, ds.end_date,
                       d.fullname AS driver_name, d.phone AS driver_phone, d.vehicle_type, d.license_plate,
                       h.name AS hotel_name, h.address AS hotel_address,
                       COALESCE(SUM(b.num_people), 0) AS total_customers,
                       CASE 
                            WHEN CURDATE() BETWEEN ds.start_date AND ds.end_date THEN 'ongoing'
                            WHEN ds.start_date > CURDATE() THEN 'upcoming'
                            ELSE 'completed'
                       END AS schedule_status
                FROM departure_schedule ds
                JOIN tour t ON ds.tour_id = t.tour_id
                LEFT JOIN booking b ON b.schedule_id = ds.schedule_id AND b.status != 'cancelled'
                LEFT JOIN driver d ON ds.driver_id = d.driver_id
                LEFT JOIN hotel h ON ds.hotel_id = h.hotel_id
                LEFT JOIN guide g ON ds.guide_id = g.guide_id
                WHERE NOT (ds.end_date < :first OR ds.start_date > :last)";

        if ($guide_user_id) {
            $sql .= " AND g.user_id = :uid";
        }

        $sql .= " GROUP BY ds.schedule_id";

        if ($only_with_bookings) {
            $sql .= " HAVING COALESCE(SUM(b.num_people), 0) > 0";
        }

        $sql .= "\n                  ORDER BY ds.start_date ASC";

        $stmt = $this->conn->prepare($sql);
        $params = [':first' => $first, ':last' => $last];
        if ($guide_user_id) $params[':uid'] = $guide_user_id;
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Những hàm assign / create / assignHotel giữ nguyên
    public function assignGuide($schedule_id, $guide_id)
    {
        $stmt = $this->conn->prepare("UPDATE departure_schedule SET guide_id=:gid WHERE schedule_id=:sid");
        $stmt->execute(['gid' => $guide_id, 'sid' => $schedule_id]);
    }

    public function assignDriver($schedule_id, $driver_id)
    {
        $stmt = $this->conn->prepare("UPDATE departure_schedule SET driver_id=:did WHERE schedule_id=:sid");
        $stmt->execute(['did' => $driver_id, 'sid' => $schedule_id]);
    }

    public function create($data)
    {
        $sql = "INSERT INTO departure_schedule 
                (tour_id, start_date, end_date, meeting_point, guide_id, driver_id, hotel_id, notes)
                VALUES (:tour_id, :start_date, :end_date, :meeting_point, :guide_id, :driver_id, :hotel_id, :notes)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            'tour_id' => $data['tour_id'],
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'],
            'meeting_point' => $data['meeting_point'] ?? null,
            'guide_id' => $data['guide_id'] ?? null,
            'driver_id' => $data['driver_id'] ?? null,
            'hotel_id' => $data['hotel_id'] ?? null,
            'notes' => $data['notes'] ?? null
        ]);
        return $this->conn->lastInsertId();
    }

    public function assignHotel($schedule_id, $hotel_id)
    {
        $stmt = $this->conn->prepare("UPDATE departure_schedule SET hotel_id=:hid WHERE schedule_id=:sid");
        return $stmt->execute(['hid' => $hotel_id, 'sid' => $schedule_id]);
    }

    /**
     * Lấy lịch của guide trong tháng (đã có trước) — giữ tên hàm
     */
    public function getGuideMonthSchedules($guide_user_id, $year, $month)
    {
        // Mặc định khi hiển thị lịch cho guide: ẩn các schedule không có khách
        return $this->getByMonth($guide_user_id, $year, $month, true);
    }

    /**
     * Chi tiết 1 schedule (dùng cho view detail)
     */
    public function getScheduleDetail($schedule_id)
    {
        $sql = "
            SELECT 
                ds.*,
                t.name AS tour_name,
                d.fullname AS driver_name,
                d.phone AS driver_phone,
                d.vehicle_type,
                d.license_plate,
                h.name AS hotel_name,
                h.address AS hotel_address,
                h.manager_name,
                h.manager_phone,
                u.fullname AS guide_name,
                u.phone AS guide_phone,
                u.email AS guide_email,
                g.cccd AS guide_cccd,
                COALESCE((SELECT SUM(b.num_people) FROM booking b WHERE b.schedule_id = ds.schedule_id AND b.status != 'cancelled'),0) AS total_customers
            FROM departure_schedule ds
            LEFT JOIN tour t ON ds.tour_id = t.tour_id
            LEFT JOIN driver d ON ds.driver_id = d.driver_id
            LEFT JOIN hotel h ON ds.hotel_id = h.hotel_id
            LEFT JOIN guide g ON ds.guide_id = g.guide_id
            LEFT JOIN users u ON g.user_id = u.user_id
            WHERE ds.schedule_id = :id
            LIMIT 1
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $schedule_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Danh sách khách của schedule kèm trạng thái điểm danh gần nhất (nếu có)
     * Sử dụng subquery lấy attendance.max id để tránh lặp
     */
    public function getScheduleCustomers($schedule_id)
    {
        $sql = "
            SELECT 
                tc.id AS tour_customer_id,
                c.customer_id,
                c.fullname,
                c.phone,
                c.email,
                tc.room_number,
                a.status AS attendance_status,
                a.note
            FROM tour_customer tc
            JOIN customer c ON tc.customer_id = c.customer_id
            LEFT JOIN (
                SELECT a1.*
                FROM attendance a1
                JOIN (
                    SELECT tour_customer_id, MAX(attendance_id) AS maxid
                    FROM attendance
                    WHERE schedule_id = :sid
                    GROUP BY tour_customer_id
                ) ag ON ag.maxid = a1.attendance_id
            ) a ON a.tour_customer_id = tc.id
            WHERE tc.schedule_id = :sid
            ORDER BY c.fullname ASC
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['sid' => $schedule_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

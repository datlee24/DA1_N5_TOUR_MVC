<?php
// models/AttendanceModel.php
class AttendanceModel {
    protected $conn;

    public function __construct() {
        $this->conn = connectDB();
    }

    public function create($data) {
        $sql = "INSERT INTO attendance 
                (schedule_id, tour_customer_id, customer_id, guide_id, status, note, marked_at)
                VALUES (:schedule_id, :tour_customer_id, :customer_id, :guide_id, :status, :note, NOW())";

        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':schedule_id' => $data['schedule_id'],
            ':tour_customer_id' => $data['tour_customer_id'],
            ':customer_id' => $data['customer_id'],
            ':guide_id' => $data['guide_id'],
            ':status' => $data['status'],
            ':note' => $data['note'] ?? null
        ]);
    }

    // Lấy danh sách khách của schedule kèm trạng thái điểm danh gần nhất (nếu có)
    public function listBySchedule($schedule_id) {
        $sql = "SELECT 
                    tc.id as tour_customer_id, c.customer_id,
                    c.fullname, c.phone, tc.room_number,
                    a.status, a.note, a.marked_at
                FROM tour_customer tc
                JOIN customer c ON c.customer_id = tc.customer_id
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
                ORDER BY c.fullname ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':sid' => $schedule_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

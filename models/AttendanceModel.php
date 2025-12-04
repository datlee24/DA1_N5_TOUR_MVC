<?php
// models/AttendanceModel.php
class AttendanceModel
{
    protected $conn;

    public function __construct()
    {
        $this->conn = connectDB();
    }

    /**
     * Upsert điểm danh: nếu đã có record (schedule_id + tour_customer_id) thì update (status,note,marked_at),
     * ngược lại insert mới.
     */
    public function upsert($data)
    {
        // check tồn tại bản ghi gần nhất cho tour_customer_id trong schedule
        $sqlCheck = "SELECT attendance_id FROM attendance 
                     WHERE schedule_id = :schedule_id AND tour_customer_id = :tcid
                     ORDER BY attendance_id DESC LIMIT 1";
        $stmt = $this->conn->prepare($sqlCheck);
        $stmt->execute(['schedule_id' => $data['schedule_id'], 'tcid' => $data['tour_customer_id']]);
        $existing = $stmt->fetchColumn();

        if ($existing) {
            $sql = "UPDATE attendance SET status = :status, note = :note, guide_id = :guide_id, marked_at = NOW()
                    WHERE attendance_id = :aid";
            $stmt2 = $this->conn->prepare($sql);
            return $stmt2->execute([
                ':status' => $data['status'],
                ':note' => $data['note'] ?? null,
                ':guide_id' => $data['guide_id'],
                ':aid' => $existing
            ]);
        } else {
            $sql = "INSERT INTO attendance 
                    (schedule_id, tour_customer_id, customer_id, guide_id, status, note, marked_at)
                    VALUES (:schedule_id, :tour_customer_id, :customer_id, :guide_id, :status, :note, NOW())";
            $stmt3 = $this->conn->prepare($sql);
            return $stmt3->execute([
                ':schedule_id' => $data['schedule_id'],
                ':tour_customer_id' => $data['tour_customer_id'],
                ':customer_id' => $data['customer_id'],
                ':guide_id' => $data['guide_id'],
                ':status' => $data['status'],
                ':note' => $data['note'] ?? null
            ]);
        }
    }

    /**
     * Lấy danh sách khách của schedule kèm trạng thái điểm danh gần nhất (nếu có)
     */
    public function listBySchedule($schedule_id)
    {
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

    // Các hàm search (nếu cần) giữ nguyên hoặc mở rộng
    public function search($filters = [])
    {
        $sql = "SELECT a.*, c.fullname AS customer_name, c.phone AS customer_phone,
                       ds.schedule_id, ds.start_date, ds.end_date, t.name AS tour_name,
                       u.fullname AS guide_name
                FROM attendance a
                LEFT JOIN customer c ON a.customer_id = c.customer_id
                LEFT JOIN departure_schedule ds ON a.schedule_id = ds.schedule_id
                LEFT JOIN tour t ON ds.tour_id = t.tour_id
                LEFT JOIN guide g ON a.guide_id = g.guide_id
                LEFT JOIN users u ON g.user_id = u.user_id
                WHERE 1=1";

        $params = [];
        if (!empty($filters['schedule_id'])) {
            $sql .= " AND a.schedule_id = :schedule_id";
            $params[':schedule_id'] = $filters['schedule_id'];
        }
        if (!empty($filters['guide_id'])) {
            $sql .= " AND a.guide_id = :guide_id";
            $params[':guide_id'] = $filters['guide_id'];
        }
        if (!empty($filters['customer_id'])) {
            $sql .= " AND a.customer_id = :customer_id";
            $params[':customer_id'] = $filters['customer_id'];
        }
        if (!empty($filters['date'])) {
            $sql .= " AND DATE(a.marked_at) = :date";
            $params[':date'] = $filters['date'];
        }

        $sql .= " ORDER BY a.marked_at DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>

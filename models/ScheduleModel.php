<?php
// models/ScheduleModel.php
class ScheduleModel {
    protected $conn;
    public function __construct() { $this->conn = connectDB(); }

    public function getByTour($tour_id) {
        $stmt = $this->conn->prepare("SELECT * FROM departure_schedule WHERE tour_id=:id ORDER BY start_date ASC");
        $stmt->execute(['id'=>$tour_id]);
        return $stmt->fetchAll();
    }

    public function find($id) {
        $stmt = $this->conn->prepare("SELECT * FROM departure_schedule WHERE schedule_id=:id");
        $stmt->execute(['id'=>$id]);
        return $stmt->fetch();
    }

    public function getBookedSeats($schedule_id) {
        $stmt = $this->conn->prepare("SELECT COALESCE(SUM(num_people),0) FROM booking WHERE schedule_id=:id AND status!='cancelled'");
        $stmt->execute(['id'=>$schedule_id]);
        return (int)$stmt->fetchColumn();
    }

    public function assignGuide($schedule_id, $guide_id) {
        $stmt = $this->conn->prepare("UPDATE departure_schedule SET guide_id=:gid WHERE schedule_id=:sid");
        $stmt->execute(['gid'=>$guide_id, 'sid'=>$schedule_id]);
    }

    public function create($data) {
        $sql = "INSERT INTO departure_schedule (tour_id, start_date, end_date, meeting_point, guide_id, driver, notes)
                VALUES (:tour_id, :start_date, :end_date, :meeting_point, :guide_id, :driver, :notes)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            'tour_id' => $data['tour_id'],
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'],
            'meeting_point' => $data['meeting_point'] ?? null,
            'guide_id' => $data['guide_id'] ?? null,
            'driver' => $data['driver'] ?? null,
            'notes' => $data['notes'] ?? null
        ]);
        return $this->conn->lastInsertId();
    }

    // Lấy schedule của 1 guide theo year/month
    public function getByMonth($guide_user_id, $year, $month) {
        // Nếu guide_user_id null -> trả rỗng
        if (!$guide_user_id) return [];

        $sql = "SELECT ds.*, t.name AS tour_name
                FROM departure_schedule ds
                LEFT JOIN tour t ON t.tour_id = ds.tour_id
                WHERE ds.guide_id = :gid
                  AND YEAR(ds.start_date) = :y
                  AND MONTH(ds.start_date) = :m
                ORDER BY ds.start_date ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['gid'=>$guide_user_id, 'y'=>$year, 'm'=>$month]);
        return $stmt->fetchAll();
    }

    // Lấy schedule của 1 guide theo ngày cụ thể (bất kỳ schedule nào chồng lên ngày đó)
    public function getByDate($guide_user_id, $date) {
        if (!$guide_user_id) return [];

        $sql = "SELECT ds.*, t.name AS tour_name
                FROM departure_schedule ds
                LEFT JOIN tour t ON t.tour_id = ds.tour_id
                WHERE ds.guide_id = :gid
                  AND :date BETWEEN ds.start_date AND ds.end_date
                ORDER BY ds.start_date ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['gid'=>$guide_user_id, 'date'=>$date]);
        return $stmt->fetchAll();
    }
}

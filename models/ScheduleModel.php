
<?php
// models/ScheduleModel.php
class ScheduleModel {
    protected $conn;
    public function __construct() { $this->conn = connectDB(); }

    public function getByTour($tour_id) {
        $stmt = $this->conn->prepare("SELECT * FROM departure_schedule WHERE tour_id=:id ORDER BY start_date ASC");
        $stmt->execute(['id'=>$tour_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find($id) {
        $stmt = $this->conn->prepare("SELECT * FROM departure_schedule WHERE schedule_id=:id");
        $stmt->execute(['id'=>$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
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

    public function assignDriver($schedule_id, $driver_id) {
        $stmt = $this->conn->prepare("UPDATE departure_schedule SET driver_id=:did WHERE schedule_id=:sid");
        $stmt->execute(['did'=>$driver_id, 'sid'=>$schedule_id]);
    }

    public function create($data) {
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

    public function assignHotel($schedule_id, $hotel_id) {
        $stmt = $this->conn->prepare("UPDATE departure_schedule SET hotel_id=:hid WHERE schedule_id=:sid");
        return $stmt->execute(['hid'=>$hotel_id, 'sid'=>$schedule_id]);
    }
}
?>

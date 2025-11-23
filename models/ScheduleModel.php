<?php
class ScheduleModel {
    protected $conn;

    public function __construct() { $this->conn = connectDB(); }

    public function getByTour($tour_id) {
        $stmt = $this->conn->prepare("SELECT * FROM departure_schedule WHERE tour_id=:id");
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
}


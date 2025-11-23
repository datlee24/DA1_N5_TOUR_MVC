<?php
class ScheduleModel {
    protected $conn;
    public function __construct() {
        $this->conn = connectDB();
    }

    public function getByTour($tour_id) {
        $stmt = $this->conn->prepare("SELECT * FROM departure_schedule WHERE tour_id = :tour_id");
        $stmt->execute(['tour_id' => $tour_id]);
        return $stmt->fetchAll();
    }

    public function find($schedule_id) {
        $stmt = $this->conn->prepare("SELECT * FROM departure_schedule WHERE schedule_id = :id");
        $stmt->execute(['id'=>$schedule_id]);
        return $stmt->fetch();
    }

    public function getBookedSeats($schedule_id) {
        $stmt = $this->conn->prepare("SELECT COALESCE(SUM(num_people),0) as total 
                                      FROM booking 
                                      WHERE schedule_id = :sid AND status <> 'cancelled'");
        $stmt->execute(['sid'=>$schedule_id]);
        return (int)$stmt->fetchColumn();
    }
}

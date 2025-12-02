<?php
// models/DriverModel.php
class DriverModel {
    protected $conn;
    public function __construct() { $this->conn = connectDB(); }

    public function getAll() {
        $stmt = $this->conn->query("SELECT * FROM driver ORDER BY fullname ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find($id) {
        $stmt = $this->conn->prepare("SELECT * FROM driver WHERE driver_id=:id");
        $stmt->execute(['id'=>$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Kiểm tra tài xế có bận trong khoảng start..end không
     */
    public function isBusy($driverId, $start, $end) {
        $sql = "SELECT COUNT(*) FROM departure_schedule
                WHERE driver_id = :id
                AND NOT (end_date < :start OR start_date > :end)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id'=>$driverId, 'start'=>$start, 'end'=>$end]);
        return $stmt->fetchColumn() > 0;
    }
}

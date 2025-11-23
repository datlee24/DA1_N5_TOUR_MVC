<?php
class GuideModel {
    protected $conn;
    public function __construct() {
        $this->conn = connectDB();
    }

    public function getAll() {
        return $this->conn->query("SELECT * FROM guide")->fetchAll();
    }

    // Check trùng lịch khi gắn HDV
    public function hasConflict($guide_id, $start, $end) {
        $sql = "SELECT COUNT(*) FROM departure_schedule
                WHERE guide_id = :gid
                AND NOT (end_date < :start OR start_date > :end)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            'gid'=>$guide_id,
            'start'=>$start,
            'end'=>$end
        ]);
        return $stmt->fetchColumn() > 0;
    }
}


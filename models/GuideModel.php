<?php
class GuideModel {
    protected $conn;
    public function __construct() { $this->conn = connectDB(); }

    public function getAll() {
        return $this->conn->query("SELECT * FROM guide")->fetchAll();
    }

    public function getAllWithUser() {
        $sql = "SELECT g.guide_id, g.user_id, u.fullname, u.phone, u.email
                FROM guide g
                JOIN users u ON g.user_id = u.user_id";
        return $this->conn->query($sql)->fetchAll();
    }

    public function findWithUser($guide_id) {
        $sql = "SELECT g.*, u.fullname, u.phone
                FROM guide g JOIN users u ON g.user_id=u.user_id
                WHERE g.guide_id = :gid";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['gid'=>$guide_id]);
        return $stmt->fetch();
    }

    public function isBusy($guide_id, $start, $end) {
        $sql = "SELECT COUNT(*) FROM departure_schedule
                WHERE guide_id=:gid
                AND NOT (end_date < :start OR start_date > :end)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['gid'=>$guide_id,'start'=>$start,'end'=>$end]);
        return $stmt->fetchColumn() > 0;
    }

    // Lấy toàn bộ lịch của HDV
    public function getSchedule($guide_id) {
        $sql = "SELECT * FROM departure_schedule WHERE guide_id = :gid ORDER BY start_date ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['gid' => $guide_id]);
        return $stmt->fetchAll();
    }
    public function getFullProfile($user_id)
    {
        $sql = "SELECT 
                    u.user_id, u.fullname, u.phone, u.email, u.role,
                    g.guide_id, g.language, g.certificate, g.experience, g.specialization
                FROM users u
                LEFT JOIN guide g ON u.user_id = g.user_id
                WHERE u.user_id = :uid
                LIMIT 1";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['uid' => $user_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
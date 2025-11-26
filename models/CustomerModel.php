<?php
class CustomerModel {
    protected $conn;
    public function __construct() { $this->conn = connectDB(); }

    public function getAll() { return $this->conn->query("SELECT * FROM customer")->fetchAll(); }

    public function find($id) {
        $stmt = $this->conn->prepare("SELECT * FROM customer WHERE customer_id=:id");
        $stmt->execute(['id'=>$id]);
        return $stmt->fetch();
    }

    public function create($data) {
        $sql = "INSERT INTO customer (fullname,phone,email) VALUES (:fullname,:phone,:email)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($data);
        return $this->conn->lastInsertId();
    }

    public function getBySchedule($schedule_id) {
        $sql = "SELECT c.* FROM tour_customer tc JOIN customer c ON tc.customer_id = c.customer_id WHERE tc.schedule_id = :sid";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['sid'=>$schedule_id]);
        return $stmt->fetchAll();
    }

    public function customerIsBusy($customer_id, $start, $end) {
        $sql = "SELECT COUNT(*) FROM tour_customer tc JOIN departure_schedule ds ON tc.schedule_id = ds.schedule_id
                WHERE tc.customer_id = :cid
                AND NOT (ds.end_date < :start OR ds.start_date > :end)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['cid'=>$customer_id,'start'=>$start,'end'=>$end]);
        return $stmt->fetchColumn() > 0;
    }
}
?>

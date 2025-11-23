<?php
class CustomerModel {
    protected $conn;

    public function __construct() { $this->conn = connectDB(); }

    public function getAll() {
        return $this->conn->query("SELECT * FROM customer")->fetchAll();
    }

    public function create($data) {
        $sql = "INSERT INTO customer (fullname,phone,email) VALUES (:fullname,:phone,:email)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($data);
        return $this->conn->lastInsertId();
    }

public function getBySchedule($schedule_id) {
    $sql = "SELECT c.*
            FROM tour_customer tc
            JOIN customer c ON tc.customer_id = c.customer_id
            WHERE tc.schedule_id = :sid";

    $stmt = $this->conn->prepare($sql);
    $stmt->execute(['sid'=>$schedule_id]);

    return $stmt->fetchAll();
}

}


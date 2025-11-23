<?php
class TourCustomerModel {
    protected $conn;

    public function __construct() { $this->conn = connectDB(); }

    public function addCustomer($schedule_id, $customer_id) {
        $stmt = $this->conn->prepare(
            "INSERT INTO tour_customer (schedule_id, customer_id)
             VALUES (:sid, :cid)"
        );
        $stmt->execute(['sid'=>$schedule_id,'cid'=>$customer_id]);
    }
}


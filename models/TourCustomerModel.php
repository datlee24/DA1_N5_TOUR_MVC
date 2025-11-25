<?php
class TourCustomerModel {
    protected $conn;

    public function __construct() { 
        $this->conn = connectDB(); 
    }

    // add a customer to schedule (NO booking_id)
    public function addCustomer($schedule_id, $customer_id) {
        $sql = "INSERT INTO tour_customer (schedule_id, customer_id)
                VALUES (:sid, :cid)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            'sid' => $schedule_id,
            'cid' => $customer_id
        ]);

        return $this->conn->lastInsertId();
    }

    // get all customers in a schedule
    public function getBySchedule($schedule_id) {
        $sql = "SELECT c.* FROM tour_customer tc
                JOIN customer c ON tc.customer_id = c.customer_id
                WHERE tc.schedule_id = :sid";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['sid' => $schedule_id]);
        return $stmt->fetchAll();
    }
}
?>

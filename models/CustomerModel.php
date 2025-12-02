
<?php
class CustomerModel {
    protected $conn;
    public function __construct() { $this->conn = connectDB(); }

    public function getAll() {
        $stmt = $this->conn->query("SELECT * FROM customer ORDER BY fullname ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find($id) {
        $stmt = $this->conn->prepare("SELECT * FROM customer WHERE customer_id=:id");
        $stmt->execute(['id'=>$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function search($keyword) {
        $keyword = trim($keyword);
        $sql = "SELECT * FROM customer 
                WHERE fullname LIKE :kw 
                   OR phone LIKE :kw 
                   OR email LIKE :kw
                ORDER BY fullname ASC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['kw' => "%$keyword%"]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // create accepts array with fields; only inserts provided fields (safe defaults)
    public function create($data) {
        // normalize
        $fullname = $data['fullname'] ?? '';
        $phone = $data['phone'] ?? null;
        $email = $data['email'] ?? null;
        $gender = $data['gender'] ?? null;
        $birthdate = $data['birthdate'] ?? null;
        $id_number = $data['id_number'] ?? null;
        $notes = $data['notes'] ?? null;

        $sql = "INSERT INTO customer (fullname, gender, birthdate, phone, email, id_number, notes)
                VALUES (:fullname, :gender, :birthdate, :phone, :email, :id_number, :notes)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            'fullname' => $fullname,
            'gender' => $gender,
            'birthdate' => $birthdate,
            'phone' => $phone,
            'email' => $email,
            'id_number' => $id_number,
            'notes' => $notes
        ]);
        return $this->conn->lastInsertId();
    }

    public function getBySchedule($schedule_id) {
        $sql = "SELECT c.* FROM tour_customer tc JOIN customer c ON tc.customer_id = c.customer_id WHERE tc.schedule_id = :sid";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['sid'=>$schedule_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function customerIsBusy($customer_id, $start, $end) {
        $sql = "SELECT COUNT(*) FROM tour_customer tc JOIN departure_schedule ds ON tc.schedule_id = ds.schedule_id
                WHERE tc.customer_id = :cid
                AND NOT (ds.end_date < :start OR ds.start_date > :end)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['cid'=>$customer_id,'start'=>$start,'end'=>$end]);
        return $stmt->fetchColumn() > 0;
    }

    public function getBusyCustomers($start, $end) {
        $sql = "SELECT DISTINCT tc.customer_id 
                FROM tour_customer tc
                JOIN departure_schedule ds ON tc.schedule_id = ds.schedule_id
                WHERE NOT (ds.end_date < :start OR ds.start_date > :end)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['start'=>$start, 'end'=>$end]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
}
?>

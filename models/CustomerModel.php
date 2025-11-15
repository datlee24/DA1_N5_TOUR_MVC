<?php
// models/CustomerModel.php
class CustomerModel {
    protected $conn;
    public function __construct(){ $this->conn = connectDB(); }

    // Tìm theo phone hoặc email, nếu có return id, ngược lại tạo
    public function getOrCreate($fullname, $phone=null, $email=null, $notes=null){
        // ưu tiên tìm theo phone rồi email
        if($phone){
            $stmt = $this->conn->prepare("SELECT customer_id FROM customer WHERE phone = :phone LIMIT 1");
            $stmt->execute([':phone'=>$phone]);
            $r = $stmt->fetch(PDO::FETCH_ASSOC);
            if($r) return $r['customer_id'];
        }
        if($email){
            $stmt = $this->conn->prepare("SELECT customer_id FROM customer WHERE email = :email LIMIT 1");
            $stmt->execute([':email'=>$email]);
            $r = $stmt->fetch(PDO::FETCH_ASSOC);
            if($r) return $r['customer_id'];
        }

        // tạo mới
        $ins = "INSERT INTO customer (fullname, phone, email, notes) VALUES (:fullname, :phone, :email, :notes)";
        $s2 = $this->conn->prepare($ins);
        $s2->execute([':fullname'=>$fullname, ':phone'=>$phone, ':email'=>$email, ':notes'=>$notes]);
        return $this->conn->lastInsertId();
    }

    public function getAll(){
        $stmt = $this->conn->prepare("SELECT * FROM customer ORDER BY fullname");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

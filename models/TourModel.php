<?php
class TourModel { protected $conn; public function __construct(){ $this->conn=connectDB(); }
    public function getAll()
    { return $this->conn->query("SELECT * FROM tour")->fetchAll(); }
    public function getAllActive()
    { return $this->conn->query("SELECT * FROM tour WHERE status='active'")->fetchAll(); }
    public function find($id)
    { $stmt=$this->conn->prepare("SELECT * FROM tour WHERE tour_id=:id"); $stmt->execute(['id'=>$id]); return $stmt->fetch(); }
}
?>
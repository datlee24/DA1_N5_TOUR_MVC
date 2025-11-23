<?php
class TourModel {
    protected $conn;
    public function __construct() {
        $this->conn = connectDB();
    }

    public function getAll() {
        $stmt = $this->conn->prepare("SELECT * FROM tour");
        $stmt->execute();
        return $stmt->fetchAll();
    }
}

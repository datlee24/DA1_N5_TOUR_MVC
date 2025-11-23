<?php
class CustomerModel {
    protected $conn;
    public function __construct() {
        $this->conn = connectDB();
    }

    public function getAll() {
        return $this->conn->query("SELECT * FROM customer")->fetchAll();
    }
}

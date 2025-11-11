<?php
class TourModel
{
    public $conn;
    public function __construct(){
        $this->conn = connectDB();
    }

}
?>
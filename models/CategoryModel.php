<?php
class CategoryModel
{
    public $conn;
    public function __construct(){
        $this->conn = connectDB();
    }

    public function getAllCategory(){
        $sql="SELECT * FROM category";
         $stmt =$this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);

    }

}
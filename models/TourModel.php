<?php
class TourModel
{
    public $conn;
    public function __construct(){
        $this->conn = connectDB();
    }

 //Hiển thị danh sách  tour 
    public function getAllTour(){
        $sql = "SELECT 
                    tour.tour_id,
                    tour.name,
                    tour.description,
                    tour.policy,
                    tour.supplier,
                    tour.image,
                    tour.status,
                    category.name AS category_name
                FROM tour
                JOIN category ON tour.category_id = category.category_id";
        $stmt =$this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);

    }
    // Thêm tour

    public function addTour($data){
        $sql = "INSERT INTO tour (category_id, name, description, policy, supplier, image, status)
                VALUES (:category_id, :name, :description, :policy, :supplier, :image, :status)";
                  $stmt =$this->conn->prepare($sql);
                return $stmt->execute($data);
    }

    //Lấy 1 tour
    public function getTourById($id){
        $sql="SELECT * FROM `tour` WHERE tour_id = :id";
                $stmt =$this->conn->prepare($sql);
        $stmt->execute(['id' => $id]);
         return $stmt->fetch(PDO::FETCH_ASSOC);

    }

    //Cập nhật tour
    public function updateTour($data){
        $sql = "UPDATE tour 
            SET category_id=:category_id, name=:name, description=:description, 
                policy=:policy, supplier=:supplier, image=:image, status=:status 
            WHERE tour_id=:tour_id";
                 $stmt =$this->conn->prepare($sql);
                return $stmt->execute($data);

    }
    //Xóa tour
    public function deleteTour($id){
          $sql = "DELETE FROM tour WHERE tour_id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }

}
?>
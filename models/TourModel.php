<?php

class TourModel { protected $conn; public function __construct(){ $this->conn=connectDB(); }
    public function getAll()
    { return $this->conn->query("SELECT * FROM tour")->fetchAll(); }
    public function getAllActive()
    { return $this->conn->query("SELECT * FROM tour WHERE status='active'")->fetchAll(); }
    public function find($id)
    { $stmt=$this->conn->prepare("SELECT * FROM tour WHERE tour_id=:id"); $stmt->execute(['id'=>$id]); return $stmt->fetch(); }



 //Hiển thị danh sách  tour 
    public function getAllTour(){
        $sql = "SELECT 
                    tour.tour_id,
                    tour.name,
                    tour.description,
                    tour.policy,
                    tour.supplier,
                     tour.price,
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
        $sql = "INSERT INTO tour (category_id, name, description, policy, supplier, price, image, status)
        VALUES (:category_id, :name, :description, :policy, :supplier, :price, :image, :status)";

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
                policy=:policy, supplier=:supplier, image=:image, price =:price, status=:status 
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
    // Lây lịch trình tour
    public function getItineraryByTourId($tour_id){
        $sql ="SELECT * FROM itinerary WHERE tour_id=:tour_id ORDER BY day_number ASC";
         $stmt =$this->conn->prepare($sql);
        $stmt->execute(['tour_id' => $tour_id]);
         return $stmt->fetchAll(PDO::FETCH_ASSOC);

    }
    // Tìm kiếm
    public function searchTourByName($key){
        $sql="SELECT 
                tour.tour_id,
                tour.name,
                tour.description,
                tour.policy,
                tour.supplier,
                tour.price,
                tour.image,
                tour.status,
                category.name AS category_name
            FROM tour
            JOIN category ON tour.category_id = category.category_id
            WHERE tour.name LIKE :key";
              $stmt = $this->conn->prepare($sql);
                $stmt->execute(['key' => "%$key%"]);
                  return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

   

}
?>
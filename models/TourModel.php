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
        $sql = "INSERT INTO tour (category_id, name, description, supplier, price, image, status)
        VALUES (:category_id, :name, :description, :supplier, :price, :image, :status)";

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
             supplier=:supplier, image=:image, price =:price, status=:status 
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
    
    // Tìm kiếm
    public function searchTourByName($key){
        $sql="SELECT 
                tour.tour_id,
                tour.name,
                tour.description,
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

    // Lấy lịch trình tour
    public function getItineraryByTourId($tour_id){
        $sql ="SELECT * FROM itinerary WHERE tour_id=:tour_id ORDER BY day_number ASC";
         $stmt =$this->conn->prepare($sql);
        $stmt->execute(['tour_id' => $tour_id]);
         return $stmt->fetchAll(PDO::FETCH_ASSOC);

    }
// Lấy 1 lịch trình theo id
public function getItineraryById($id){
    $sql="SELECT * FROM itinerary WHERE itinerary_id=:id";
    $stmt=$this->conn->prepare($sql);
    $stmt->execute([
        ':id'=>$id
    ]);
    return $stmt->fetch(PDO::FETCH_ASSOC);

}
// Thêm lịch trình
public function addItinerary($data){
    $sql="INSERT INTO itinerary (tour_id, day_number, title, description,   location, time_start, time_end)
                VALUES (:tour_id, :day_number, :title, :description, :location, :time_start, :time_end)";
                $stmt=$this->conn->prepare($sql);
                return $stmt->execute($data);
}
// Cập nhật lịch tour
public function updateItinerary($data){
    $sql="UPDATE itinerary SET 
                    day_number=:day_number, title=:title, description=:description,
                    location=:location, time_start=:time_start, time_end=:time_end
                WHERE itinerary_id=:itinerary_id";
                $stmt=$this->conn->prepare($sql);
                return $stmt->execute($data);
}
//    Xóa lịch trình
public function deleteItinerary($id){
    $sql="DELETE FROM itinerary WHERE itinerary_id=:id";
     $stmt=$this->conn->prepare($sql);
        return  $stmt->execute([
        ':id'=>$id
    ]);
}

// Đếm số tour đang dùng category(nếu có thì không xóa đc category hoặc ngược lại)
public function countTourByCategory($category_id){
    $sql="SELECT COUNT(*) FROM tour WHERE category_id = :category_id";
     $stmt=$this->conn->prepare($sql);
     $stmt->execute([
        ':category_id'=>$category_id
    ]);
     return $stmt->fetchColumn();
}
// Đếm số booking theo tour
public function countBookingByTour($tour_id){
    $sql="SELECT COUNT(*) FROM booking WHERE tour_id = :tour_id";
     $stmt=$this->conn->prepare($sql);
     $stmt->execute([
        ':tour_id'=>$tour_id
    ]);
     return $stmt->fetchColumn();
}

}
?>
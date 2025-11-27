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
    // Lây lịch trình tour
    public function getItineraryByTourId($tour_id){
        $sql ="SELECT * FROM itinerary WHERE tour_id=:tour_id ORDER BY day_number ASC";
         $stmt =$this->conn->prepare($sql);
        $stmt->execute(['tour_id' => $tour_id]);
         return $stmt->fetchAll(PDO::FETCH_ASSOC);

    }
    // Lấy lịch khởi hành và hướng dẫn viên
   public function getScheduleWithGuideByTourId($tour_id){
    $sql = "SELECT 
                ds.schedule_id,
                ds.start_date,
                ds.end_date,
                ds.meeting_point,
                ds.driver,
                ds.notes,
                g.guide_id,
                g.language AS guide_language,
                g.certificate AS guide_certificate,
                g.experience AS guide_experience,
                g.specialization AS guide_specialization,
                u.fullname AS guide_name
            FROM departure_schedule ds
            LEFT JOIN guide g ON ds.guide_id = g.guide_id
            LEFT JOIN users u ON g.user_id = u.user_id
            WHERE ds.tour_id = :tour_id";

    $stmt = $this->conn->prepare($sql);
    $stmt->execute(['tour_id' => $tour_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

}
?>
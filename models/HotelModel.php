<?php
class HotelModel {
    protected $conn;
    public function __construct() { $this->conn = connectDB(); }

    public function getByTour($tourId) {
        $sql = "SELECT h.* FROM tour_hotel th
                JOIN hotel h ON th.hotel_id = h.hotel_id
                WHERE th.tour_id = :tour_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['tour_id' => $tourId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

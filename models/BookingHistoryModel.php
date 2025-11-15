<?php
// models/BookingHistoryModel.php
class BookingHistoryModel {
    protected $conn;
    public function __construct(){ $this->conn = connectDB(); }

    public function add($booking_id, $old_status, $new_status, $changed_by, $note = null){
        $sql = "INSERT INTO booking_history (booking_id, old_status, new_status, changed_by, note) 
                VALUES (:bid, :old, :new, :by, :note)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':bid' => $booking_id,
            ':old' => $old_status,
            ':new' => $new_status,
            ':by'  => $changed_by,
            ':note'=> $note
        ]);
    }

    public function getByBooking($booking_id){
        $stmt = $this->conn->prepare("SELECT * FROM booking_history WHERE booking_id = :bid ORDER BY changed_at DESC");
        $stmt->execute([':bid'=>$booking_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

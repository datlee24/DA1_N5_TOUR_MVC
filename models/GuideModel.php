<?php
class GuideModel
{
    protected $conn;
    public function __construct()
    {
        $this->conn = connectDB();
    }

    public function getAll()
    {
        $sql = "SELECT 
                g.*,
                u.fullname,
                u.email,
                u.phone
            FROM guide g
            JOIN users u ON g.user_id = u.user_id
            WHERE u.role = 'hdv'
            ORDER BY g.guide_id DESC";

        return $this->conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }


    public function getAllWithUser()
    {
        $sql = "SELECT g.guide_id, g.user_id, u.fullname, u.phone, u.email
            FROM guide g
            JOIN users u ON g.user_id = u.user_id
            WHERE u.role = 'hdv'
            ORDER BY g.guide_id DESC";
        return $this->conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findWithUser($guide_id)
    {
        $sql = "SELECT g.*, u.fullname, u.phone
                FROM guide g JOIN users u ON g.user_id=u.user_id
                WHERE g.guide_id = :gid";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['gid' => $guide_id]);
        return $stmt->fetch();
    }

    public function isBusy($guide_id, $start, $end)
    {
        $sql = "SELECT COUNT(*) FROM departure_schedule
                WHERE guide_id=:gid
                AND NOT (end_date < :start OR start_date > :end)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['gid' => $guide_id, 'start' => $start, 'end' => $end]);
        return $stmt->fetchColumn() > 0;
    }

    public function getSchedule($guide_id, $only_with_bookings = false)
    {
        if (!$only_with_bookings) {
            $sql = "SELECT * FROM departure_schedule WHERE guide_id = :gid ORDER BY start_date ASC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute(['gid' => $guide_id]);
            return $stmt->fetchAll();
        }

        // Trả về các schedule của guide chỉ khi còn booking (status != 'cancelled')
        $sql = "SELECT ds.*
                FROM departure_schedule ds
                LEFT JOIN booking b ON b.schedule_id = ds.schedule_id AND b.status != 'cancelled'
                WHERE ds.guide_id = :gid
                GROUP BY ds.schedule_id
                HAVING COALESCE(SUM(b.num_people), 0) > 0
                ORDER BY ds.start_date ASC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['gid' => $guide_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function findById($id)
    {
        $sql = "SELECT 
                g.*,
                u.fullname,
                u.email,
                u.phone
            FROM guide g
            JOIN users u ON g.user_id = u.user_id
            WHERE g.guide_id = :id";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data)
    {
        $sql = "INSERT INTO guide (user_id, language, certificate, experience, specialization)
                VALUES (:user_id, :language, :certificate, :experience, :specialization)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute($data);
    }

    public function update($id, $data)
    {
        // Build dynamic SET clause so we don't overwrite user_id with NULL
        $params = [];
        $sets = [];

        if (array_key_exists('user_id', $data) && $data['user_id'] !== null) {
            $sets[] = 'user_id = :user_id';
            $params['user_id'] = $data['user_id'];
        }

        $sets[] = 'language = :language';
        $params['language'] = $data['language'] ?? null;

        $sets[] = 'certificate = :certificate';
        $params['certificate'] = $data['certificate'] ?? null;

        $sets[] = 'experience = :experience';
        $params['experience'] = $data['experience'] ?? null;

        $sets[] = 'specialization = :specialization';
        $params['specialization'] = $data['specialization'] ?? null;

        $params['id'] = $id;

        $sql = "UPDATE guide SET " . implode(', ', $sets) . " WHERE guide_id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute($params);
    }

    public function delete($id)
    {
        $sql = "DELETE FROM guide WHERE guide_id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }
    public function getFullProfile($user_id)
    {
        $sql = "SELECT 
                u.user_id, u.fullname, u.phone, u.email,
                g.guide_id, g.language, g.certificate, 
                g.experience, g.specialization
            FROM users u
            LEFT JOIN guide g ON u.user_id = g.user_id
            WHERE u.user_id = :uid
            LIMIT 1";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['uid' => $user_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}

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

    public function getSchedule($guide_id)
    {
        $sql = "SELECT * FROM departure_schedule WHERE guide_id = :gid ORDER BY start_date ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['gid' => $guide_id]);
        return $stmt->fetchAll();
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
        $data['id'] = $id;
        $sql = "UPDATE guide 
                SET user_id = :user_id,
                    language = :language,
                    certificate = :certificate,
                    experience = :experience,
                    specialization = :specialization
                WHERE guide_id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute($data);
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

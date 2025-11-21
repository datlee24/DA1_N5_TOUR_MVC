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
        $sql = "SELECT g.*, u.username, u.email 
                FROM guide AS g 
                LEFT JOIN users AS u ON g.user_id = u.user_id 
                ORDER BY g.guide_id DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function findById($id)
    {
        $sql = "SELECT g.*, u.username, u.email 
                FROM guide AS g 
                LEFT JOIN users AS u ON g.user_id = u.user_id 
                WHERE g.guide_id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function findByUserId($userId)
    {
        $sql = "SELECT * FROM guide WHERE user_id = :user_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function create($data)
    {
        $sql = "INSERT INTO guide (user_id, language, certificate, specialization, base_fee)
                VALUES (:user_id, :language, :certificate, :specialization, :base_fee)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':user_id', $data['user_id'], PDO::PARAM_INT);
        $stmt->bindParam(':language', $data['language']);
        $stmt->bindParam(':certificate', $data['certificate']);
        $stmt->bindParam(':specialization', $data['specialization']);
        $stmt->bindParam(':base_fee', $data['base_fee']);
        return $stmt->execute();
    }

    public function update($id, $data)
    {
        $sql = "UPDATE guide 
                SET user_id = :user_id,
                    language = :language,
                    certificate = :certificate,
                    specialization = :specialization,
                    base_fee = :base_fee
                WHERE guide_id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':user_id', $data['user_id'], PDO::PARAM_INT);
        $stmt->bindParam(':language', $data['language']);
        $stmt->bindParam(':certificate', $data['certificate']);
        $stmt->bindParam(':specialization', $data['specialization']);
        $stmt->bindParam(':base_fee', $data['base_fee']);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function delete($id)
    {
        $sql = "DELETE FROM guide WHERE guide_id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}


<?php
class UserModel
{
    protected $conn;
    public function __construct()
    {
        $this->conn = connectDB();
    }

    public function getAll()
    {
        $sql = "SELECT * FROM users";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findById($id)
    {
        $sql = "SELECT * FROM users WHERE user_id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Kiểm tra email đã tồn tại trong cơ sở dữ liệu
    // Trả về thông tin người dùng nếu tồn tại, không thì trả về null
    public function checkEmail($email)
    {
        $sql = "SELECT * FROM users WHERE email = :email";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            return $stmt->fetch();
        } else {
            return null; // Không tìm thấy người dùng
        }
    }
        // Lấy thông tin user theo email
    public function findByEmail($email)
    {
        $sql = "SELECT * FROM users WHERE email = :email LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function updateProfile($user_id, $data)
{
    $sql = "UPDATE users 
            SET fullname = :fullname,
                phone    = :phone,
                email    = :email,
                password = :password
            WHERE user_id = :id";

    $stmt = $this->conn->prepare($sql);
    return $stmt->execute([
        'fullname' => $data['fullname'],
        'phone'    => $data['phone'],
        'email'    => $data['email'],
        'password' => $data['password'],
        'id'       => $user_id
    ]);
}

}
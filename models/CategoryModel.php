<?php
class CategoryModel
{
    public $conn;
    public function __construct(){
        $this->conn = connectDB();
    }

    public function getAllCategory(){
        $sql="SELECT * FROM category";
         $stmt =$this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);

    }
     // Lấy 1 danh mục theo id (dùng khi sửa)
    public function getCategoryId($id){
        $sql="SELECT * FROM category WHERE category_id =:id";
         $stmt =$this->conn->prepare($sql);
        $stmt->execute(['id'=>$id]);
        return $stmt->fetch();
    }
    // Thêm mới danh mục
    public function insertCategory($name,$description){
        $sql ="INSERT INTO category (name,description) VALUES (:name,:description)";
        $stmt =$this->conn->prepare($sql);
        $stmt->execute([
            'name'=>$name,
            'description'=>$description

        ]);

    }
    // Cập nhật danh mục
    public function updateCategory($id,$name,$description){
        $sql="UPDATE category SET name=:name, description =:description WHERE category_id=:id";
          $stmt =$this->conn->prepare($sql);
        $stmt->execute([
            'id'=>$id,
            'name'=>$name,
            'description'=>$description
        ]);

    }
    // Xóa danh mục
    public function deleteCategory($id){
        $sql="DELETE FROM `category` WHERE category_id =:id";
        $stmt =$this->conn->prepare($sql);
        $stmt->execute([
            'id'=>$id,
        ]);
    }

}


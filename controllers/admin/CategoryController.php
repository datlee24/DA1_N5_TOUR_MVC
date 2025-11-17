<?php
class CategoryController{
    public $modelCategory;
    public function __construct(){
        $this->modelCategory = new CategoryModel();
    }
    
    // Hiển thị danh sách danh mục
    public function listCategory(){
        $categories= $this->modelCategory->getAllCategory();
    require './views/admin/categories/list.php'; 
   }
   //form thêm danh mục
   public function addCategoryForm(){
     require './views/admin/categories/create.php'; 
   }
//    Xử lý thêm danh mục
public function addCategory(){
    $name = $_POST['name'];
    $description =$_POST['description'];
    if (!$name) {
        $_SESSION['error'] = "Tên danh mục không được để trống";
        header("Location: admin.php?act=category_add_form");
        exit();
    }

    $this->modelCategory->insertCategory($name,$description);

    // Thêm flash message thành công
    $_SESSION['success'] = "Thêm danh mục thành công!";
    header("Location: admin.php?act=category_list");
    exit();

}
// form sửa danh mục
public function editCategoryForm(){
    $id = $_GET['id'];
    $category = $this->modelCategory->getCategoryId($id);
   require './views/admin/categories/edit.php';
}

//Xử lý sửa
public function updateCategory(){
    $id =$_POST['category_id'];
     $name = $_POST['name'];
    $description =$_POST['description'];
     if (!$id || !$name) {
        $_SESSION['error'] = "Dữ liệu không hợp lệ!";
        header("Location: admin.php?act=category_edit&id=$id");
        exit();
    }
    $this->modelCategory->updateCategory($id,$name, $description);
     $_SESSION['success'] = "Cập nhật danh mục thành công!";
    header("Location:admin.php?act=category_list");
    
}
// Xóa danh mục
public function deleteCategory(){
    $id=$_GET['id'];
    $this->modelCategory->deleteCategory($id);
     header("Location:admin.php?act=category_list");
}

   
}
?>
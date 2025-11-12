<?php
class TourController{
    public $modelTour;
    public $modelCategory;

    
    public function __construct(){
        $this->modelTour = new TourModel();
        $this->modelCategory = new CategoryModel();
    }
   
       //Danh sách tour
    public function list(){
        $tours =$this->modelTour->getAllTour();
        require './views/admin/tours/list.php';
    }

    // Xóa tour
    public function deleteTour(){
        $id = $_GET['id'];
        $tour =$this->modelTour->getTourById($id);
        $this->modelTour->deleteTour($id);
         header('Location: index.php?act=tour_list');
         exit;
    }
    // From add tour

    public function FromAdd(){
        $categories = $this->modelCategory->getAllCategory();
       require './views/admin/tours/add.php';

    }
    //Xử lý thêm tour
    public function addTour(){

        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $imageName = null;
             //Upload ảnh
             if(isset($_FILES['image']) && $_FILES['image']['error']=== UPLOAD_ERR_OK){
                $targetDir ="upload/tours/";
                if(!is_dir($targetDir)) mkdir($targetDir,0777,true);
                $imageName = time() .'_'.basename($_FILES['image']['name']);
                move_uploaded_file($_FILES['image']['tmp_name'],$targetDir.$imageName);

             }
             $data = [
                 'category_id' => $_POST['category_id'],
                'name' => $_POST['name'],
                'description' => $_POST['description'],
                'policy' => $_POST['policy'],
                'supplier' => $_POST['supplier'],
                'image' => $imageName,
                'status' => $_POST['status']
             ];
             $this->modelTour->addTour($data);
             header('Location: index.php?act=tour_list');
        }

    }

        
    

}

?>
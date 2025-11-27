<?php
class TourController{
    public $modelTour;
    public $modelCategory;

    
    public function __construct(){
        $this->modelTour = new TourModel();
        $this->modelCategory = new CategoryModel();
    }
   
       //Danh sách tour
    public function tour_list(){
        $tours =$this->modelTour->getAllTour();
        require './views/admin/tours/list.php';
    }

    // Xóa tour
    public function deleteTour(){
        $id = $_GET['id'];
        $tour =$this->modelTour->getTourById($id);
        $this->modelTour->deleteTour($id);
         header('Location:admin.php?act=tour_list');
;
         exit;
    }
    // From add tour

    public function FormAdd(){
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
            header('Location:admin.php?act=tour_list');

        }

    }

        // Update tour
    public function FormEdit(){
        $id=$_GET['id'];
        $tour = $this->modelTour->getTourById($id);
        $categories=$this->modelCategory->getAllCategory();

         require './views/admin/tours/edit.php';

    }
    // xử lý
    public function updateTour(){
       if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        $imageName = $_POST['old_image'];

        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {

            $targetDir = "upload/tours/";
            if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);

            $imageName = time() . '_' . basename($_FILES['image']['name']);
            move_uploaded_file($_FILES['image']['tmp_name'], $targetDir . $imageName);
        }
             $data = [
                 'tour_id' => $_POST['tour_id'],    
                 'category_id' => $_POST['category_id'],
                'name' => $_POST['name'],
                'description' => $_POST['description'],
                'policy' => $_POST['policy'],
                'supplier' => $_POST['supplier'],
                'image' => $imageName,
                'status' => $_POST['status']
             ];
             $this->modelTour->updateTour($data);
            header('Location:admin.php?act=tour_list');

        }

    }  
        public function tour_detail(){
        $tour_id =$_GET['id'];
        // Lấy tour
        $tour=$this->modelTour->getTourById($tour_id);
        // Lấy lịch trình
        $itineraries =$this->modelTour->getItineraryByTourId($tour_id);
        // Lấy lịch khởi hành và hướng dẫn viên
        $schedules=$this->modelTour->getScheduleWithGuideByTourId($tour_id);

        require './views/admin/tours/detail.php';
    }
    }
    // public function tour_detail(){
    //     $tour_id =$_GET['id'];
    //     // Lấy tour
    //     $tour=$this->modelTour->getTourById($tour_id);
    //     // Lấy lịch trình
    //     $itineraries =$this->modelTour->getItineraryByTourId($tour_id);
    //     // Lấy lịch khởi hành và hướng dẫn viên
    //     $schedules=$this->modelTour->getScheduleWithGuideByTourId($tour_id);

    //     require './views/admin/tours/detail.php';
    // }

// }

?>
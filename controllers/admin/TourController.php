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
        $tours = []; 
        if(isset($_GET['q']) && !empty($_GET['q'])){
            $key= $_GET['q'];
            $tours = $this->modelTour->searchTourByName($key); // gán vào $tours
        } else {
            $tours = $this->modelTour->getAllTour();
        }
    
        require './views/admin/tours/list.php';
    }


    // Xóa tour
    public function deleteTour() {
        if(!isset($_GET['id'])){
            $_SESSION['error'] = "ID tour không hợp lệ!";
            header('Location: admin.php?act=tour_list');
            exit;
        }

        $id = $_GET['id'];

        // Kiểm tra tour có booking không
        if($this->modelTour->countBookingByTour($id) > 0){
            $_SESSION['error'] = "Tour đang có booking, không thể xóa!";
        } else if($this->modelTour->deleteTour($id)) {
            $_SESSION['success'] = "Xóa tour thành công!";
        } else {
            $_SESSION['error'] = "Xóa tour thất bại!";
        }

        header('Location: admin.php?act=tour_list');
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
                'supplier' => $_POST['supplier'],
                'price' => $_POST['price'],
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
                'supplier' => $_POST['supplier'],
                'price' => $_POST['price'], 
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
        require './views/admin/tours/detail.php';
    }
    // Xóa lịch trình
    public function deleteItinerary(){
        $id=$_GET['id'];
        $tour_id=$_GET['tour_id'];
        $this->modelTour->deleteItinerary($id);
        header("Location: admin.php?act=tour_detail&id=$tour_id");
        exit;
    }
    // form thêm
    public function addItineraryForm(){
        $tour_id=$_GET['tour_id'];
        require './views/admin/tours/itinerary_add.php';
    }
    // Xử lý thêm
    public function addItinerary(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $tour_id = $_POST['tour_id'];
            $day_number = $_POST['day_number'];
            $titles = $_POST['title'];
            $descriptions = $_POST['description'];
            $locations = $_POST['location'];

            $success = 0;
            foreach($titles as $i => $title){
                if(!empty($title)){
                    $data = [
                        'tour_id' => $tour_id,
                        'day_number' => $day_number,
                        'title' => $title,
                        'description' => $descriptions[$i],
                        'location' => $locations[$i],
                        'time_start' => null,
                        'time_end' => null
                    ];
                    if($this->modelTour->addItinerary($data)){
                        $success++;
                    }
                }
            }

            if($success > 0){
                $_SESSION['success'] = "Thêm $success hoạt động thành công!";
            } else {
                $_SESSION['error'] = "Thêm lịch trình thất bại!";
            }

            header('Location:admin.php?act=tour_detail&id='.$tour_id);
            exit;
        }
    }

    // Sửa lịch trình tour
    public function editItineraryForm(){
            $id = $_GET['id'];
            $itinerary = $this->modelTour->getItineraryById($id);
            $tour_id = $itinerary['tour_id'];
            require './views/admin/tours/itinerary_edit.php';

    }
     public function updateItinerary(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $data=[
                'itinerary_id' => $_POST['itinerary_id'],
                'day_number'=>$_POST['day_number'],
                'title'=>$_POST['title'],
                'description'=>$_POST['description'],
                'location'=>$_POST['location'],
                'time_start'=>$_POST['time_start'],
                'time_end'=>$_POST['time_end']

            ];
            $this->modelTour->updateItinerary($data);
            header('Location:admin.php?act=tour_detail&id='.$_POST['tour_id']);
        }
    }


    }

?>
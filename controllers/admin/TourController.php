<?php
class TourController{
    public $modelTour;
    
    public function __construct(){
        $this->modelTour = new TourModel();
    }
    //Hiển thị danh sách  tour theo danh mục```
    public function tour_list(){
        $categories = $this->modelTour-> getAllCategory();
        
    }

}

?>
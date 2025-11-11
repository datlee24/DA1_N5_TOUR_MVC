<?php
class TourController{
    public $modelTour;
    
    public function __construct(){
        $this->modelTour = new TourModel();
    }
   
       //Danh sách tour
    public function list(){
        $tours =$this->conn->getAllTour();
        require './views/tours/list.php'
    }
        
    

}

?>
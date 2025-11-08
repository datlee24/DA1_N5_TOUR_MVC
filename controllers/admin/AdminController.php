<?php
class AdminController{

    public function __construct(){
        checkIsAdmin();
    }

    //hien thi trang chu admin
    public function dashboard(){
        require_once PATH_ADMIN . 'dashboard.php';
    }
}
?>
<?php
class GuideController
{
    public function __construct()
    {
        if (!isset($_SESSION)) session_start();
        checkGuide();  // Chưa login → quay về login
    }

    public function Home()
    {
        require_once PATH_GUIDE . "trangchu.php";
    }
}

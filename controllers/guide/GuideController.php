<?php
// controllers/guide/GuideController.php
class GuideController
{
    public function __construct()
    {
        if (!isset($_SESSION)) session_start();
        // Không check ở constructor để cho trang login có thể truy cập
    }

    public function Home()
    {
        // Nếu chưa login guide -> redirect login
        if (!isset($_SESSION['guide'])) {
            header("Location: index.php?act=login");
            exit;
        }

        $guide = $_SESSION['guide'];

        require_once PATH_GUIDE . "trangchu.php";
    }
}

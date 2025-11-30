<?php
class GuideAuthController
{
    protected $userModel;

    public function __construct()
    {
        if (!isset($_SESSION)) session_start();
        $this->userModel = new UserModel();
    }

    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            require_once PATH_GUIDE . "login.php";
            return;
        }

        $email    = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        $user = $this->userModel->findByEmail($email);

        if (!$user) {
            $_SESSION['error'] = "Email không tồn tại!";
            header("Location: index.php?act=login");
            exit;
        }

        if ($user['password'] != $password) {
            $_SESSION['error'] = "Mật khẩu không đúng!";
            header("Location: index.php?act=login");
            exit;
        }

        if ($user['role'] != 'hdv') {
            $_SESSION['error'] = "Bạn không phải Hướng dẫn viên!";
            header("Location: index.php?act=login");
            exit;
        }

        $_SESSION['guide'] = $user;
        header("Location: index.php?act=home");
        exit;
    }

    public function logout()
    {
        unset($_SESSION['guide']);
        header("Location: index.php?act=login");
        exit;
    }
}

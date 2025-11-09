<?php
require_once PATH_ROOT . './models/UserModel.php';

class AuthController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function login()
    {
        if (isset($_SESSION['admin'])) {
            $_SESSION['error'] = "Bạn đã đăng nhập rồi!";
            header('Location: admin.php?act=dashboard');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            if (empty($email) || empty($password)) {
                $_SESSION['error'] = "Email và mật khẩu không được để trống";
                header('Location: admin.php?act=login');
                exit;
            }

            $user = $this->userModel->checkEmail($email);

            if ($user) {
                // ✅ So sánh trực tiếp (vì DB chưa mã hóa)
                if ($password === $user['password']) {
                    if ($user['role'] !== 'admin') {
                        $_SESSION['error'] = "Tài khoản không có quyền truy cập vào trang quản trị";
                        header('Location: admin.php?act=login');
                        exit;
                    }

                    $_SESSION['admin'] = $user;
                    $_SESSION['success'] = "Đăng nhập thành công!";
                    header('Location: admin.php?act=dashboard');
                    exit;
                } else {
                    $_SESSION['error'] = "Sai mật khẩu";
                    header('Location: admin.php?act=login');
                    exit;
                }
            } else {
                $_SESSION['error'] = "Tài khoản không tồn tại";
                header('Location: admin.php?act=login');
                exit;
            }
        }

        require_once PATH_ADMIN . 'login.php';
    }

    public function logout()
    {
        unset($_SESSION['admin']);
        header('Location: admin.php?act=login');
        exit;
    }
}

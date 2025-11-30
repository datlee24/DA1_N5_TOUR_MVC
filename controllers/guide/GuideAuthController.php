<?php
// controllers/guide/GuideAuthController.php
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
        // GET -> hiển thị form
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            require_once PATH_GUIDE . "login.php";
            return;
        }

        // POST -> xử lý đăng nhập
        $email    = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        $user = $this->userModel->findByEmail($email);

        if (!$user) {
            $_SESSION['error'] = "Email không tồn tại!";
            header("Location: index.php?act=login");
            exit;
        }

        // Lưu ý: nếu password đã hash trong DB, cần dùng password_verify
        if (isset($user['password']) && password_needs_rehash($user['password'], PASSWORD_DEFAULT) === false) {
            // Trường hợp DB lưu password plain (không khuyến nghị) - so sánh trực tiếp
            // Nhưng tốt hơn nên kiểm tra hashed:
            // if (!password_verify($password, $user['password'])) ...
        }

        if ($user['password'] != $password) {
            $_SESSION['error'] = "Mật khẩu không đúng!";
            header("Location: index.php?act=login");
            exit;
        }

        if (!isset($user['role']) || $user['role'] != 'hdv') {
            $_SESSION['error'] = "Bạn không phải Hướng dẫn viên!";
            header("Location: index.php?act=login");
            exit;
        }

        // Lưu session guide
        $_SESSION['guide'] = $user;

        // Chuyển về trang home (act '/')
        header("Location: index.php?act=/");
        exit;
    }

    public function logout()
    {
        unset($_SESSION['guide']);
        header("Location: index.php?act=login");
        exit;
    }
}

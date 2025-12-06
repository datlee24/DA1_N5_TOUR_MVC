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

        // Verify password: prefer password_verify for hashed passwords.
        $pwOk = false;
        if (!empty($user['password']) && password_verify($password, $user['password'])) {
            $pwOk = true;
        } else {
            // If stored password is plaintext (legacy), allow login and migrate to hash
            if ($user['password'] === $password) {
                $pwOk = true;
                // migrate: hash and update DB
                $newHash = password_hash($password, PASSWORD_DEFAULT);
                $this->userModel->setPassword($user['user_id'], $newHash);
                $user['password'] = $newHash;
            }
        }

        if (!$pwOk) {
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

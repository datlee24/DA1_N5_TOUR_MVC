<?php
require_once PATH_ROOT . './models/UserModel.php';
// Đây là phần xử lý đăng nhập của admin, không phải của người dùng
class AuthController
{
    protected $userModel;

    public function __construct()
    {
        // Không sử dụng checkIsAdmin ở đây vì đây là phần xử lý đăng nhập
        // Chỉ cần khởi tạo UserModel để kiểm tra thông tin đăng nhập
        $this->userModel = new UserModel();
    }

    // Đăng nhập admin
    public function login()
    {
        // phần kiểm tra trạng thái đăng nhập
        if (isset($_SESSION['admin'])) {
            // Nếu đã đăng nhập, chuyển hướng về trang chủ
            $_SESSION['error'] = "Bạn đã đăng nhập rồi!";
            header('Location: admin.php?act=dashboard');
            exit;
        }

        // phần xử lý đăng nhập khi người dùng gửi thông tin đăng nhập
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            // kiểm tra các thông tin đăng nhập
            if (empty($email) || empty($password)) {
                $_SESSION['error'] = "Email và mật khẩu không được để trống";
                header('Location: admin.php?act=login');
                exit;
            }

            // Kiểm tra người dùng bằng email
            $user = $this->userModel->checkEmail($email);
            // Nếu tìm thấy người dùng
            if ($user) {
                // Kiểm tra mật khẩu bằng password_verify
                $hashed = password_verify($password, $user['password']);
                // Nếu mật khẩu đúng (giá trị đã băm trùng khớp với giá trị trong CSDL)
                if ($hashed) {
                    // Kiểm tra xem người dùng có phải là quản trị viên không
                    if ($user['role'] !== 'admin') {
                        $_SESSION['error'] = "Tài khoản không có quyền truy cập vào trang quản trị";
                        header('Location: admin.php?act=login');
                        exit;
                    }
                    // Nếu là admin, đăng nhập thành công
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
                $_SESSION['error'] = "Tài khoản quản trị không tồn tại";
                header('Location: admin.php?act=login');
                exit;
            }
        }

        // phần hiển thị giao diện đăng nhập
        require_once PATH_ADMIN . 'login.php';
    }

    // Đăng xuất admin
    public function logout()
    {
        // Xóa session người dùng
        unset($_SESSION['admin']);
        // Chuyển hướng về trang đăng nhập
        header('Location: index.php');
        exit;
    }
}
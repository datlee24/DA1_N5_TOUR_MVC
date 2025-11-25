<?php
// Nạp file model UserModel để thao tác dữ liệu người dùng
require_once PATH_ROOT . './models/UserModel.php';

/**
 * Controller chịu trách nhiệm xử lý ĐĂNG NHẬP / ĐĂNG XUẤT cho trang quản trị
 * - Chỉ dành cho tài khoản có role = 'admin'
 * - Hiện tại hệ thống chưa sử dụng mật khẩu băm (hash)
 */
class AuthController
{
    protected $userModel; // Biến lưu instance của UserModel

    // Hàm khởi tạo
    public function __construct()
    {
        // Khởi tạo model để sử dụng trong controller
        $this->userModel = new UserModel();
    }

    /**
     * Hàm xử lý đăng nhập
     * - Kiểm tra thông tin gửi từ form
     * - Xác thực tài khoản, mật khẩu, phân quyền
     */
    public function login()
    {
        // Nếu người dùng đã đăng nhập rồi thì không cho đăng nhập lại
        if (isset($_SESSION['admin'])) {
            $_SESSION['error'] = "Bạn đã đăng nhập rồi!";
            header('Location: admin.php?act=dashboard');
            exit;
        }

        // Kiểm tra nếu người dùng gửi form đăng nhập
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            // Kiểm tra dữ liệu rỗng
            if (empty($email) || empty($password)) {
                $_SESSION['error'] = "Email và mật khẩu không được để trống";
                header('Location: admin.php?act=login');
                exit;
            }

            // Kiểm tra xem email có tồn tại trong CSDL không
            $user = $this->userModel->checkEmail($email);

            if ($user) {
                /**
                 * ⚠️ Ở phiên bản hiện tại, mật khẩu trong DB đang lưu dạng thuần (VD: 123456)
                 * => chỉ cần so sánh trực tiếp.
                 * Sau này sẽ chuyển qua mật khẩu mã hóa bằng password_hash(),
                 * thì thay dòng dưới bằng password_verify($password, $user['password']);
                 */
                if ($password === $user['password']) {

                    // Kiểm tra quyền truy cập (chỉ cho phép role = 'admin')
                    if ($user['role'] !== 'admin') {
                        $_SESSION['error'] = "Tài khoản không có quyền truy cập vào trang quản trị";
                        header('Location: admin.php?act=login');
                        exit;
                    }

                    // ✅ Đăng nhập thành công
                    $_SESSION['admin'] = $user;
                    $_SESSION['success'] = "Đăng nhập thành công!";
                    header('Location: admin.php?act=dashboard');
                    exit;
                } else {
                    // Sai mật khẩu
                    $_SESSION['error'] = "Sai mật khẩu";
                    header('Location: admin.php?act=login');
                    exit;
                }
            } else {
                // Không tìm thấy tài khoản theo email
                $_SESSION['error'] = "Tài khoản không tồn tại";
                header('Location: admin.php?act=login');
                exit;
            }
        }

        // Hiển thị form đăng nhập
        require_once PATH_ADMIN . 'login.php';
    }

    /**
     * Hàm xử lý đăng xuất
     * - Xóa session admin và chuyển về trang đăng nhập
     */
        public function logout()
    {
        // Xóa session người dùng
        unset($_SESSION['admin']);
        // Chuyển hướng về trang đăng nhập
        header('Location: index.php');
        exit;
    }
}

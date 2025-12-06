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
                // Verify password using password_verify. If DB still has plaintext, migrate it.
                $pwOk = false;
                if (!empty($user['password']) && password_verify($password, $user['password'])) {
                    $pwOk = true;
                } else {
                    if ($user['password'] === $password) {
                        $pwOk = true;
                        // migrate to hash
                        $newHash = password_hash($password, PASSWORD_DEFAULT);
                        $this->userModel->setPassword($user['user_id'], $newHash);
                        $user['password'] = $newHash;
                    }
                }

                if (!$pwOk) {
                    $_SESSION['error'] = "Sai mật khẩu";
                    header('Location: admin.php?act=login');
                    exit;
                }

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
        // Xóa session người dùng (và các dữ liệu session liên quan)
        if (isset($_SESSION['admin'])) unset($_SESSION['admin']);
        // Nếu muốn xóa toàn bộ session có thể dùng session_unset()/session_destroy().
        // Ở đây chỉ đảm bảo đưa người dùng về trang đăng nhập admin
        header('Location: admin.php?act=login');
        exit;
    }

    /**
     * Hàm xử lý đăng nhập cho hướng dẫn viên (role = 'hdv')
     */
    public function hdvLogin()
    {
        // Nếu hướng dẫn viên đã đăng nhập rồi thì chuyển tới dashboard hdv
        if (isset($_SESSION['hdv'])) {
            $_SESSION['success'] = "Bạn đã đăng nhập rồi!";
            header('Location: admin.php?act=hdv-dashboard');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            if (empty($email) || empty($password)) {
                $_SESSION['error'] = "Email và mật khẩu không được để trống";
                header('Location: admin.php?act=hdv-login');
                exit;
            }

            $user = $this->userModel->checkEmail($email);
            if ($user) {
                $pwOk = false;
                if (!empty($user['password']) && password_verify($password, $user['password'])) {
                    $pwOk = true;
                } else {
                    if ($user['password'] === $password) {
                        $pwOk = true;
                        $newHash = password_hash($password, PASSWORD_DEFAULT);
                        $this->userModel->setPassword($user['user_id'], $newHash);
                        $user['password'] = $newHash;
                    }
                }

                if (!$pwOk) {
                    $_SESSION['error'] = "Sai mật khẩu";
                    header('Location: admin.php?act=hdv-login');
                    exit;
                }

                // Chỉ cho phép role = 'hdv' đăng nhập vào cổng hdv
                if ($user['role'] !== 'hdv') {
                    $_SESSION['error'] = "Tài khoản không phải hướng dẫn viên";
                    header('Location: admin.php?act=hdv-login');
                    exit;
                }

                // Đăng nhập thành công cho hdv
                $_SESSION['hdv'] = $user;
                $_SESSION['success'] = "Đăng nhập thành công!";
                header('Location: admin.php?act=hdv-dashboard');
                exit;
            } else {
                $_SESSION['error'] = "Tài khoản không tồn tại";
                header('Location: admin.php?act=hdv-login');
                exit;
            }
        }

        // Hiển thị form đăng nhập cho hướng dẫn viên
        require_once PATH_ROOT . 'views/hdv/login.php';
    }

    /**
     * Đăng xuất hướng dẫn viên
     */
    public function hdvLogout()
    {
        unset($_SESSION['hdv']);
        header('Location: admin.php?act=hdv-login');
        exit;
    }
}

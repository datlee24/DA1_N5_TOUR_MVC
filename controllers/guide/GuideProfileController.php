<?php
// controllers/guide/GuideProfileController.php
class GuideProfileController
{
    protected $guideModel;

    public function __construct()
    {
        if (!isset($_SESSION)) session_start();
        checkIsGuide();
        $this->guideModel = new GuideModel();
    }

    // Trang hồ sơ chi tiết
    public function detail()
    {
        $user_id = $_SESSION['guide']['user_id'] ?? null;
        if (!$user_id) {
            $_SESSION['error'] = "Bạn cần đăng nhập lại.";
            header("Location: index.php?act=login");
            exit;
        }

        // Lấy FULL hồ sơ theo user_id
        $profile = $this->guideModel->getFullProfile($user_id);

        $pageTitle = "Hồ sơ cá nhân";
        require_once PATH_GUIDE . "profile/detail.php";
    }

    // Trang chỉnh sửa hồ sơ (nếu cần)
    public function edit()
    {
        $user_id  = $_SESSION['guide']['user_id'];
        $profile = $this->guideModel->getFullProfile($user_id);

        $pageTitle = "Cập nhật hồ sơ";
        require_once PATH_GUIDE . "profile/edit.php";
    }

    // Cập nhật hồ sơ
    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            header("Location: index.php?act=profile");
            exit;
        }

        $user_id  = $_SESSION['guide']['user_id'] ?? null;
        if (!$user_id) {
            $_SESSION['error'] = "Phiên không hợp lệ, vui lòng đăng nhập lại.";
            header("Location: index.php?act=login");
            exit;
        }

        $userModel = new UserModel();

        $data = [
            'fullname' => trim($_POST['fullname']),
            'phone'    => trim($_POST['phone']),
            'email'    => trim($_POST['email']),
        ];

        // Nếu đổi mật khẩu
        if (!empty($_POST['password'])) {
            $data['password'] = $_POST['password'];
        }

        $userModel->updateProfile($user_id, $data);

        // Cập nhật session
        $_SESSION['guide'] = array_merge($_SESSION['guide'], $data);

        $_SESSION['success'] = "Cập nhật hồ sơ thành công!";
        header("Location: index.php?act=profile");
        exit;
    }
}

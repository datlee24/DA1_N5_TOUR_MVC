<?php
// controllers/guide/GuideProfileController.php
class GuideProfileController
{
    protected $userModel;

    public function __construct()
    {
        if (!isset($_SESSION)) session_start();
        checkIsGuide();
        $this->userModel = new UserModel();
    }

    public function edit()
    {
        $guide = $_SESSION['guide'];
        require_once PATH_GUIDE . "profile_edit.php";
    }

    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            header("Location: index.php?act=profile");
            exit;
        }

        $user_id  = $_SESSION['guide']['user_id'] ?? null;
        if (!$user_id) {
            $_SESSION['error'] = "Phiên không hợp lệ. Vui lòng đăng nhập lại.";
            header("Location: index.php?act=login");
            exit;
        }

        $data = [
            'fullname' => trim($_POST['fullname'] ?? ''),
            'phone'    => trim($_POST['phone'] ?? ''),
            'email'    => trim($_POST['email'] ?? '')
        ];

        // Nếu nhập password mới
        if (!empty($_POST['password'])) {
            $data['password'] = $_POST['password']; // nếu DB dùng hash hãy hash trước khi lưu
        }

        $this->userModel->updateProfile($user_id, $data);

        // Cập nhật session guide (giữ nguyên key user_id)
        $_SESSION['guide'] = array_merge($_SESSION['guide'], $data);

        $_SESSION['success'] = "Cập nhật hồ sơ thành công!";

        header("Location: index.php?act=/");
        exit;
    }
}

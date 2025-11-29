<?php
class GuideProfileController
{
    protected $userModel;

    public function __construct()
    {
        if (!isset($_SESSION)) session_start();
        checkGuide();
        $this->userModel = new UserModel();
    }

    // Trang xem hồ sơ chi tiết
    public function view()
    {
        $guide_id = $_SESSION['guide']['user_id'];

        $guideModel = new GuideModel();
        $profile = $guideModel->getFullProfile($guide_id);

        require_once PATH_GUIDE . "profile/profile.php";
    }

    // Form chỉnh sửa
    public function edit()
    {
        $guide = $_SESSION['guide']; 
        require_once PATH_GUIDE . "profile/profile_edit.php";
    }

    // Lưu thông tin cập nhật
    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            header("Location: index.php?act=profile");
            exit;
        }

        $user_id  = $_SESSION['guide']['user_id'];

        $data = [
            'fullname' => $_POST['fullname'],
            'phone'    => $_POST['phone'],
            'email'    => $_POST['email'],
            'password' => $_POST['password'],
        ];

        $this->userModel->updateProfile($user_id, $data);

        // Update session
        $_SESSION['guide'] = array_merge($_SESSION['guide'], $data);

        $_SESSION['success'] = "Cập nhật hồ sơ thành công!";

        header("Location: index.php?act=profile-view");
        exit;
    }
}

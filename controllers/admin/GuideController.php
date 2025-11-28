<?php

class GuideController
{
    protected $guideModel;
    protected $userModel;

    public function __construct()
    {
        checkIsAdmin();
        $this->guideModel = new GuideModel();
        $this->userModel = new UserModel();
    }

    public function index()
    {
        $guides = $this->guideModel->getAll();
        require_once PATH_ADMIN . 'guide/index.php';
    }

    public function create()
    {
        $users = $this->userModel->getAll();
        require_once PATH_ADMIN . 'guide/create.php';
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: admin.php?act=guide');
            exit;
        }

        $data = $this->collectData();
        $errors = $this->validateData($data);

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old'] = $data;
            header('Location: admin.php?act=guide-create');
            exit;
        }

        $this->guideModel->create($data);
        $_SESSION['success'] = "Thêm hướng dẫn viên thành công";
        unset($_SESSION['old']);
        header('Location: admin.php?act=guide');
        exit;
    }

    public function edit()
    {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            $_SESSION['error'] = "Thiếu mã hướng dẫn viên";
            header('Location: admin.php?act=guide');
            exit;
        }

        $guide = $this->guideModel->findById($id);
        if (!$guide) {
            $_SESSION['error'] = "Hướng dẫn viên không tồn tại";
            header('Location: admin.php?act=guide');
            exit;
        }

        $users = $this->userModel->getAll();
        require_once PATH_ADMIN . 'guide/edit.php';
    }

    public function update()
    {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            $_SESSION['error'] = "Thiếu mã hướng dẫn viên";
            header('Location: admin.php?act=guide');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: admin.php?act=guide-edit&id=' . $id);
            exit;
        }

        $data = $this->collectData();
        $errors = $this->validateData($data, $id);

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old'] = $data;
            header('Location: admin.php?act=guide-edit&id=' . $id);
            exit;
        }

        $guide = $this->guideModel->findById($id);
        if (!$guide) {
            $_SESSION['error'] = "Hướng dẫn viên không tồn tại";
            header('Location: admin.php?act=guide');
            exit;
        }

        $this->guideModel->update($id, $data);
        $_SESSION['success'] = "Cập nhật hướng dẫn viên thành công";
        unset($_SESSION['old']);
        header('Location: admin.php?act=guide');
        exit;
    }
public function delete()
    {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            $_SESSION['error'] = "Thiếu mã hướng dẫn viên";
            header('Location: admin.php?act=guide');
            exit;
        }

        $guide = $this->guideModel->findById($id);
        if (!$guide) {
            $_SESSION['error'] = "Hướng dẫn viên không tồn tại";
            header('Location: admin.php?act=guide');
            exit;
        }

        $this->guideModel->delete($id);
        $_SESSION['success'] = "Xóa hướng dẫn viên thành công";
        header('Location: admin.php?act=guide');
        exit;
    }

    protected function collectData()
    {
        return [
            'user_id' => isset($_POST['user_id']) ? (int) $_POST['user_id'] : null,
            'language' => trim($_POST['language'] ?? ''),
            'certificate' => trim($_POST['certificate'] ?? ''),
            'specialization' => trim($_POST['specialization'] ?? ''),
            'base_fee' => isset($_POST['base_fee']) ? (float) $_POST['base_fee'] : null,
        ];
    }

    protected function validateData($data, $id = null)
    {
        $errors = [];

        if (empty($data['user_id'])) {
            $errors['user_id'] = "Vui lòng chọn người dùng";
        } else {
            $user = $this->userModel->findById($data['user_id']);
            if (!$user) {
                $errors['user_id'] = "Người dùng không tồn tại";
            } else {
                $foundGuide = $this->guideModel->findByUserId($data['user_id']);
                if ($foundGuide && (int)$foundGuide['guide_id'] !== (int)$id) {
                    $errors['user_id'] = "Người dùng đã được gán làm hướng dẫn viên";
                }
            }
        }

        if (empty($data['language'])) {
            $errors['language'] = "Ngôn ngữ không được để trống";
        }

        if (empty($data['certificate'])) {
            $errors['certificate'] = "Số giấy phép hành nghề không được để trống";
        }

        if (empty($data['specialization'])) {
            $errors['specialization'] = "Chuyên môn/Khu vực không được để trống";
        }

        if ($data['base_fee'] === null || $data['base_fee'] === '') {
            $errors['base_fee'] = "Thù lao cơ bản không được để trống";
        } elseif (!is_numeric($data['base_fee']) || $data['base_fee'] < 0) {
            $errors['base_fee'] = "Thù lao cơ bản phải là số không âm";
        }

        return $errors;
    }
}

<?php

class GuideController
{
    protected $guideModel;

    public function __construct()
    {
        checkIsAdmin();
        $this->guideModel = new GuideModel();
    }

    public function index()
    {
        $guides = $this->guideModel->getAll();
        require_once PATH_ADMIN . 'guide/index.php';
    }

    public function create()
    {
        require_once PATH_ADMIN . 'guide/create.php';
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: admin.php?act=guide");
            exit;
        }

        $data = [
            'user_id'        => $_POST['user_id'] ?? null,
            'language'       => trim($_POST['language']),
            'certificate'    => trim($_POST['certificate']),
            'experience'     => trim($_POST['experience']),
            'specialization' => trim($_POST['specialization']),
        ];

        $this->guideModel->create($data);

        $_SESSION['success'] = "Thêm hướng dẫn viên thành công";
        header("Location: admin.php?act=guide");
        exit;
    }

    public function edit()
    {
        $id = $_GET['id'] ?? null;
        $guide = $this->guideModel->findById($id);

        if (!$guide) {
            $_SESSION['error'] = "Hướng dẫn viên không tồn tại";
            header("Location: admin.php?act=guide");
            exit;
        }

        require_once PATH_ADMIN . 'guide/edit.php';
    }

    public function update()
    {
        $id = $_GET['id'] ?? null;

        $data = [
            'user_id'        => $_POST['user_id'] ?? null,
            'language'       => trim($_POST['language']),
            'certificate'    => trim($_POST['certificate']),
            'experience'     => trim($_POST['experience']),
            'specialization' => trim($_POST['specialization']),
        ];

        $this->guideModel->update($id, $data);

        $_SESSION['success'] = "Cập nhật hướng dẫn viên thành công";
        header("Location: admin.php?act=guide");
        exit;
    }
}

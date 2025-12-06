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
        require_once PATH_ADMIN . 'guide/create.php';
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: admin.php?act=guide");
            exit;
        }

        // prepare guide data
        $data = [
            'language'       => trim($_POST['language']),
            'certificate'    => trim($_POST['certificate']),
            'experience'     => trim($_POST['experience']),
            'specialization' => trim($_POST['specialization']),
        ];

        // determine or create linked user
        $user_id = $_POST['user_id'] ?? null;
        $email = trim($_POST['email'] ?? '');
        if (!$user_id && $email) {
            // if email exists, reuse user
            $existing = $this->userModel->findByEmail($email);
            if ($existing) {
                $user_id = $existing['user_id'];
            } else {
                // create user
                $u = [
                    'username' => trim($_POST['username'] ?? ''),
                    'password' => trim($_POST['password'] ?? ''),
                    'fullname' => trim($_POST['fullname'] ?? ''),
                    'email'    => $email,
                    'phone'    => trim($_POST['phone'] ?? ''),
                    'role'     => 'hdv',
                    'status'   => 1
                ];
                $newId = $this->userModel->create($u);
                if ($newId) $user_id = $newId;
            }
        }

        if (!$user_id) {
            $_SESSION['error'] = "Không xác định được user liên kết cho HDV. Vui lòng cung cấp email hoặc chọn user.";
            header("Location: admin.php?act=guide-create");
            exit;
        }

        $data['user_id'] = $user_id;
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

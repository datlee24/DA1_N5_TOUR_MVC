<?php

class CustomerController
{
    protected $customerModel;

    public function __construct()
    {
        checkIsAdmin();
        $this->customerModel = new CustomerModel();
    }

    // === Danh sách + tìm kiếm ===
    public function index()
    {
        $keyword = $_GET['keyword'] ?? '';

        if ($keyword !== '') {
            $customers = $this->customerModel->search($keyword);
        } else {
            $customers = $this->customerModel->getAll();
        }

        require_once PATH_ADMIN . "customer/index.php";
    }

    // === Form thêm khách hàng ===
    public function create()
    {
        require_once PATH_ADMIN . "customer/create.php";
    }

    // === Lưu khách hàng ===
    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: admin.php?act=customer");
            exit;
        }

        $data = [
            'fullname'   => $_POST['fullname'] ?? '',
            'gender'     => $_POST['gender'] ?? '',
            'birthdate'  => $_POST['birthdate'] ?? null,
            'phone'      => $_POST['phone'] ?? '',
            'email'      => $_POST['email'] ?? '',
            'id_number'  => $_POST['id_number'] ?? '',
            'notes'      => $_POST['notes'] ?? '',
        ];

        $this->customerModel->create($data);

        $_SESSION['success'] = "Thêm khách hàng thành công!";
        header("Location: admin.php?act=customer");
        exit;
    }
}

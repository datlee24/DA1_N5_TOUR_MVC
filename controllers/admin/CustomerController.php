
<?php
class CustomerController
{
    protected $customerModel;

    public function __construct()
    {
        checkIsAdmin();
        $this->customerModel = new CustomerModel();
    }

    // index: nếu có param `query` -> trả JSON cho ajax search; ngược lại hiển thị view list
    public function index()
    {
        $isAjaxQuery = isset($_GET['query']) && trim($_GET['query']) !== '';

        if ($isAjaxQuery) {
            header("Content-Type: application/json; charset=utf-8");
            $q = trim($_GET['query']);
            $rows = $this->customerModel->search($q);
            echo json_encode($rows);
            exit;
        }

        // legacy: normal listing
        $keyword = $_GET['keyword'] ?? '';

        if ($keyword !== '') {
            $customers = $this->customerModel->search($keyword);
        } else {
            $customers = $this->customerModel->getAll();
        }

        require_once PATH_ADMIN . "customer/index.php";
    }

    public function create()
    {
        require_once PATH_ADMIN . "customer/create.php";
    }

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
?>

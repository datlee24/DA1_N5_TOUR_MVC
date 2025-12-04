<?php

class GuideCustomerController
{
    protected $customerModel;

    public function __construct()
    {
        if (!isset($_SESSION)) {
            session_start();
        }

        checkIsGuide();

        $this->customerModel = new CustomerModel();
    }

    /**
     * Danh sách khách hàng (dùng bảng customer)
     */
    public function index()
    {
        $customers = $this->customerModel->getAll();

        require_once PATH_GUIDE . "customer/customers.php";
    }
}



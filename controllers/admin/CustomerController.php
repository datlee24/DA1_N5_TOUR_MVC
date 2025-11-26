<?php

class CustomerController
{
    protected $customerModel;

    public function __construct()
    {
        checkIsAdmin();
        $this->customerModel = new CustomerModel();
    }

    /**
     * Trang danh sách khách hàng
     */
    public function index()
    {
        $customers = $this->customerModel->getAll();
        require_once PATH_ADMIN . 'customer/index.php';
    }
}



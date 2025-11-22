<?php

class TourExpenseController
{
    protected $tourExpenseModel;

    public function __construct()
    {
        checkIsAdmin();
        $this->tourExpenseModel = new TourExpenseModel();
    }

    public function index()
    {
        $expenses = $this->tourExpenseModel->getAll();
        require_once PATH_ADMIN . 'tour_expense/index.php';
    }
}


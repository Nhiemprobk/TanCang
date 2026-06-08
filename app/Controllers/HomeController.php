<?php

require_once 'app/Models/HomeModel.php';

class HomeController {
    private $pdo;
    private $homeModel;

    public function __construct() {
        require 'config/database.php';
        $this->pdo = $pdo;
        $this->homeModel = new HomeModel($this->pdo);
    }

    public function index() {
        $pendingCount = $this->homeModel->getPendingOrdersCount();
        $totalRevenue = $this->homeModel->getTotalRevenue();
        $revenueSummary = $this->homeModel->getRevenueSummary();
        $weekLabels = $revenueSummary['weekLabels'];
        $revenueLabels = $revenueSummary['revenueLabels'];
        $revenueData = $revenueSummary['revenueData'];
        $recentOrders = $this->homeModel->getRecentOrders();

        require 'app/Views/home/index.php';
    }
}

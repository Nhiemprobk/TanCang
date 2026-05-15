<?php

class PricingController {
    private $pdo;

    public function __construct() {
        require 'config/database.php';
        $this->pdo = $pdo;
    }

    // Hiển thị giao diện cài đặt giá
    public function index() {
        $stmt = $this->pdo->query("SELECT * FROM logis_pricing ORDER BY container_type ASC");
        $prices = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        require_once 'app/Views/pricing/index.php';
    }

    // Lưu thay đổi giá khi bấm Submit
    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['prices'])) {
            foreach ($_POST['prices'] as $type => $price) {
                // Xóa dấu chấm phân cách ngàn để lưu vào DB (ví dụ: 500.000 -> 500000)
                $clean_price = str_replace('.', '', $price);
                
                $stmt = $this->pdo->prepare("UPDATE logis_pricing SET price = ? WHERE container_type = ?");
                $stmt->execute([$clean_price, $type]);
            }
            $_SESSION['success_msg'] = "Đã cập nhật biểu giá thành công!";
        }
        header("Location: index.php?page=pricing");
        exit();
    }
}
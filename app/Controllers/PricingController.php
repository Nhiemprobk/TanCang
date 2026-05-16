<?php

class PricingController {
    private $pdo;

    public function __construct() {
        require 'config/database.php';
        $this->pdo = $pdo;
    }

    // Hiển thị giao diện cài đặt giá
    public function index() {
        if (($_SESSION['role_level'] ?? 4) > 2) {
            // 1. Nạp tin nhắn gắt gỏng vào Session để View hứng hiện Popup
            $_SESSION['error_msg'] = "Truy cập bị từ chối! Tài khoản của bạn không có thẩm quyền vào phân khu này.";
            // 2. Lấy đường link của trang trước đó, nếu trình duyệt không lưu thì mặc định về trang home
            $backUrl = $_SERVER['HTTP_REFERER'] ?? 'index.php?page=home';
            // 3. Đá người dùng quay lại nơi họ vừa đứng
            header("Location: " . $backUrl);
            exit();
        }
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
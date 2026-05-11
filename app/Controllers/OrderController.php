<?php

class OrderController {
    
    // Khai báo thuộc tính $pdo cho Controller
    private $pdo;

    // Hàm khởi tạo: Tự động chạy ngay khi gọi OrderController
    public function __construct() {
        global $pdo;
        require_once 'config/database.php';
        $this->pdo = $pdo;
    }

    public function index() {
        // 1. Lấy danh sách đơn hàng (Thay vì $pdo->query thì dùng $this->pdo->query)
        $stmt = $this->pdo->query("SELECT * FROM logis_orders ORDER BY id DESC");
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // 2. Khởi tạo mảng thống kê cho bảng SUM
        $summary = [
            'ha_rong' => ['20' => 0, '40' => 0, '45' => 0, 'tong' => 0],
            'cap_rong' => ['20' => 0, '40' => 0, '45' => 0, 'tong' => 0]
        ];

        // 3. Tính toán số liệu tổng
        foreach ($orders as $o) {
            $type = ($o['action_type'] == 'Hạ rỗng') ? 'ha_rong' : 'cap_rong';
            $summary[$type]['20'] += $o['qty_20'];
            $summary[$type]['40'] += $o['qty_40'];
            $summary[$type]['45'] += $o['qty_45'];
            $summary[$type]['tong'] += ($o['qty_20'] + $o['qty_40'] + $o['qty_45']);
        }

        // 4. Gọi View và truyền dữ liệu ($orders và $summary) sang
        require_once 'app/Views/orders/index.php';
    }
}
?>
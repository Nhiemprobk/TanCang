<?php

class UserController {
    private $pdo;

    // Khởi tạo kết nối Database
    public function __construct() {
        global $pdo;
        require_once 'config/database.php';
        $this->pdo = $pdo;
    }

    public function index() {
        // 1. Lấy danh sách user kèm tên nhóm quyền (JOIN bảng roles)
        $stmt = $this->pdo->query("
            SELECT u.*, r.role_name 
            FROM users u 
            LEFT JOIN roles r ON u.role_id = r.id 
            ORDER BY u.id DESC
        ");
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // 2. TÍNH TOÁN THỐNG KÊ (Phải đặt ở đây trước khi gọi View)
        $stats = [
            'total' => count($users),
            'active' => count(array_filter($users, function($u) {
                return isset($u['is_active']) && $u['is_active'] == 1;
            })),
            'locked' => count(array_filter($users, function($u) {
                return isset($u['is_active']) && $u['is_active'] == 0;
            }))
        ];

        // 3. Gọi View và truyền dữ liệu sang
        require_once 'app/Views/users/index.php';
    }
}
?>
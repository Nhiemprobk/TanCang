<?php

class RoleController {
    private $pdo;

    public function __construct() {
        // Nhúng file kết nối CSDL có sẵn của bạn
        require 'config/database.php';
        $this->pdo = $pdo;
    }

    // 1. Giao diện hiển thị danh sách quyền
    public function index() {
        if (($_SESSION['role_level'] ?? 4) > 1) {
            header("Location: index.php?page=orders");
            exit();
        }
        // Lấy dữ liệu từ đúng bảng Roles của bạn
        $stmt = $this->pdo->query("SELECT * FROM Roles ORDER BY id ASC");
        $roles = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Gọi đến file View hiển thị
        require_once 'app/Views/roles/index.php';
    }

    // 2. Xử lý lưu quyền mới khi bấm nút Lưu
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name']);
            $level = (int)$_POST['level']; // Lấy cấp độ từ form
            $description = trim($_POST['description']);
            
            if (!empty($name) && $level >= 1 && $level <= 4) {
                try {
                    // Thêm cột level vào câu lệnh INSERT
                    $stmt = $this->pdo->prepare("INSERT INTO Roles (name, level, description) VALUES (?, ?, ?)");
                    $stmt->execute([$name, $level, $description]);
                    $_SESSION['success_msg'] = "Đã thêm nhóm quyền cấp độ " . $level . " thành công!";
                } catch (PDOException $e) {
                    $_SESSION['error_msg'] = "Tên quyền này đã tồn tại!";
                }
            }
            
            header("Location: index.php?page=roles");
            exit();
        }
    }
}